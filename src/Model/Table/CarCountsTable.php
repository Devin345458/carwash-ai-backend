<?php
namespace App\Model\Table;

use App\Model\Entity\CarCount;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Carcounts Model
 *
 * @property StoresTable|BelongsTo $Stores
 * @property UsersTable|BelongsTo $Users
 * @method CarCount get($primaryKey, $options = [])
 * @method CarCount newEntity($data = null, array $options = [])
 * @method CarCount[] newEntities(array $data, array $options = [])
 * @method CarCount|bool save(EntityInterface $entity, $options = [])
 * @method CarCount|bool saveOrFail(EntityInterface $entity, $options = [])
 * @method CarCount patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method CarCount[] patchEntities($entities, array $data, array $options = [])
 * @method CarCount findOrCreate($search, callable $callback = null, $options = [])
 * @mixin TimestampBehavior
 */
class CarCountsTable extends Table
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

        $this->setTable('car_counts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior(
            'WhoDidIt',
            [
            'userModel' => 'Users',
            'contain' => false,
            ]
        );

        $this->belongsTo(
            'Stores',
            [
            'foreignKey' => 'store_id',
            'joinType' => 'INNER',
            ]
        );
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
            ->integer('carcount')
            ->requirePresence('carcount')
            ->allowEmptyString('carcount', false);

        $validator
            ->uuid('store_id')
            ->allowEmptyString('store_id', false);

        $validator
            ->date('date_of_cars')
            ->requirePresence('date_of_cars')
            ->allowEmptyDate('date_of_cars', false);

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
        $rules->add($rules->existsIn(['created_by'], 'CreatedBy', 'The user you created this with no longer exists'));
        $rules->add($rules->existsIn(['modified_by'], 'ModifiedBy', 'The user you modified this with no longer exists'));

        return $rules;
    }

    /**
     * Update store count
     *
     * @param EventInterface $event The event
     * @param CarCount $car_count The car count
     * @param ArrayObject $options The options
     */
    public function afterSave(EventInterface $event, CarCount $car_count, ArrayObject $options) {
        $store = $this->Stores->get($car_count->store_id);
        $store->current_car_count += $car_count->carcount;
        $this->Stores->save($store);
    }
}
