<?php
namespace App\Model\Table;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Equipment;
use App\Model\Entity\Location;
use App\Model\Entity\Maintenance;
use App\Model\Entity\Store;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\I18n\Date;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Exception;

/**
 * Maintenances Model
 *
 * @property EquipmentsTable|BelongsTo $Equipments
 * @property StoresTable|BelongsTo $Stores
 * @property ItemsTable|BelongsToMany Items
 * @property ItemsTable|BelongsToMany Parts
 * @property ItemsTable|BelongsToMany Consumables
 * @property ItemsTable|BelongsToMany Tools
 * @property MaintenanceSessionsMaintenancesTable|BelongsToMany MaintenanceSessionsMaintenances
 * @property ActivityLogsTable|HasMany $ActivityLogs
 * @method Maintenance get($primaryKey, $options = [])
 * @method Maintenance newEntity($data = null, array $options = [])
 * @method Maintenance[] newEntities(array $data, array $options = [])
 * @method Maintenance|bool save(EntityInterface $entity, $options = [])
 * @method Maintenance|bool saveOrFail(EntityInterface $entity, $options = [])
 * @method Maintenance patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Maintenance[] patchEntities($entities, array $data, array $options = [])
 * @method Maintenance findOrCreate($search, callable $callback = null, $options = [])
 * @mixin TimestampBehavior
 */
class MaintenancesTable extends Table
{
    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('maintenances');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('WhoDidIt', [
            'userModel' => 'Users',
            'contain' => false,
        ]);

        $this->addBehavior('Muffin/Trash.Trash');

        $this->belongsTo('Photos');

        $this->belongsTo('Equipments', [
            'foreignKey' => 'equipment_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Stores');

        $this->belongsToMany('Items');

        $this->belongsToMany('Parts', [
            'joinTable' => 'items_maintenances',
        ])
            ->setClassName('Items')
            ->setConditions(['Parts.item_type_id' => 1])
            ->setTargetForeignKey('item_id')
            ->setForeignKey('maintenance_id');

        $this->belongsToMany('Consumables', [
            'joinTable' => 'items_maintenances',
        ])
            ->setClassName('Items')
            ->setConditions(['Consumables.item_type_id' => 2])
            ->setTargetForeignKey('item_id')
            ->setForeignKey('maintenance_id');

        $this->belongsToMany('Tools', [
            'joinTable' => 'items_maintenances',
        ])
            ->setClassName('Items')
            ->setConditions(['Tools.item_type_id' => 3])
            ->setTargetForeignKey('item_id')
            ->setForeignKey('maintenance_id');

        $this->hasMany('Repairs', [
            'foreignKey' => 'maintenance_id',
            'joinType' => 'LEFT',
        ]);

        $this->hasMany('MaintenanceSessionsMaintenances');

        $this->hasMany('ActivityLogs')
            ->setConditions(['object_model' => 'Maintenances'])
            ->setForeignKey('foreign_key')
            ->setBindingKey('id');
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
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->notEmptyString('name', 'You must choose a name for the maintenance');

        $validator
            ->scalar('method')
            ->maxLength('method', 255)
            ->notEmptyString('method', 'You must choose a method for doing maintenance');

        $validator
            ->integer('expected_duration')
            ->notEmptyString('expected_duration', 'You must set an expected duration for maintenance');

        $validator
            ->integer('frequency_days')
            ->allowEmptyString(
                'frequency_days',
                'You must enter a frequency to do maintenance',
                function ($context) {
                    $test = isset($context['data']['frequency_cars']);

                    return $test;
                }
            );

        $validator
            ->integer('frequency_cars')
            ->allowEmptyString(
                'frequency_cars',
                'You must enter a frequency to do maintenance',
                function ($context) {
                    $test = isset($context['data']['frequency_days']);

                    return $test;
                }
            );

        $validator
            ->boolean('draft')
            ->allowEmptyString('draft');

        $validator
            ->integer('equipment_id')
            ->notEmptyString('equipment_id');

        $validator
            ->uuid('store_id')
            ->notEmptyString('store_id');

        $validator
            ->scalar('created_by')
            ->maxLength('created_by', 36)
            ->minLength('created_by', 36)
            ->allowEmptyString('created_by', 'create');

        $validator
            ->scalar('modified_by')
            ->maxLength('modified_by', 36)
            ->minLength('modified_by', 36)
            ->allowEmptyString('modified_by', 'create');

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
        $rules->add($rules->existsIn(['equipment_id'], 'Equipments'));
        $rules->add($rules->existsIn(['store_id'], 'Stores'));

        $rules->add(function (Maintenance $maintenance, $options) {
            return $maintenance->frequency_days !== null || $maintenance->frequency_car === null;
        },
            'ruleName',
            [
                'errorField' => 'Due Date/Due Cars',
                'message' => 'Due Date or Due Cars can not be null.',
        ]);

        return $rules;
    }

