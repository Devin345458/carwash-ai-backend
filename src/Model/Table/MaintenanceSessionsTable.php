<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\MaintenanceSession;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MaintenanceSessions Model
 *
 * @property UsersTable&BelongsTo $CreatedBy
 * @property UsersTable&BelongsTo ModifiedBy
 * @property StoresTable&BelongsTo $Stores
 * @property MaintenanceSessionsMaintenancesTable&HasMany $MaintenanceSessionsMaintenances
 * @property MaintenancesTable&BelongsToMany $Maintenances
 * @method MaintenanceSession newEmptyEntity()
 * @method MaintenanceSession newEntity(array $data, array $options = [])
 * @method MaintenanceSession[] newEntities(array $data, array $options = [])
 * @method MaintenanceSession get($primaryKey, $options = [])
 * @method MaintenanceSession findOrCreate($search, ?callable $callback = null, $options = [])
 * @method MaintenanceSession patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method MaintenanceSession[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method MaintenanceSession|false save(EntityInterface $entity, $options = [])
 * @method MaintenanceSession saveOrFail(EntityInterface $entity, $options = [])
 * @method MaintenanceSession[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method MaintenanceSession[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method MaintenanceSession[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method MaintenanceSession[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class MaintenanceSessionsTable extends Table
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

        $this->setTable('maintenance_sessions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('WhoDidIt');

        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsToMany('Maintenances', [
            'foreignKey' => 'maintenance_session_id',
            'targetForeignKey' => 'maintenance_id',
            'joinTable' => 'MaintenanceSessionsMaintenances',
        ]);
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
            ->dateTime('start_time')
            ->notEmptyDateTime('start_time');

        $validator
            ->dateTime('end_time')
            ->allowEmptyDateTime('end_time');

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
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy'), ['errorField' => 'created_by_id']);
        $rules->add($rules->existsIn(['modified_by_id'], 'ModifiedBy'), ['errorField' => 'modified_by_id']);
        $rules->add($rules->existsIn(['store_id'], 'Stores'), ['errorField' => 'store_id']);
        $rules->add($rules->isUnique(['end_time', 'store_id', 'created_by_id'], 'You already have a maintenance session for this store'));

        return $rules;
    }
}
