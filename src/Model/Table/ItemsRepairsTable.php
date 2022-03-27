<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\ItemsRepair;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\Log\Log;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ItemsRepairs Model
 *
 * @property RepairsTable&BelongsTo $Repairs
 * @property ItemsTable&BelongsTo $Items
 *
 * @method ItemsRepair newEmptyEntity()
 * @method ItemsRepair newEntity(array $data, array $options = [])
 * @method ItemsRepair[] newEntities(array $data, array $options = [])
 * @method ItemsRepair get($primaryKey, $options = [])
 * @method ItemsRepair findOrCreate($search, ?callable $callback = null, $options = [])
 * @method ItemsRepair patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method ItemsRepair[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method ItemsRepair|false save(EntityInterface $entity, $options = [])
 * @method ItemsRepair saveOrFail(EntityInterface $entity, $options = [])
 * @method ItemsRepair[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method ItemsRepair[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method ItemsRepair[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method ItemsRepair[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ItemsRepairsTable extends Table
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

        $this->setTable('items_repairs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Repairs', [
            'foreignKey' => 'repair_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Items', [
            'foreignKey' => 'item_id',
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
            ->integer('quantity')
            ->notEmptyString('quantity');

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
        $rules->add($rules->existsIn('repair_id', 'Repairs'), ['errorField' => 'repair_id']);
        $rules->add($rules->existsIn('item_id', 'Items'), ['errorField' => 'item_id']);

        return $rules;
    }
}
