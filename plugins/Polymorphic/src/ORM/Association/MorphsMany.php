<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Polymorphic\ORM\Association;

use Cake\Collection\Collection;
use Cake\Database\Expression\FieldInterface;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\InvalidPropertyInterface;
use Cake\ORM\Association\DependentDeleteHelper;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Association\Loader\SelectLoader;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use Closure;
use InvalidArgumentException;

/**
 * Represents an N - 1 relationship where the target side of the relationship
 * will have one or multiple records per each one in the source side conditioned by the class name.
 *
 * An example of a HasMany association would be User has many Notifications.
 */
class MorphsMany extends HasMany
{
    /**
     * Order in which target records should be returned
     *
     * @var mixed
     */
    protected $_sort;

    /**
     * The type of join to be used when adding the association to a query
     *
     * @var string
     */
    protected $_joinType = Query::JOIN_TYPE_INNER;

    /**
     * The strategy name to be used to fetch associated records.
     *
     * @var string
     */
    protected $_strategy = self::STRATEGY_SELECT;

    /**
     * Valid strategies for this type of association
     *
     * @var array<string>
     */
    protected $_validStrategies = [
        self::STRATEGY_SELECT,
    ];

    /**
     *
     */
    protected $_typeKey = null;

    /**
     * @var string|null
     */
    protected $_morphKey = null;

    /**
     * Saving strategy that will only append to the links set
     *
     * @var string
     */
    public const SAVE_APPEND = 'append';

    /**
     * Saving strategy that will replace the links with the provided set
     *
     * @var string
     */
    public const SAVE_REPLACE = 'replace';

    /**
     * Saving strategy to be used by this association
     *
     * @var string
     */
    protected $_saveStrategy = self::SAVE_APPEND;

    /**
     * Association type for morph to many associations.
     *
     * @var string
     */
    public const MORPH_TO_MANY = 'morphToMany';


    public function __construct(string $alias, array $options = [])
    {
        $this->_morphKey = $options['morphKey'];
        parent::__construct($alias, $options);
    }

    /**
     * Takes an entity from the source table and looks if there is a field
     * matching the property name for this association. The found entity will be
     * saved on the target table for this association by passing supplied
     * `$options`
     *
     * @param  EntityInterface $entity an entity from the source table
     * @param array<string, mixed> $options options to be passed to the save method in the target table
     * @return EntityInterface|false false if $entity could not be saved, otherwise it returns
     * the saved entity
     * @throws InvalidArgumentException when the association data cannot be traversed.
     *@see \Cake\ORM\Table::save()
     */
    public function saveAssociated(EntityInterface $entity, array $options = [])
    {
        $targetEntities = $entity->get($this->getProperty());

        $isEmpty = in_array($targetEntities, [null, [], '', false], true);
        if ($isEmpty) {
            if (
                $entity->isNew() ||
                $this->getSaveStrategy() !== self::SAVE_REPLACE
            ) {
                return $entity;
            }

            $targetEntities = [];
        }

        if (!is_iterable($targetEntities)) {
            $name = $this->getProperty();
            $message = sprintf('Could not save %s, it cannot be traversed', $name);
            throw new InvalidArgumentException($message);
        }

        $id = $entity->extract((array)$this->getBindingKey());
        $id = $id[array_key_first($id)];
        $foreignKeyReference = [
            $this->getForeignKey() => $id,
            $this->getTypeKey() => $entity->getSource()
        ];

        $options['_sourceTable'] = $this->getSource();

        if (
            $this->_saveStrategy === self::SAVE_REPLACE &&
            !$this->_unlinkAssociated($foreignKeyReference, $entity, $this->getTarget(), $targetEntities, $options)
        ) {
            return false;
        }

        if (!is_array($targetEntities)) {
            $targetEntities = iterator_to_array($targetEntities);
        }
        if (!$this->_saveTarget($foreignKeyReference, $entity, $targetEntities, $options)) {
            return false;
        }

        return $entity;
    }

    /**
     * Get the relationship type.
     *
     * @return string
     */
    public function type(): string
    {
        return self::ONE_TO_MANY;
    }

    public function getMorphName(): string
    {
        return $this->_morphKey;
    }

    /**
     * Gets the name of the field representing the foreign key to the source table.
     *
     * @return array<string>|string
     */
    public function getForeignKey()
    {
        if ($this->_foreignKey === null) {
            $this->_foreignKey = $this->getMorphName() . '_id';
        }

        return $this->_foreignKey;
    }

    /**
     * Gets the name of the field representing the foreign key to the source table.
     *
     * @return array<string>|string
     */
    public function getTypeKey()
    {
        if ($this->_typeKey === null) {
            $this->_typeKey = $this->getMorphName() . '_type';
        }

        return $this->_typeKey;
    }

    /**
     * @inheritDoc
     */
    public function eagerLoader(array $options): Closure
    {
        $options = array_merge_recursive($options, [
            'conditions' => [$this->getTarget()->getAlias() . '.' . $this->getTypeKey() => $this->getSource()->getAlias()]
        ]);

        return parent::eagerLoader($options);
    }
}
