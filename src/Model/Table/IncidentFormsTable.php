<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\IncidentForm;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Association\HasOne;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * IncidentForms Model
 *
 * @property StoresTable&BelongsTo $Stores
 * @property IncidentFormVersionsTable&HasMany $IncidentFormVersions
 * @property IncidentFormSubmissionsTable&HasOne $CurrentVersions
 *
 * @method IncidentForm newEmptyEntity()
 * @method IncidentForm newEntity(array $data, array $options = [])
 * @method IncidentForm[] newEntities(array $data, array $options = [])
 * @method IncidentForm get($primaryKey, $options = [])
 * @method IncidentForm findOrCreate($search, ?callable $callback = null, $options = [])
 * @method IncidentForm patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method IncidentForm[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method IncidentForm|false save(EntityInterface $entity, $options = [])
 * @method IncidentForm saveOrFail(EntityInterface $entity, $options = [])
 * @method IncidentForm[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method IncidentForm[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method IncidentForm[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method IncidentForm[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @method Query findByStoreId(string $storeId)
 *
 * @mixin TimestampBehavior
 */
class IncidentFormsTable extends Table
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

        $this->setTable('incident_forms');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('IncidentFormVersions', [
            'foreignKey' => 'incident_form_id',
        ]);

        $this->hasOne('CurrentVersions', [
            'className' => 'IncidentFormVersions',
            'foreignKey' => 'incident_form_id',
        ])->setConditions(function (QueryExpression $query) {
            return $query->equalFields('CurrentVersions.version', 'IncidentForms.version');
        });
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
        $rules->add($rules->existsIn('store_id', 'Stores'), ['errorField' => 'store_id']);

        return $rules;
    }
}
