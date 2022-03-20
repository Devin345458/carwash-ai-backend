<?php
/**
 * CakeManager (http://cakemanager.org)
 * Copyright (c) http://cakemanager.org
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) http://cakemanager.org
 * @link          http://cakemanager.org CakeManager Project
 * @since         1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Model\Behavior;

use ArrayObject;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Throwable;

/**
 * WhoDidIt behavior
 */
class WhoDidItBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array
     *
     * ### OPTIONS
     * - created_by         string      field to use
     * - modified_by        string      field to use
     * - userModel          string      model to use
     * - fields             array       list of fields to get on query
     */
    protected $_defaultConfig = [
        'created_by' => 'created_by_id',
        'modified_by' => 'modified_by_id',
        'createdByPropertyName' => 'created_by',
        'modifiedByPropertyName' => 'modified_by',
        'userModel' => 'Users',
        'contain' => true,
        'fields' => [],
    ];

    /**
     * Holder for table.
     *
     * @var Table
     */
    protected $Table;

    /**
     * Constructor
     *
     * @param Table $table Table who requested the behavior.
     * @param array $config Options.
     */
    public function __construct(Table $table, array $config = [])
    {
        parent::__construct($table, $config);

        $this->Table = $table;

        if ($this->getConfig('created_by')) {
            $this->Table->belongsTo('CreatedBy', [
                'foreignKey' => $this->getConfig('created_by'),
                'className' => $this->getConfig('userModel'),
                'propertyName' => $this->getConfig('createdByPropertyName'),
            ]);
        }

        if ($this->getConfig('modified_by')) {
            $this->Table->belongsTo('ModifiedBy', [
                'foreignKey' => $this->getConfig('modified_by'),
                'className' => $this->getConfig('userModel'),
                'propertyName' => $this->getConfig('modifiedByPropertyName'),
            ]);
        }
    }

    /**
     * Initialize
     *
     * Initialize callback for Behaviors.
     *
     * @param array $config Options.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    /**
     * BeforeFind callback
     *
     * Used to add CreatedBy and ModifiedBy to the contain of the query.
     *
     * @param EventInterface $event Event.
     * @param Query $query The Query object.
     * @return void
     */
    public function beforeFind(EventInterface $event, Query $query)
    {
        $contain = $query->getContain();
        $fields = $this->getConfig('fields');
        if ($this->getConfig('contain') || isset($contain['CreatedBy'])) {
            if ($this->getConfig('created_by')) {
                if (isset($contain['CreatedBy']['fields'])) {
                    $fields = $contain['CreatedBy']['fields'];
                }
                $query->contain(['CreatedBy' => ['fields' => $fields]]);
            }
        }
        if ($this->getConfig('contain') || isset($contain['ModifiedBy'])) {
            if ($this->getConfig('modified_by')) {
                if (isset($contain['ModifiedBy']['fields'])) {
                    $fields = $contain['ModifiedBy']['fields'];
                }
                $query->contain(['ModifiedBy' => ['fields' => $fields]]);
            }
        }
    }

    /**
     * BeforeSave callback
     *
     * Used to add the user to the `created_by` and `modified_by` fields.
     *
     * @param EventInterface $event Event.
     * @param EntityInterface $entity The Entity to save on.
     * @param array $options Options.
     * @return void
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        if (!Router::getRequest()) {
            return;
        }

        $auth = false;
        try {
            $auth = Router::getRequest()->getAttribute('identity')->getOriginalData();
        } catch (Throwable $exception) {
            return;
        }

        if (empty($auth)) {
            return;
        }
        $id = $auth['id'];

        if ($entity->isNew()) {
            if ($this->getConfig('created_by')) {
                $entity->set($this->getConfig('created_by'), $id);
            }
        }

        if ($this->getConfig('modified_by')) {
            $entity->set($this->getConfig('modified_by'), $id);
        }
    }
}
