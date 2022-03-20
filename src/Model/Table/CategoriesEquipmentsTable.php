<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Categories Model
 */
class CategoriesEquipmentsTable extends Table
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

        $this->setTable('categories_equipments');
        $this->setDisplayField('id');
        $this->setPrimaryKey(['category_id', 'equipment_id']);

        $this->addBehavior('Timestamp');
        $this->addBehavior('WhoDidIt');

        $this->belongsTo('Categories');
        $this->belongsTo('Equipments');
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
            ->integer('category_id')
            ->allowEmptyString('category_id', 'create');

        $validator
            ->integer('equipment_id')
            ->allowEmptyString('id', 'create');

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
        $rules->add($rules->existsIn(['equipment_id'], 'Equipments', 'The equipment you tried to save this for does not exist'));
        $rules->add($rules->existsIn(['category_id'], 'Categories', 'The category you tried to save this for does not exist'));
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy', 'The user you created this with no longer exists'));
        $rules->add($rules->existsIn(['modified_by_id'], 'ModifiedBy', 'The user you modified this with no longer exists'));

        return $rules;
    }
}
