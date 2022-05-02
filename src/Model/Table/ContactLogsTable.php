<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\ContactLog;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ContactLogs Model
 *
 * @property UsersTable&BelongsTo $Users
 * @property IncidentFormSubmissionsTable&BelongsTo $IncidentFormSubmissions
 *
 * @method ContactLog newEmptyEntity()
 * @method ContactLog newEntity(array $data, array $options = [])
 * @method ContactLog[] newEntities(array $data, array $options = [])
 * @method ContactLog get($primaryKey, $options = [])
 * @method ContactLog findOrCreate($search, ?callable $callback = null, $options = [])
 * @method ContactLog patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method ContactLog[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method ContactLog|false save(EntityInterface $entity, $options = [])
 * @method ContactLog saveOrFail(EntityInterface $entity, $options = [])
 * @method ContactLog[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method ContactLog[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method ContactLog[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method ContactLog[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ContactLogsTable extends Table
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

        $this->setTable('contact_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('IncidentFormSubmissions', [
            'foreignKey' => 'incident_form_submission_id',
            'joinType' => 'INNER',
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
            ->dateTime('when', ['iso8601'])
            ->requirePresence('when', 'create')
            ->notEmptyDateTime('when');

        $validator
            ->scalar('spoke_to')
            ->maxLength('spoke_to', 255)
            ->requirePresence('spoke_to', 'create')
            ->notEmptyString('spoke_to');

        $validator
            ->scalar('details')
            ->maxLength('details', 255)
            ->requirePresence('details', 'create')
            ->notEmptyString('details');

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
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);
        $rules->add($rules->existsIn('incident_form_submission_id', 'IncidentFormSubmissions'), ['errorField' => 'incident_form_submission_id']);

        return $rules;
    }
}
