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

use Cake\Database\Expression\IdentifierExpression;
use Cake\ORM\Association;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use Closure;
use Polymorphic\ORM\Association\Loader\MorphLoader;
use Polymorphic\ORM\PolyMorphicTable;
use RuntimeException;

/**
 * Represents an 1 - N relationship where the source side of the relation is
 * related to only one record in the target table conditioned by the class name.
 *
 * An example of a BelongsTo association would be Notifications belongs to User.
 */
class MorphsTo extends Association\BelongsTo
{
    /**
     * Valid strategies for this type of association
     *
     * @var array<string>
     */
    protected $_validStrategies = [
        self::STRATEGY_SELECT
    ];

    /**
     * The strategy name to be used to fetch associated records. Some association
     * types might not implement but one strategy to fetch records.
     *
     * @var string
     */
    protected $_strategy = self::STRATEGY_SELECT;

    /**
     * Association type for morph to many associations.
     *
     * @var string
     */
    public const MORPH_TO_ONE = 'morphToOne';

    public function __construct(string $alias, array $options = []) {
        parent::__construct($alias, $options);
        $this->_bindingKey = $this->_getMorphs($this->_propertyName(), $options['morphType'], $options['morphId']);
    }

    /**
     * Get the polymorphic relationship columns.
     *
     * @param string $name
     * @param string|null $type
     * @param string|null $id
     * @return array
     */
    protected function _getMorphs(string $name, ?string $type = null, ?string $id = null): array
    {
        return [$type ?: $name.'_type', $id ?: $name.'_id'];
    }

    /**
     * Returns default property name based on association name.
     *
     * @return string
     */
    protected function _propertyName(): string
    {
        [, $name] = pluginSplit($this->_name);

        return Inflector::underscore(Inflector::singularize($name));
    }

    /**
     * Gets the table instance for the target side of the association.
     *
     * @return Table
     */
    public function getTarget(): Table
    {
        return $this->_sourceTable;
    }

    /**
     * Get the relationship type.
     *
     * @return string
     */
    public function type(): string
    {
        return self::MORPH_TO_ONE;
    }

    /**
     * Returns a single or multiple conditions to be appended to the generated join
     * clause for getting the results on the target table.
     *
     * @param array<string, mixed> $options list of options passed to attachTo method
     * @return array<IdentifierExpression>
     * @throws RuntimeException if the number of columns in the foreignKey do not
     * match the number of columns in the target table primaryKey
     */
    protected function _joinCondition(array $options): array
    {
        $conditions = [];
        $tAlias = $this->_name;
        $sAlias = $this->_sourceTable->getAlias();
        $foreignKey = (array)$options['foreignKey'];
        $bindingKey = (array)$this->getBindingKey();

        if (count($foreignKey) !== count($bindingKey)) {
            if (empty($bindingKey)) {
                $msg = 'The "%s" table does not define a primary key. Please set one.';
                throw new RuntimeException(sprintf($msg, $this->getTarget()->getTable()));
            }

            $msg = 'Cannot match provided foreignKey for "%s", got "(%s)" but expected foreign key for "(%s)"';
            throw new RuntimeException(sprintf(
                $msg,
                $this->_name,
                implode(', ', $foreignKey),
                implode(', ', $bindingKey)
            ));
        }

        foreach ($foreignKey as $k => $f) {
            $field = sprintf('%s.%s', $tAlias, $bindingKey[$k]);
            $value = new IdentifierExpression(sprintf('%s.%s', $sAlias, $f));
            $conditions[$field] = $value;
        }

        return $conditions;
    }

    /**
     * @inheritDoc
     */
    public function eagerLoader(array $options): Closure
    {
        $loader = new MorphLoader([
            'sourceAlias' => $this->getSource()->getAlias(),
            'bindingKeys' => $this->getBindingKey(),
            'strategy' => $this->getStrategy(),
            'morphAlias' => $this->_name,
            'associationType' => $this->type(),
            'finder' => [$this, 'find'],
        ]);

        return $loader->buildEagerLoader($options);
    }

    /**
     * Proxies the finding operation to the target table's find method
     * and modifies the query accordingly based of this association
     * configuration
     *
     * @param array<string, mixed>|string|null $type the type of query to perform, if an array is passed,
     *   it will be interpreted as the `$options` parameter
     * @param array<string, mixed> $options The options to for the find
     * @see \Cake\ORM\Table::find()
     * @return \Cake\ORM\Query
     */
    public function find($type = null, array $options = []): Query
    {
        $type = $type ?: $this->getFinder();
        [$type, $opts] = $this->_extractFinder($type);

        return $this->getTarget()
            ->find($type, $options + $opts)
            ->where($this->getConditions());
    }
}
