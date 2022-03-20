<?php
namespace App\Model\Table;

use App\Model\Entity\TransactionAction;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TransactionActions Model
 *
 * @property InventoryTransactionsTable|HasMany $InventoryTransactions
 * @method TransactionAction get($primaryKey, $options = [])
 * @method TransactionAction newEntity($data = null, array $options = [])
 * @method TransactionAction[] newEntities(array $data, array $options = [])
 * @method TransactionAction|bool save(EntityInterface $entity, $options = [])
 * @method TransactionAction saveOrFail(EntityInterface $entity, $options = [])
 * @method TransactionAction patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method TransactionAction[] patchEntities($entities, array $data, array $options = [])
 * @method TransactionAction findOrCreate($search, callable $callback = null, $options = [])
 */
class TransactionActionsTable extends Table
{

    public const ADD = 0;
    public const REMOVE = 1;
    public const SET = 2;

    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('transaction_actions');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('InventoryTransactions', [
            'foreignKey' => 'transaction_action_id',
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->integer('operation')
            ->requirePresence('operation', 'create')
            ->allowEmptyString('operation', false);

        return $validator;
    }
}
