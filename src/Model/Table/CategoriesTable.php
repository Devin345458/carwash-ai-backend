<?php
namespace App\Model\Table;

use App\Model\Entity\Category;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Categories Model
 *
 * @method Category get($primaryKey, $options = [])
 * @method Category newEntity($data = null, array $options = [])
 * @method Category[] newEntities(array $data, array $options = [])
 * @method Category|bool save(EntityInterface $entity, $options = [])
 * @method Category|bool saveOrFail(EntityInterface $entity, $options = [])
 * @method Category patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Category[] patchEntities($entities, array $data, array $options = [])
 * @method Category findOrCreate($search, callable $callback = null, $options = [])
 */
class CategoriesTable extends Table
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

        $this->setTable('categories');
        $this->setDisplayField('category');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior(
            'WhoDidIt',
            [
            'userModel' => 'Users',
            ]
        );

        $this->belongsTo('Companies');

        $this->belongsToMany('Equipments');
        $this->belongsToMany('Tools');
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
            ->scalar('category')
            ->maxLength('category', 255)
            ->allowEmptyString('category', false);

        $validator
            ->scalar('description')
            ->maxLength('description', 10000)
            ->allowEmptyString('description', true);

        $validator
            ->integer('company_id')
            ->allowEmptyString('company_id', false);

        $validator
            ->scalar('model')
            ->maxLength('model', 255)
            ->allowEmptyString('model', false);

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
     * @param  RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['company_id'], 'Companies', 'The company you tried to save this for does not exist'));
        $rules->add($rules->existsIn(['created_by'], 'CreatedBy', 'The user you created this with no longer exists'));
        $rules->add($rules->existsIn(['modified_by'], 'ModifiedBy', 'The user you modified this with no longer exists'));
        $rules->add($rules->isUnique(['category', 'company_id', 'model'], 'This category already exists for your company'));

        return $rules;
    }
}
