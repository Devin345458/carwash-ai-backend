<?php
namespace App\Model\Table;

use App\Model\Entity\ItemType;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ItemTypes Model
 *
 * @property CompaniesTable|BelongsTo $Companies
 * @property ItemsTable|HasMany $Items
 * @method ItemType get($primaryKey, $options = [])
 * @method ItemType newEntity($data = null, array $options = [])
 * @method ItemType[] newEntities(array $data, array $options = [])
 * @method ItemType|bool save(EntityInterface $entity, $options = [])
 * @method ItemType saveOrFail(EntityInterface $entity, $options = [])
 * @method ItemType patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method ItemType[] patchEntities($entities, array $data, array $options = [])
 * @method ItemType findOrCreate($search, callable $callback = null, $options = [])
 * @mixin TimestampBehavior
 */
class ItemTypesTable extends Table
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

        $this->setTable('item_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
        ]);
        $this->hasMany('Items', [
            'foreignKey' => 'item_type_id',
        ]);

        $this->addBehavior('Timestamp');

        $this->addBehavior('WhoDidIt');
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
            ->allowEmptyString('name', false);

        $validator
            ->uuid('created_by')
            ->allowEmptyString('created_by');

        $validator
            ->uuid('modified_by')
            ->allowEmptyString('modified_by');

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
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->isUnique(['name', 'company_id'], 'You have already created this item type'));

        return $rules;
    }
}
