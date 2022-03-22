<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\MaintenanceSessionsMaintenance;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MaintenanceSessionsMaintenances Model
 *
 * @property MaintenancesTable&BelongsTo $Maintenances
 * @property MaintenanceSessionsTable&BelongsTo $MaintenanceSessions
 * @property CommentsTable&BelongsToMany $Comments
 * @method MaintenanceSessionsMaintenance newEmptyEntity()
 * @method MaintenanceSessionsMaintenance newEntity(array $data, array $options = [])
 * @method MaintenanceSessionsMaintenance[] newEntities(array $data, array $options = [])
 * @method MaintenanceSessionsMaintenance get($primaryKey, $options = [])
 * @method MaintenanceSessionsMaintenance findOrCreate($search, ?callable $callback = null, $options = [])
 * @method MaintenanceSessionsMaintenance patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method MaintenanceSessionsMaintenance[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method MaintenanceSessionsMaintenance|false save(EntityInterface $entity, $options = [])
 * @method MaintenanceSessionsMaintenance saveOrFail(EntityInterface $entity, $options = [])
 * @method MaintenanceSessionsMaintenance[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method MaintenanceSessionsMaintenance[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method MaintenanceSessionsMaintenance[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method MaintenanceSessionsMaintenance[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class MaintenanceSessionsMaintenancesTable extends Table
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

        $this->setTable('maintenance_sessions_maintenances');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Maintenances', [
            'foreignKey' => 'maintenance_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('MaintenanceSessions', [
            'foreignKey' => 'maintenance_session_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsToMany('Comments');
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
            ->notEmptyString('status');

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
        $rules->add($rules->existsIn(['maintenance_id'], 'Maintenances'), ['errorField' => 'maintenance_id']);
        $rules->add($rules->existsIn(['maintenance_session_id'], 'MaintenanceSessions'), ['errorField' => 'maintenance_session_id']);

        return $rules;
    }

    /**
     * @param EventInterface $event The event
     * @param MaintenanceSessionsMaintenance $entity The entity
     * @param ArrayObject $options The options
     * @return void
     */
    public function beforeSave(EventInterface $event, MaintenanceSessionsMaintenance $entity, ArrayObject $options)
    {
        if ($entity->status === 1 && $entity->isDirty('status')) {
            $this->Maintenances->complete($entity->maintenance_id);
        }
    }

    public function findCost(Query $q, array $options)
    {
        $q->contain(
            ['Inventoryuses' => function (Query $q) {
                return $q->select(['cost' => $q->func()->sum('cost'), 'foreign_key']);
            }]
        );
        $q->matching(
            'Maintenances',
            function (Query $q) use ($options) {
                if (is_array($options['store_id'])) {
                    return $q->where(['Maintenances.store_id in' => $options['store_id']]);
                }

                return $q->where(['Maintenances.store_id' => $options['store_id']]);
            }
        );

        return $q;
    }
}
