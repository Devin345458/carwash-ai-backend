<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Recordings Model
 *
 * @property \App\Model\Table\IncidentFormSubmissionsTable&\Cake\ORM\Association\BelongsTo $IncidentFormSubmissions
 *
 * @method \App\Model\Entity\Recording newEmptyEntity()
 * @method \App\Model\Entity\Recording newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Recording[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Recording get($primaryKey, $options = [])
 * @method \App\Model\Entity\Recording findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Recording patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Recording[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Recording|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Recording saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Recording[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Recording[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Recording[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Recording[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RecordingsTable extends Table
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

        $this->setTable('recordings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('IncidentFormSubmissions', [
            'foreignKey' => 'incident_form_submission_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('camera')
            ->maxLength('camera', 255)
            ->requirePresence('camera', 'create')
            ->notEmptyString('camera');

        $validator
            ->scalar('start_time')
            ->maxLength('start_time', 255)
            ->requirePresence('start_time', 'create')
            ->notEmptyString('start_time');

        $validator
            ->scalar('end_time')
            ->maxLength('end_time', 255)
            ->requirePresence('end_time', 'create')
            ->notEmptyString('end_time');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('incident_form_submission_id', 'IncidentFormSubmissions'), ['errorField' => 'incident_form_submission_id']);

        return $rules;
    }
}
