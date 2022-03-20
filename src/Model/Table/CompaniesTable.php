<?php

namespace App\Model\Table;

use App\Model\Behavior\WhoDidItBehavior;
use App\Model\Entity\Company;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Association\HasOne;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Validation\Validator;
use ChargeBee_Customer;

/**
 * Companies Model
 *
 * @property StoresTable|HasMany $Stores
 * @property UsersTable|HasMany $Employees
 * @property CategoriesTable|HasMany $Categories
 * @method   Company get($primaryKey, $options = [])
 * @method   Company newEntity($data = null, array $options = [])
 * @method   Company[] newEntities(array $data, array $options = [])
 * @method   Company|bool save(EntityInterface $entity, $options = [])
 * @method   Company|bool saveOrFail(EntityInterface $entity, $options = [])
 * @method   Company patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method   Company[] patchEntities($entities, array $data, array $options = [])
 * @method   Company findOrCreate($search, callable $callback = null, $options = [])
 * @mixin TimestampBehavior
 * @mixin WhoDidItBehavior
 */
class CompaniesTable extends Table
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

        $this->setTable('companies');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany(
            'Stores',
            [
                'foreignKey' => 'company_id',
                'conditions' => ['Stores.store_type_id' => 1],
            ]
        )->setDependent(true);

        $this->hasMany(
            'Warehouses',
            [
                'foreignKey' => 'company_id',
                'conditions' => ['Warehouses.store_type_id' => 2],
                'className' => 'Stores',
            ]
        )->setDependent(true);

        $this->hasMany(
            'Employees',
            [
                'className' => 'Users',
                'through' => 'Stores',
            ]
        )->setDependent(true);

        $this->hasMany(
            'Suppliers',
            [
                'through' => 'Stores',
            ]
        );

        $this->hasMany(
            'Categories',
            [
                'foreignKey' => 'company_id',
            ]
        )->setDependent(true);

        $this->hasMany(
            'Equipments',
            [
                'through' => 'Stores',
            ]
        )->setDependent(true);
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
            ->notEmptyString('name');

        $validator
            ->integer('zipcode')
            ->allowEmptyString('zipcode');

        $validator
            ->scalar('country')
            ->maxLength('country', 2)
            ->allowEmptyString('country');

        $validator
            ->scalar('state')
            ->maxLength('state', 2)
            ->allowEmptyString('state');

        $validator
            ->scalar('chargebee_customer_id')
            ->maxLength('chargebee_customer_id', 255)
            ->allowEmptyString('chargebee_customer_id', false);

        $validator
            ->dateTime('created')
            ->allowEmptyDateTime('created', 'create');

        $validator
            ->dateTime('modified')
            ->allowEmptyDateTime('modified', 'create');

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
        $rules->add($rules->isUnique(['chargebee_customer_id'], 'This chargebee_customer_id  you tried to save is already being used'));

        return $rules;
    }

    /**
     *
     *
     * @param Event $event The event
     * @param Company|EntityInterface $entity The company
     * @param ArrayObject $options The options
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew() && Router::getRequest()) {
            $token = Router::getRequest()->getData('token');
            $data = [
                'firstName' => $entity->billing_first_name,
                'lastName' => $entity->billing_last_name,
                'email' => $entity->email,
                'billingAddress' => [
                    'firstName' => $entity->billing_first_name,
                    'lastName' => $entity->billing_last_name,
                    'line1' => $entity->address,
                    'city' => $entity->city,
                    'state' => $entity->state,
                    'zip' => $entity->zip,
                    'country' => $entity->country,
                ],
            ];
            if ($token) {
                $data['card'] = [
                    'gateway' => 'stripe',
                    'tmpToken' => $token,
                ];
            }
            $result = ChargeBee_Customer::create($data);
            $customer = $result->customer();
            $entity->chargebee_customer_id = $customer->id;
            $this->save($entity);
        }
    }
}
