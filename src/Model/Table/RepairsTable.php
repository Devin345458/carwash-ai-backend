<?php

namespace App\Model\Table;

use App\Model\Entity\Item;
use App\Model\Entity\Repair;
use App\Utility\NotificationManager;
use ArrayObject;
use Aws\S3\S3Client;
use Cake\Collection\Collection;
use Cake\Controller\Component\AuthComponent;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\RepositoryInterface;
use Cake\Event\Event;
use Cake\I18n\Date;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Association\HasOne;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Validation\Validator;
use Pusher\PusherException;

/**
 * Repairs Model
 *
 * @property UsersTable|BelongsTo $Users
 * @property StoresTable|BelongsTo $Stores
 * @property EquipmentsTable|BelongsTo $Equipments
 * @property CommentsTable|HasMany $Comments
 * @property ActivityLogsTable|HasMany $ActivityLogs
 * @property FilesTable|BelongsToMany Files
 * @property ItemsTable|BelongsToMany Items
 * @property RepairRemindersTable|HasMany RepairReminders
 * @method Repair get($primaryKey, $options = [])
 * @method Repair newEntity($data = null, array $options = [])
 * @method Repair[] newEntities(array $data, array $options = [])
 * @method Repair|bool save(EntityInterface $entity, $options = [])
 * @method Repair|bool saveOrFail(EntityInterface $entity, $options = [])
 * @method Repair patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Repair[] patchEntities($entities, array $data, array $options = [])
 * @method Repair findOrCreate($search, callable $callback = null, $options = [])
 * @mixin TimestampBehavior
 */
class RepairsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('repairs');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('WhoDidIt');

        $this->hasMany('Comments')
            ->setConditions(['commentable_type' => get_class($this)])
            ->setForeignKey('commentable_id')
            ->setBindingKey('id');


        $this->belongsTo('AssignedTo', [
            'foreignKey' => 'assigned_to_id',
            'joinType' => 'LEFT',
            'className' => 'Users',
        ]);

        $this->belongsTo('AssignedBy', [
            'foreignKey' => 'assigned_by_id',
            'joinType' => 'LEFT',
            'className' => 'Users',
        ]);

        $this->belongsTo('Stores');

        $this->belongsTo('Maintenances');

        $this->belongsTo('Equipments', [
            'foreignKey' => 'equipment_id',
            'joinType' => 'LEFT',
        ]);

        $this->belongsToMany('Items');

        $this->hasMany('Comments')
            ->setConditions(['commentable_type' => get_class($this)])
            ->setForeignKey('commentable_id')
            ->setBindingKey('id')
            ->setSaveStrategy('append');

        $this->hasMany('ActivityLogs')
            ->setConditions(['scope_model' => 'Repairs'])
            ->setForeignKey('object_id')
            ->setBindingKey('id');

        $this->hasMany('RepairReminders');

        $this->hasOne('MyReminders')
            ->setConditions(['user_id' => Router::getRequest() ? Router::getRequest()->getAttribute('identity')->id : null])
            ->setForeignKey('repair_id')
            ->setBindingKey('id')
            ->setProperty('my_reminder')
            ->setClassName('RepairReminders');

        $this->belongsToMany('Files');

    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notBlank('name');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->boolean('completed')
            ->allowEmptyString('completed');

        $validator
            ->date('due_date')
            ->allowEmptyDate('due_date');

        $validator
            ->dateTime('reminder', ['ymd'])
            ->allowEmptyDate('reminder');

        $validator
            ->integer('priority')
            ->allowEmptyString('priority');

        $validator
            ->numeric('health_impact')
            ->allowEmptyString('health_impact');

        $validator
            ->dateTime('completed_datetime')
            ->allowEmptyDateTime('completed_datetime');

        $validator
            ->integer('repair_id')
            ->allowEmptyString('repair_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy', 'The user you created this with no longer exists'));
        $rules->add($rules->existsIn(['modified_by_iid'], 'ModifiedBy', 'The user you modified this with no longer exists'));
        $rules->add($rules->existsIn(['assigned'], 'AssignedTo'));
        $rules->add($rules->existsIn(['assigned_by'], 'AssignedBy'));
        $rules->add($rules->existsIn(['store_id'], 'Stores'));
        $rules->add($rules->existsIn(['equipment_id'], 'Equipments'));

        return $rules;
    }

    /**
     * Notify when repair is completed
     *
     * @param Event $event The event
     * @param Repair $entity The repair
     * @param ArrayObject $options The options
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if (!$entity->priority) {
            $entity->priority = 0;
        }

        if (!$entity->status) {
            $entity->status = 'Pending Assignment';
        }
    }

    /**
     * @param Query $q
     * @param array $options
     * @return Query
     */
    public function findRepairs(Query $q, array $options)
    {
        $q->contain([
                'Stores' => [
                    'fields' => [
                        'Stores.id',
                        'Stores.name',
                        'Stores.file_id',
                    ],
                ],
                'AssignedTo' => [
                    'fields' => [
                        'AssignedTo.first_name',
                        'AssignedTo.last_name',
                        'AssignedTo.file_id',
                    ]
                ],
            ])
            ->leftJoinWith('Comments')
            ->leftJoinWith('Files')
            ->select(['file_count' => $q->func()->count('Files.id')])
            ->select(['comment_count' => $q->func()->count('Comments.id')])
            ->group(['Repairs.id'])
            ->enableAutoFields(true);

        if (count($options['assigned_to_id'])) {
            $q->matching('AssignedTo', function (Query $query) use ($options) {
                return $query->where(['AssignedTo.id IN' => $options['assigned_to_id']]);
            });
        }

        if (count($options['assigned_by_id'])) {
            $q->matching('AssignedBy', function (Query $query) use ($options) {
                return $query->where(['AssignedBy.id IN' => $options['assigned_by_id']]);
            });
        }

        if (count($options['created_by_id'])) {
            $q->matching('CreatedBy', function (Query $query) use ($options) {
                return $query->where(['CreatedBy.id IN' => $options['created_by_id']]);
            });
        }

        if (count($options['equipment_id'])) {
            $q->matching('Equipments', function (Query $query) use ($options) {
                return $query->where(['Equipments.id IN' => $options['equipment_id']]);
            });
        }

        if (count($options['status'])) {
            $q->where(['Repairs.status IN' => $options['status']]);
        }

        if ($options['search']) {
            $q->where(['Repairs.name LIKE' => '%'.$options['search'].'%']);
        }

        if (isset($options['priority']) && $options['priority'] !== -1) {
            $q->where(['Repairs.priority' => $options['priority']]);
        }

        return $q;
    }

    public function findDashboard(Query $q, array $options)
    {
        return $q->find('repairs')->where(['Repairs.completed =' => false]);
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        if (isset($data['completed'])) {
            $data['completed'] = filter_var($data['completed'], FILTER_VALIDATE_BOOLEAN);
        }
    }
}