    /**
     * Update due dates for maintenance
     *
     * @param  Maintenance $maintenance The maintenancee
     */
    public function updateMaintenanceDueDate(Maintenance $maintenance)
    {
        $current_car_count = $this->Stores->get($maintenance->store_id)->current_car_count;
        $maintenance->last_cars_completed = $current_car_count;
        $maintenance->last_completed_date = new FrozenTime();

        if (!$this->save($maintenance)) {
            throw new ValidationException($maintenance);
        }
    }

    /**
     * Complete a maintenance
     *
     * @param int $maintenance_id The maintenance to complete
     * @return void
     * @throws Exception
     */
    public function complete(int $maintenance_id)
    {
        $maintenance = $this->get($maintenance_id, [
            'contain' => [
                'Parts',
            ],
        ]);

        foreach ($maintenance->parts as $item) {
            $this->Parts->Inventories->use($item, $item->_joinData->quantity, $maintenance->store_id, 5);
        }

        $this->updateMaintenanceDueDate($maintenance);
    }

    /**
     * Returns store maintenance grouped by location and grouped equipment and sorted by equipment order
     *
     * @param string $store_id The store id
     * @param string $user_id The user id
     * @param bool $due Whether to get due or upcoming
     * @return Equipment[]
     */
    public function dueEquipmentMaintenance(string $store_id, string $user_id, bool $due): array
    {
        $query = $this
            ->Equipments
            ->Locations
            ->find()
            ->where(['Locations.store_id' => $store_id])
            ->innerJoinWith('Equipments.Maintenances', function (Query $query) use ($store_id, $due) {
                return $query->find('due', compact('store_id', 'due'));
            })
            ->contain([
                'Equipments.Maintenances' => [
                    'Items.Inventories' => function (Query $q) use ($store_id) {
                        return $q->where(['Inventories.store_id' => $store_id]);
                    },
                    'Equipments.Locations'
                ]
            ])
            ->group('Locations.id');

        $locations = $query->all()->map(function (Location $location) {
            $location->maintenances = [];
            foreach ($location->equipments as $equipment) {
                foreach ($equipment->maintenances as $maintenance) {
                    $location->maintenances[] = $maintenance;
                }
            }
            unset($location->equipments);
            return $location;
        });

        return $locations->toArray();
    }

    /**
     * Find due or upcoming maintenance with store settings offset
     *
     * @param Query $q The query
     * @param array $options The options
     * @return Query
     */
    public function findDue(Query $q, array $options)
    {
        /** @var Store $store */
        $store = $this->Equipments->Stores->findById($options['store_id'])->first();

        $where = ['OR' => []];
        if ($options['due']) {
            if ($store->allow_car_counts) {
                $where['OR']['Maintenances.last_cars_completed + frequency_cars <='] = $store->current_car_count + $store->maintenance_due_cars_offset;
                $where['OR']['Maintenances.last_cars_completed'] = 0;
            }
            $where['OR']['DATE_ADD(Maintenances.last_completed_date, INTERVAL frequency_days DAY)  <='] = (new FrozenDate())->addDay($store->maintenance_due_days_offset);
            $where['OR']['Maintenances.last_completed_date IS'] = null;
        } else {
            if ($store->allow_car_counts) {
                $where['OR']['Maintenances.last_cars_completed + frequency_cars >'] = $store->current_car_count + $store->maintenance_due_cars_offset;
            }
            $where['OR']['DATE_ADD(Maintenances.last_completed_date, INTERVAL frequency_days DAY)  >'] = (new FrozenDate())->addDay($store->maintenance_due_days_offset);
        }
        $q->where($where);

        return $q;
    }

    /**
     * @param Event       $event
     * @param Maintenance $entity
     * @param ArrayObject $options
     */
    public function beforeRules(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->frequency_days) {
            $entity->method = 'Time';
            if ($entity->isNew()) {
                $entity->due_date = (new Date())->modify('+' . $entity->frequency_days . ' days');
            }
        } else {
            $entity->method = 'Car Count';
            if ($entity->isNew()) {
                $current_carcount = $this->Equipments->Stores->CarCounts->find()->where(['store_id =' => $entity->store_id]);
                $currentCarCount = $current_carcount->sumOf('carcount');
                $entity->due_cars = $currentCarCount;
            }
        }
    }
}
