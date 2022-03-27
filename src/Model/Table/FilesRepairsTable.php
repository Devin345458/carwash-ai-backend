<?php
namespace App\Model\Table;

use App\Model\Entity\FilesRepair;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PhotosRepairs Model
 *
 * @property FilesTable|BelongsTo $Files
 * @property RepairsTable|BelongsTo $Repairs
 * @method FilesRepair get($primaryKey, $options = [])
 * @method FilesRepair newEntity($data = null, array $options = [])
 * @method FilesRepair[] newEntities(array $data, array $options = [])
 * @method FilesRepair|bool save(EntityInterface $entity, $options = [])
 * @method FilesRepair saveOrFail(EntityInterface $entity, $options = [])
 * @method FilesRepair patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method FilesRepair[] patchEntities($entities, array $data, array $options = [])
 * @method FilesRepair findOrCreate($search, callable $callback = null, $options = [])
 */
class FilesRepairsTable extends Table
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

        $this->setTable('files_repairs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Files', [
            'foreignKey' => 'file_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Repairs', [
            'foreignKey' => 'repair_id',
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
            ->allowEmptyString('id', 'create');

        $validator
            ->boolean('cover')
            ->allowEmptyString('cover', false);

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
        $rules->add($rules->existsIn(['file_id'], 'Files'));
        $rules->add($rules->existsIn(['repair_id'], 'Repairs'));

        return $rules;
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        if (isset($data['cover'])) {
            $data['cover'] = filter_var($data['cover'], FILTER_VALIDATE_BOOLEAN);
        }
    }

    public function beforeSave(EventInterface $event, FilesRepair $entity, ArrayObject $options) {
        $count = $this->find()
            ->where(['repair_id' => $entity->repair_id])
            ->count();

        if ($count === 0) {
            $entity->cover = true;
        }
    }
}
