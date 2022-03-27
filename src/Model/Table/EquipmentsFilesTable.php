<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\EquipmentsFile;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EquipmentsFiles Model
 *
 * @property EquipmentsTable&BelongsTo $Equipments
 * @property FilesTable&BelongsTo $Files
 *
 * @method EquipmentsFile newEmptyEntity()
 * @method EquipmentsFile newEntity(array $data, array $options = [])
 * @method EquipmentsFile[] newEntities(array $data, array $options = [])
 * @method EquipmentsFile get($primaryKey, $options = [])
 * @method EquipmentsFile findOrCreate($search, ?callable $callback = null, $options = [])
 * @method EquipmentsFile patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method EquipmentsFile[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method EquipmentsFile|false save(EntityInterface $entity, $options = [])
 * @method EquipmentsFile saveOrFail(EntityInterface $entity, $options = [])
 * @method EquipmentsFile[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method EquipmentsFile[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method EquipmentsFile[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method EquipmentsFile[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class EquipmentsFilesTable extends Table
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

        $this->setTable('equipments_files');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Equipments', [
            'foreignKey' => 'equipment_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Files', [
            'foreignKey' => 'file_id',
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
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('updated_at')
            ->allowEmptyDateTime('updated_at');

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
        $rules->add($rules->existsIn('equipment_id', 'Equipments'), ['errorField' => 'equipment_id']);
        $rules->add($rules->existsIn('file_id', 'Files'), ['errorField' => 'file_id']);

        return $rules;
    }
}
