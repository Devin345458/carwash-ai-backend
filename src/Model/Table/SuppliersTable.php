<?php
namespace App\Model\Table;

use App\Model\Entity\Supplier;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Suppliers Model
 *
 * @method Supplier get($primaryKey, $options = [])
 * @method Supplier newEntity($data = null, array $options = [])
 * @method Supplier[] newEntities(array $data, array $options = [])
 * @method Supplier|bool save(EntityInterface $entity, $options = [])
 * @method Supplier|bool saveOrFail(EntityInterface $entity, $options = [])
 * @method Supplier patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Supplier[] patchEntities($entities, array $data, array $options = [])
 * @method Supplier findOrCreate($search, callable $callback = null, $options = [])
 */
class SuppliersTable extends Table
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

        $this->setTable('suppliers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('WhoDidIt');

        $this->belongsTo('Stores');
        $this->belongsTo('Files');

        $this->belongsTo('Companies', [
            'through' => 'Stores',
        ]);

        $this->hasMany('Inventories', [
            'foreignKey' => 'supplier_id',
        ]);

        $this->hasMany('Equipments', [
            'foreignKey' => 'manufacturer_id',
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name', false);

        $validator
            ->scalar('website')
            ->maxLength('website', 255)
            ->allowEmptyString('website');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 255)
            ->allowEmptyString('phone');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('contact_name')
            ->maxLength('contact_name', 255)
            ->allowEmptyString('contact_name');

        $validator
            ->allowEmptyString('store_id', false);

        $validator
            ->dateTime('created')
            ->allowEmptyDateTime('created', 'create');

        $validator
            ->dateTime('modified')
            ->allowEmptyDateTime('modified', 'create');

        $validator
            ->scalar('created_by_id')
            ->maxLength('created_by_id', 36)
            ->minLength('created_by_id', 36)
            ->allowEmptyString('created_by_id', 'create');

        $validator
            ->scalar('modified_by_id')
            ->maxLength('modified_by_id', 36)
            ->minLength('modified_by_id', 36)
            ->allowEmptyString('modified_by_id', 'create');

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
        $rules->add(function ($entity, $options) {
            if ($entity->email === null) return true;
            $unique = new IsUnique(['store_id', 'email']);
            return $unique($entity, $options);
        });
        $rules->add($rules->existsIn(['store_id'], 'Stores', 'This store does not exist to add a supplier to'));
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy', 'The user you created this with no longer exists'));
        $rules->add($rules->existsIn(['modified_by_d'], 'ModifiedBy', 'The user you modified this with no longer exists'));

        return $rules;
    }

    public function getCompanySuppliers($company_id)
    {
        return $this->find('list', ['limit' => 200])->where(['company_id in' => [$company_id, 1]])->toArray();
    }
}
