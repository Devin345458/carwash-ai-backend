<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\IncidentFormSubmission;
use Cake\Database\Schema\TableSchemaInterface;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * IncidentFormSubmissions Model
 *
 * @property IncidentFormVersionsTable&BelongsTo $IncidentFormVersions
 * @property UsersTable&BelongsTo $Users
 * @property StoresTable&BelongsTo $Stores
 * @property RecordingsTable&HasMany $Recordings
 * @property ContactLogsTable&HasMany $ContactLogs
 *
 * @method IncidentFormSubmission newEmptyEntity()
 * @method IncidentFormSubmission newEntity(array $data, array $options = [])
 * @method IncidentFormSubmission[] newEntities(array $data, array $options = [])
 * @method IncidentFormSubmission get($primaryKey, $options = [])
 * @method IncidentFormSubmission findOrCreate($search, ?callable $callback = null, $options = [])
 * @method IncidentFormSubmission patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method IncidentFormSubmission[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method IncidentFormSubmission|false save(EntityInterface $entity, $options = [])
 * @method IncidentFormSubmission saveOrFail(EntityInterface $entity, $options = [])
 * @method IncidentFormSubmission[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method IncidentFormSubmission[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method IncidentFormSubmission[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method IncidentFormSubmission[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin TimestampBehavior
 */
class IncidentFormSubmissionsTable extends Table
{
    /**
     * Add data type
     *
     * @param TableSchemaInterface $schema the table
     * @return TableSchemaInterface
     */
    protected function _initializeSchema(TableSchemaInterface $schema): TableSchemaInterface
    {
        $schema = parent::_initializeSchema($schema);
        $schema->setColumnType('data', 'json');

        return $schema;
    }

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('incident_form_submissions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('IncidentFormVersions');
        $this->belongsTo('Users');
        $this->belongsTo('Stores');
        $this->hasMany('Recordings');
        $this->hasMany('ContactLogs');
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
            ->isArray('data')
            ->requirePresence('data', 'create')
            ->notEmptyArray('data');

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
        $rules->add($rules->existsIn('incident_form_version_id', 'IncidentFormVersions'), ['errorField' => 'incident_form_version_id']);
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
