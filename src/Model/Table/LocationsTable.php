<?php
namespace App\Model\Table;

use ADmad\Sequence\Model\Behavior\SequenceBehavior;
use App\Model\Entity\Location;
use ArrayObject;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Exception;

/**
 * Locations Model
 *
 * @property StoresTable|BelongsTo $Stores
 * @property EquipmentsTable|HasMany $Equipments
 * @method Location get($primaryKey, $options = [])
 * @method Location newEntity($data = null, array $options = [])
 * @method Location[] newEntities(array $data, array $options = [])
 * @method Location|bool save(EntityInterface $entity, $options = [])
 * @method Location|bool saveOrFail(EntityInterface $entity, $options = [])
 * @method Location patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Location[] patchEntities($entities, array $data, array $options = [])
 * @method Location findOrCreate($search, callable $callback = null, $options = [])
 * @mixin TimestampBehavior
 * @mixin SequenceBehavior
 */
class LocationsTable extends Table
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

        $this->setTable('locations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior(
            'WhoDidIt',
            [
            'userModel' => 'Users',
            'contain' => false,
            ]
        );

        $this->belongsTo('Stores');

        $this->hasMany('Equipments');


        $this->addBehavior('ADmad/Sequence.Sequence', [
            'order' => 'position', // Field to use to store integer sequence. Default "position".
            'scope' => ['store_id'], // Array of field names to use for grouping records. Default [].
            'start' => 1, // Initial value for sequence. Default 1.
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
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name', false);

        $validator
            ->scalar('description')
            ->maxLength('description', 2000)
            ->allowEmptyString('description', false);

        $validator
            ->boolean('default_location')
            ->allowEmptyString('default_location', 'create');

        $validator
            ->uuid('store_id')
            ->allowEmptyDateTime('store_id', false);

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
        $rules->add($rules->isUnique(['store_id', 'name'], 'This location name already exists for this store'));
        $rules->add($rules->existsIn(['store_id'], 'Stores'));
        $rules->add($rules->existsIn(['created_by'], 'CreatedBy', 'The user you created this with no longer exists'));
        $rules->add($rules->existsIn(['modified_by'], 'ModifiedBy', 'The user you modified this with no longer exists'));

        return $rules;
    }

    public function findStoreLocations(Query $q, array $options)
    {
        $store_id = $options['store_id'];
        return $q
            ->where(['store_id' => $store_id])
            ->contain(['Equipments']);
    }

    public function beforeSave(EventInterface $event, Location $location, ArrayObject $options) {
        if ($location->default_location) {
            /** @var Location $oldDefault */
            $oldDefault = $this->find()->where([
                'store_id' => $location->store_id,
                'default_location' => true
            ])->first();
            if ($oldDefault && $oldDefault->id !== $location->id) {
                $oldDefault->default_location = false;
                $this->save($oldDefault);
            }
        }
    }

    public function beforeDelete(EventInterface $event, Location $location, ArrayObject $options) {
        if ($location->default_location) {
            throw new Exception('You can not delete your default location');
        }
    }

    public function afterDelete(EventInterface $event, Location $location, ArrayObject $options) {
        $equipments = $this->Equipments->find()->where(['location_id' => $location->id])->toArray();
        $defaultLocation = $this->find()->where(['default_location' => true, 'store_id' => $location->store_id])->firstOrFail();
        $this->Equipments->link($defaultLocation, $equipments);
    }
}
