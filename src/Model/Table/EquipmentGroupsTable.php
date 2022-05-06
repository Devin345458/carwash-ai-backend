<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Classes\ActivityLoggableInterface;
use App\Model\Entity\EquipmentGroup;
use App\Model\Entity\User;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Polymorphic\Model\Behavior\MorphBehavior;

/**
 * EquipmentGroups Model
 *
 * @property EquipmentsTable Equipments
 * @method EquipmentGroup newEmptyEntity()
 * @method EquipmentGroup newEntity(array $data, array $options = [])
 * @method EquipmentGroup[] newEntities(array $data, array $options = [])
 * @method EquipmentGroup get($primaryKey, $options = [])
 * @method EquipmentGroup findOrCreate($search, ?callable $callback = null, $options = [])
 * @method EquipmentGroup patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method EquipmentGroup[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method EquipmentGroup|false save(EntityInterface $entity, $options = [])
 * @method EquipmentGroup saveOrFail(EntityInterface $entity, $options = [])
 * @method EquipmentGroup[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method EquipmentGroup[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method EquipmentGroup[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method EquipmentGroup[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin MorphBehavior
 */
class EquipmentGroupsTable extends Table
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

        $this->setTable('equipment_groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Polymorphic.Morph');

        $this->belongsToMany('Equipments');
        $this->morphsMany('Maintenances', 'maintainable');
        $this->belongsTo('Stores');
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('updated_at')
            ->allowEmptyDateTime('updated_at');

        return $validator;
    }
}
