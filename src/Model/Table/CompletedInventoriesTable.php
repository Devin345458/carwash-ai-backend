<?php
namespace App\Model\Table;

use App\Model\Entity\CompletedInventory;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Completedinventorys Model
 *
 * @property UsersTable|BelongsTo $Users
 * @property StoresTable|BelongsTo $Stores
 * @property ActivityLogsTable|HasMany $ActivityLogs
 * @method CompletedInventory get($primaryKey, $options = [])
 * @method CompletedInventory newEntity($data = null, array $options = [])
 * @method CompletedInventory[] newEntities(array $data, array $options = [])
 * @method CompletedInventory|bool save(EntityInterface $entity, $options = [])
 * @method CompletedInventory saveOrFail(EntityInterface $entity, $options = [])
 * @method CompletedInventory patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method CompletedInventory[] patchEntities($entities, array $data, array $options = [])
 * @method CompletedInventory findOrCreate($search, callable $callback = null, $options = [])
 * @mixin TimestampBehavior
 */
class CompletedInventoriesTable extends Table
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

        $this->setTable('completed_inventories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('WhoDidIt');

        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id',
        ]);

        $this->hasMany('ActivityLogs')
            ->setConditions(['object_model' => 'Completedinventorys'])
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
            ->integer('time_to_complete')
            ->allowEmptyString('time_to_complete', false);

        $validator
            ->dateTime('completed_date')
            ->allowEmptyDateTime('completed_date', false);

        $validator
            ->integer('item_count')
            ->allowEmptyString('item_count', false);

        $validator
            ->integer('item_skip_count')
            ->allowEmptyString('item_skip_count', false);

        $validator
            ->dateTime('created')
            ->allowEmptyDateTime('created', 'create');

        $validator
            ->dateTime('modified')
            ->allowEmptyDateTime('modified', 'create');

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
        $rules->add($rules->existsIn(['store_id'], 'Stores', 'The store you tried to save this for does not exist'));
        $rules->add($rules->existsIn(['completed_by'], 'CreatedBy', 'The user you created this with no longer exists'));
        $rules->add($rules->existsIn(['modified_by'], 'ModifiedBy', 'The user you modified this with no longer exists'));

        return $rules;
    }
}
