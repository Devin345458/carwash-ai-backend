<?php
namespace App\Model\Table;

use App\Error\Exception\ValidationException;
use App\Model\Entity\ActivityLog;
use ArrayObject;
use Cake\Database\Schema\TableSchemaInterface;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Log\Log;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;
use Psr\Log\LogLevel;
use Throwable;

/**
 * ActivityLogs Model
 *
 * @property UsersTable|BelongsTo $Users
 * @property CommentsTable|BelongsTo $Comments
 * @property RepairsTable|BelongsTo $Repairs
 * @property EquipmentsTable|BelongsTo $Equipments
 * @property MaintenancesTable|BelongsTo $Maintenances
 * @property InventoriesTable|BelongsTo $Inventories
 * @property CompletedInventoriesTable|BelongsTo $Completedinventorys
 * @method ActivityLog get($primaryKey, $options = [])
 * @method ActivityLog newEntity($data = null, array $options = [])
 * @method ActivityLog[] newEntities(array $data, array $options = [])
 * @method ActivityLog|bool save(EntityInterface $entity, $options = [])
 * @method ActivityLog saveOrFail(EntityInterface $entity, $options = [])
 * @method ActivityLog patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method ActivityLog[] patchEntities($entities, array $data, array $options = [])
 * @method ActivityLog findOrCreate($search, callable $callback = null, $options = [])
 */
class ActivityLogsTable extends Table
{
    use LocatorAwareTrait;

    protected $_exclude_logging = [
        'DebugKit\Model\Entity\Panel',
        'DebugKit\Model\Entity\Request',
        'Cake\ORM\Entity',
        'App\Model\Entity\ActivityLog',
    ];

    /**
     * Add data type
     *
     * @param TableSchemaInterface $table the table
     * @return TableSchemaInterface
     */
    protected function _initializeSchema(TableSchemaInterface $table): TableSchemaInterface
    {
        $schema = parent::_initializeSchema($table);
        $schema->setColumnType('data', 'json');

        return $schema;
    }

    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('activity_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                ],
            ],
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'issuer_id',
        ]);

        $this->belongsTo('Comments', [
            'foreignKey' => 'object_id',
            'conditions' => ['object_model' => 'Comments'],
        ]);

        $this->belongsTo('Repairs', [
            'foreignKey' => 'object_id',
            'conditions' => ['object_model' => 'Repairs'],
        ]);

        $this->belongsTo('Equipments', [
            'foreignKey' => 'object_id',
            'conditions' => ['object_model' => 'Equipments'],
        ]);

        $this->belongsTo('Maintenances', [
            'foreignKey' => 'object_id',
            'conditions' => ['object_model' => 'Maintenances'],
        ]);

        $this->belongsTo('Inventories', [
            'foreignKey' => 'object_id',
            'conditions' => ['object_model' => 'Inventories'],
        ]);

        $this->belongsTo('Completedinventorys', [
            'foreignKey' => 'object_id',
            'conditions' => ['object_model' => 'Completedinventorys'],
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param  Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->allowEmptyString('id', 'create');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->scalar('scope_model')
            ->maxLength('scope_model', 64)
            ->requirePresence('scope_model', 'create')
            ->notEmptyString('scope_model');

        $validator
            ->scalar('issuer_model')
            ->maxLength('issuer_model', 64)
            ->notEmptyString('issuer_model');

        $validator
            ->scalar('object_model')
            ->maxLength('object_model', 64)
            ->notEmptyString('object_model');

        $validator
            ->scalar('level')
            ->maxLength('level', 16)
            ->requirePresence('level', 'create')
            ->notEmptyString('level');

        $validator
            ->scalar('action')
            ->maxLength('action', 64)
            ->notEmptyString('action');

        $validator
            ->scalar('message')
            ->notEmptyString('message');

        $validator
            ->scalar('data')
            ->notEmptyString('data');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param  RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['issuer_id'], 'Users'));

        return $rules;
    }

    /**
     * Logs a activity based on event
     *
     * @param EventInterface $event Event
     * @param EntityInterface $entity Entity
     * @param ArrayObject $options Options
     * @return void
     */
    public function logActivity(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if (!Router::getRequest()) {
            return;
        }
        if (in_array(get_class($entity), $this->_exclude_logging)) {
            return;
        }

        $table = $this->getTableLocator()->get($entity->getSource());
        try {
            $user_id = Router::getRequest()->getAttribute('identity')->id;
            if (!$user_id) {
                return;
            }
        } catch (Throwable $exception) {
            return;
        }

        $activity = $this->newEntity([
            'scope_model' => $table->getRegistryAlias(),
            'scope_id' => $table->getPrimaryKey(),
            'issuer_model' => 'Users',
            'issuer_id' => $user_id,
            'object_model' => Inflector::singularize($table->getRegistryAlias()),
            'object_id' => $entity->get($table->getPrimaryKey()),
            'level' => LogLevel::INFO,
        ]);

        if ($event->getName() === 'Model.afterSave') {
            if ($entity->isNew()) {
                $this->logCreate($entity, $activity);
            } else {
                $this->logUpdate($entity, $activity);
            }
        } elseif ($event->getName() === 'Model.afterDelete') {
            $this->logDelete($entity, $activity);
        } else {
            return;
        }

        if (!$this->save($activity)) {
            throw new ValidationException($activity);
        }
    }

    /**
     * Log a entity being created
     *
     * @param EntityInterface $entity Entity
     * @param ActivityLog $activity Activity Entity
     * @return void
     */
    public function logCreate(EntityInterface $entity, ActivityLog $activity): void
    {
        $activity->data = ['entity' => $entity->toArray()];
        $activity->message = Router::getRequest()->getAttribute('identity')->full_name . ' created a new ' . $entity->getSource();
        $activity->action = 'created';
    }

    /**
     * Log a entity being updated
     *
     * @param EntityInterface $entity Entity
     * @param ActivityLog $activity Activity Entity
     * @return void
     */
    public function logUpdate(EntityInterface $entity, ActivityLog $activity): void
    {
        $data['entity'] = $entity->toArray();
        foreach ($entity->extract($entity->getVisible(), true) as $field => $value) {
            $data['changed'][] = [
                'field' => $field,
                'value' => $value,
            ];
        }
        $activity->data = $data;
        $activity->message = Router::getRequest()->getAttribute('identity')->full_name . ' update a new ' . $entity->getSource();
        $activity->action = 'updated';
    }

    /**
     * Log a entity being deleted
     *
     * @param EntityInterface $entity Entity
     * @param ActivityLog $activity Activity Entity
     * @return void
     */
    public function logDelete(EntityInterface $entity, ActivityLog $activity): void
    {
        $activity->data = ['entity' => $entity->toArray()];
        $activity->message = Router::getRequest()->getAttribute('identity')->full_name . ' delete a ' . $entity->getSource();
        $activity->action = 'deleted';
    }
}
