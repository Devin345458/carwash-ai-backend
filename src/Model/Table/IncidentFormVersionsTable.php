<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\IncidentFormVersion;
use Cake\Database\Schema\TableSchemaInterface;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * IncidentFormVersions Model
 *
 * @property IncidentFormsTable&BelongsTo $IncidentForms
 * @property IncidentFormSubmissionsTable&HasMany $IncidentFormSubmissions
 *
 * @method IncidentFormVersion newEmptyEntity()
 * @method IncidentFormVersion newEntity(array $data, array $options = [])
 * @method IncidentFormVersion[] newEntities(array $data, array $options = [])
 * @method IncidentFormVersion get($primaryKey, $options = [])
 * @method IncidentFormVersion findOrCreate($search, ?callable $callback = null, $options = [])
 * @method IncidentFormVersion patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method IncidentFormVersion[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method IncidentFormVersion|false save(EntityInterface $entity, $options = [])
 * @method IncidentFormVersion saveOrFail(EntityInterface $entity, $options = [])
 * @method IncidentFormVersion[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method IncidentFormVersion[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method IncidentFormVersion[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method IncidentFormVersion[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin TimestampBehavior
 */
class IncidentFormVersionsTable extends Table
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

        $this->setTable('incident_form_versions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('IncidentForms', [
            'foreignKey' => 'incident_form_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('IncidentFormSubmissions', [
            'foreignKey' => 'incident_form_version_id',
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
            ->integer('version')
            ->requirePresence('version', 'create')
            ->notEmptyString('version');

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
        $rules->add($rules->existsIn('incident_form_id', 'IncidentForms'), ['errorField' => 'incident_form_id']);

        return $rules;
    }
}
