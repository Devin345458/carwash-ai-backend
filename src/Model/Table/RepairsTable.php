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
 * @property CompletedMaintenancesTable|BelongsTo $CompletedMaintenances
 * @property EquipmentsTable|BelongsTo $Equipments
 * @property SubtasksTable|HasMany $Subtasks
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
    public array $repair_association = [
        'Stores',
        'Comments' => ['CreatedBy.Files'],
        'CreatedBy',
        'AssignedBy.Files',
        'CompletedMaintenances.Maintenances',
        'Equipments',
        'AssignedTo.Files',
        'Subtasks.AssignedTo',
        'RepairReminders',
        'Items' => ['Inventories', 'Files'],
        'Files',
        'ActivityLogs.Users',
    ];

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

        $this->hasMany('Subtasks');

        $this->belongsTo(
            'AssignedTo',
            [
                'foreignKey' => 'assigned_to_id',
                'joinType' => 'LEFT',
                'className' => 'Users',
            ]
        );

        $this->belongsTo(
            'AssignedBy',
            [
                'foreignKey' => 'assigned_by_id',
                'joinType' => 'LEFT',
                'className' => 'Users',
            ]
        );

        $this->belongsTo('Stores');

        $this->belongsTo('Maintenances');

        $this->belongsTo(
            'Equipments',
            [
                'foreignKey' => 'equipment_id',
                'joinType' => 'LEFT',
            ]
        );

        $this->hasMany(
            'Subtasks',
            [
                'foreignKey' => 'repair_id',
            ]
        );

        $this->belongsTo(
            'CompletedMaintenances',
            [
                'foreignKey' => 'maintenance_id',
                'targetForeignKey' => 'id',
                'joinTable' => 'LEFT',
            ]
        );

        $this->belongsToMany('Items');

        $this->hasOne(
            'associated_repair',
            [
                'foreignKey' => 'id',
                'targetForeignKey' => 'repair_id',
                'className' => 'Repairs',
                'dependent' => false,
                'cascadeCallbacks' => false,
            ]
        );

        $this->belongsToMany(
            'parent_repair',
            [
                'foreignKey' => 'id',
                'targetForeignKey' => 'repair_id',
                'className' => 'Repairs',
                'dependent' => false,
                'cascadeCallbacks' => false,
            ]
        );

        $this->hasMany('Comments')
            ->setConditions(['commentable_type' => get_class($this)])
            ->setForeignKey('commentable_id')
            ->setBindingKey('id')
            ->setSaveStrategy('append');

        $this->hasMany('ActivityLogs')
            ->setConditions(['object_model' => 'Repairs'])
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
        $rules->add($rules->existsIn(['created_by'], 'CreatedBy', 'The user you created this with no longer exists'));
        $rules->add($rules->existsIn(['modified_by'], 'ModifiedBy', 'The user you modified this with no longer exists'));
        $rules->add($rules->existsIn(['assigned'], 'AssignedTo'));
        $rules->add($rules->existsIn(['assigned_by'], 'AssignedBy'));
        $rules->add($rules->existsIn(['store_id'], 'Stores'));
        $rules->add($rules->existsIn(['equipment_id'], 'Equipments'));
        $rules->add($rules->existsIn(['maintenance_id'], 'CompletedMaintenances'));
        $rules->add($rules->existsIn(['repair_id'], 'associated_repair'));

        return $rules;
    }

    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if (isset($entity->files) && count($entity->files) > 0) {
            $found = (new Collection($entity->files))->firstMatch(
                [
                    '_joinData.cover' => true,
                ]
            );
            if ($found) {
                return;
            }
            if (!isset($entity->files[0]->_joinData)) {
                $entity->files[0]->_joinData = new Entity(['cover' => true]);
            }
            $entity->files[0]->_joinData->set('cover', true);
            $entity->setDirty('files', true);
        }
    }

    /**
     * Notify when repair is completed
     *
     * @param Event $event The event
     * @param Repair $entity The repair
     * @param ArrayObject $options The options
     * @throws PusherException
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {

        if ($entity->isDirty('status') && $entity->completed) {
            NotificationManager::instance()->notify(
                [
                    'recipientLists' => ['Store'],
                    'data' => [
                        'store_id' => $entity->store_id,
                        'title' => 'Repair Completed',
                        'description' => (Router::getRequest() ? Router::getRequest()->getAttribute('identity')->full_name : 'Someone') . '- completed repair ' . $entity->name,
                        'image_url' => $entity->files ? $entity->files[0]->responsive_images['thumbnail'] : null,
                        'to' => '/repair/' . $entity->id,
                    ],
                ]
            );
        }
    }

    /**
     * @param Query $q
     * @param array $options
     * @return Query
     */
    public function findRepairs(Query $q, array $options)
    {
        return $q->contain([
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
    }

    /**
     * @param Query $q The query
     * @param array $options Finder options
     * @return Query
     */
    public function findRepair(Query $q, array $options)
    {
        $repair = $this->get($options['id']);
        $repair = $q->contain(
            [
                'Stores',
                'Comments' => [
                    'fields' => [
                        'Comments.content',
                        'Comments.commentable_id',
                        'Comments.commentable_type',
                    ],
                    'CreatedBy' => [
                        'fields' => [
                            'CreatedBy.id',
                            'CreatedBy.first_name',
                            'CreatedBy.last_name',
                        ],
                        'Files' => [
                            'fields' => [
                                'Files.id',
                                'Files.name',
                            ],
                        ],
                    ],
                ],
                'CreatedBy' => [
                    'fields' => [
                        'CreatedBy.first_name',
                        'CreatedBy.last_name',
                    ],
                ],
                'AssignedBy' => [
                    'fields' => [
                        'AssignedBy.first_name',
                        'AssignedBy.last_name',
                    ],
                    'Files' => [
                        'fields' => [
                            'Files.id',
                            'Files.name',
                        ],
                    ],
                ],
                'CompletedMaintenances' => [
                    'fields' => [
                        'CompletedMaintenances.completed_date',
                        'CompletedMaintenances.maintenance_id',
                    ],
                    'Maintenances' => [
                        'fields' => [
                            'Maintenances.name',
                        ],
                    ],
                ],
                'Equipments' => [
                    'fields' => [
                        'Equipments.name',
                        'Equipments.file_id',
                    ],
                    'Files' => [
                        'fields' => [
                            'Files.id',
                            'Files.name',
                        ],
                    ],
                ],
                'AssignedTo' => [
                    'fields' => [
                        'AssignedTo.first_name',
                        'AssignedTo.last_name',
                        'AssignedTo.file_id',
                    ],
                    'Files' => [
                        'fields' => [
                            'Files.id',
                            'Files.name',
                        ],
                    ],
                ],
                'Subtasks' => [
                    'fields' => [
                        'Subtasks.assigned_to_id',
                        'Subtasks.content',
                        'Subtasks.repair_id',
                    ],
                    'AssignedTo' => [
                        'fields' => [
                            'AssignedTo.first_name',
                            'AssignedTo.last_name',
                            'AssignedTo.file_id',
                        ],
                    ],
                ],
                'MyReminders' => [
                    'fields' => [
                        'MyReminders.reminder',
                        'MyReminders.repair_id',
                        'MyReminders.user_id',
                    ],
                ],
                'Items' => function (Query $q) use ($repair) {
                    return $q->select($this->Items)
                        ->contain([
                            'Inventories' => function (Query $q) use ($repair) {
                                return $q->select($this->Items->Inventories)
                                    ->where(['Inventories.store_id' => $repair->store_id]);
                            },
                            'Files' => function (Query $q) {
                                return $q->select($this->Items->Files);
                            },
                        ]);
                },
                'Files' => function (Query $q) {
                    return $q->select($this->Files);
                },
                'ActivityLogs' => function (Query $q) {
                    return $q->select($this->ActivityLogs)->order('ActivityLogs.created_at')->contain(['Users'])->select($this->ActivityLogs->Users);
                },
            ]
        )
        ->enableAutoFields(true)
        ->where(['Repairs.id' => $options['id']]);

        return $repair;
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
