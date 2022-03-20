<?php
namespace App\Model\Table;

use App\Model\Entity\Store;
use App\Model\Entity\User;
use ArrayObject;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\I18n\Date;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Association\HasOne;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Validation\Validator;
use ChargeBee_Subscription;

/**
 * Stores Model
 *
 * @property CompaniesTable|BelongsTo $Companies
 * @property RepairsTable|HasMany $Repairs
 * @property LocationsTable|HasMany $Locations
 * @property UsersTable|BelongsToMany $Users
 * @property InventoriesTable|HasMany $Inventories
 * @property CarCountsTable|HasMany $CarCounts
 * @method Store get($primaryKey, $options = [])
 * @method Store newEntity($data = null, array $options = [])
 * @method Store[] newEntities(array $data, array $options = [])
 * @method Store|bool save(EntityInterface $entity, $options = [])
 * @method Store|bool saveOrFail(EntityInterface $entity, $options = [])
 * @method Store patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Store[] patchEntities($entities, array $data, array $options = [])
 * @method Store findOrCreate($search, callable $callback = null, $options = [])
 * @method Query findById(int $id)
 * @mixin TimestampBehavior
 */
class StoresTable extends Table
{
    use LocatorAwareTrait;

    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('stores');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Companies');

        $this->belongsTo('StoreTypes');

        $this->hasMany('Consumables', [
            'foreignKey' => 'store_id',
        ]);

        $this->hasMany('Equipments', [
            'foreignKey' => 'store_id',
        ])->setDependent(true);

        $this->hasMany('Repairs', [
            'foreignKey' => 'store_id',
        ])->setDependent(true);

        $this->hasMany('CarCounts', [
            'foreignKey' => 'store_id',
        ])->setDependent(true);

        $this->hasMany('Locations', [
            'foreignKey' => 'store_id',
        ])->setDependent(true);

        $this->hasMany('OrderItems', [
            'foreignKey' => 'store_id',
        ])->setDependent(true);

        $this->belongsToMany('Users')
            ->setSaveStrategy('append');

        $this->belongsTo('Files');

        $this->hasMany('Suppliers');

        $this->hasMany('Inventories')->setDependent(true);
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
            ->uuid('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('subscription_id')
            ->allowEmptyString(
                'subscription_id',
                null,
                function ($context) {
                    return $context['data']['store_type_id'] === 1 && $context['data']['id'];
                }
            );

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
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['store_type_id'], 'StoreTypes'));

        return $rules;
    }

    /**
     * Filter query for non-canceled stores
     *
     * @param Event $event The event
     * @param Query $query The Query
     * @param ArrayObject $options The options
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options)
    {
        $options = $query->getOptions();
        $alias_cancel = $query->getRepository()->getRegistryAlias() . '.canceled';
        $alias_cancel_date =  $query->getRepository()->getRegistryAlias() . '.cancel_date >';
        if (!isset($options['all'])) {
            $date = new Date();
            $query->where(
                [
                'OR' => [
                    $alias_cancel => false,
                    $alias_cancel_date => $date->modify('-7 days'),
                ],
                ]
            );
        }
    }

    /**
     * If the entity is new add a store settings to it
     *
     * @param Event $event The event
     * @param Store|EntityInterface $entity The store
     * @param ArrayObject $options The options
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        /** @var Store $entity */
        if ($entity->isNew() && $entity->store_type_id === 1) {
            $company = $this->Companies->get($entity->company_id);
            $this->patchEntity($entity, [
                'allow_car_count' => $company->allow_car_count,
                'maintenance_due_days_offset' => 0,
                'maintenance_due_cars_offset' => 0,
                'upcoming_days_offset' => 7,
                'upcoming_cars_offset' => 4000,
                'require_scan' => false,
            ]);
            // Add Default Location
            $entity->locations = [
                $this->Locations->newEntity([
                    'name' => 'Tunnel',
                    'description' => 'Default Location',
                    'default_location' => true,
                ]),
            ];
        }
    }

    /**
     * Add the subscription for the store
     *
     * @param Event $event The event
     * @param Store|EntityInterface $entity The store
     * @param ArrayObject $options The options
     * @return void
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        /** @var Store $entity */
        if ($entity->isNew() && $entity->store_type_id === 1) {
            $company = $this->Companies->get($entity->company_id);
            $result = ChargeBee_Subscription::createForCustomer($company->chargebee_customer_id, [
                'planId' => $entity->plan_id,
            ]);

            $subscription = $result->subscription();
            $entity->subscription_id = $subscription->id;
            $this->save($entity);
        }
    }

    /**
     * Find stores for a user
     *
     * @param Query $query The query
     * @param array $user The user to find store for
     * @return Query The query filtered for users stores
     */
    public function findUsersStores(Query $query, array $user)
    {
        return $query->matching('Users', function ($q) use ($user) {
            return $q->where(['Users.id' => $user['id']]);
        });
    }

    /**
     * Find users for specified stores
     *
     * @param Query $query The query
     * @param array $stores The stores to find users for
     * @return Query The query
     */
    public function findStoresUsers(Query $query, array $stores)
    {
        return $query->where(['Stores.id in ' => $stores])->contain('Users', function (Query $q) {
            return $q->select(['id' => 'Users.id', 'name' => $q->func()->concat(['First_name' => 'identifier', ' ', 'Last_name' => 'identifier']), 'role' => 'role']);
        });
    }

    /**
     * Returns the query containing store settings
     *
     * @param Query $query The query
     * @param array $options The options
     * @return Query The query
     */
    public function findSettings(Query $query, array $options)
    {
        return $query->contain(['Locations' => ['Equipments'], 'Users' => ['Stores'], 'Companies' => ['CompanySettings'], 'Suppliers.Files', 'Files'])
            ->where(['Stores.id =' => $options['active_store_id']]);
    }

    /**
     * @param User $user The user
     * @return int[] An Array of the users store ids
     */
    public function usersStoresID(User $user): array
    {
        $stores = $this->find()->matching(
            'Users',
            function (Query $q) use ($user) {
                return $q->where(['Users.id' => $user['id']]);
            }
        )->toArray();

        $storesID = [];
        foreach ($stores as $store) {
            array_push($storesID, $store['id']);
        }

        return $storesID;
    }
}
