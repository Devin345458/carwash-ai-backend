<?php
namespace App\Model\Table;

use App\Error\Exception\ValidationException;
use App\Model\Entity\InventoryTransaction;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InventoryTransactions Model
 *
 * @property TransactionActionsTable|BelongsTo $TransactionActions
 * @property InventoriesTable|BelongsTo $Inventories
 * @method InventoryTransaction get($primaryKey, $options = [])
 * @method InventoryTransaction newEntity($data = null, array $options = [])
 * @method InventoryTransaction[] newEntities(array $data, array $options = [])
 * @method InventoryTransaction|bool save(EntityInterface $entity, $options = [])
 * @method InventoryTransaction saveOrFail(EntityInterface $entity, $options = [])
 * @method InventoryTransaction patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method InventoryTransaction[] patchEntities($entities, array $data, array $options = [])
 * @method InventoryTransaction findOrCreate($search, callable $callback = null, $options = [])
 * @mixin TimestampBehavior
 */
class InventoryTransactionsTable extends Table
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

        $this->setTable('inventory_transactions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('WhoDidIt');

        $this->belongsTo(
            'TransactionActions',
            [
            'foreignKey' => 'transaction_action_id',
            ]
        );

        $this->belongsTo(
            'Inventories',
            [
            'foreignKey' => 'inventory_id',
            ]
        );
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
            ->integer('quantity')
            ->allowEmptyString('quantity');

        $validator
            ->integer('difference')
            ->requirePresence('difference', 'create')
            ->allowEmptyString('difference', false);

        $validator
            ->scalar('model')
            ->requirePresence('model', 'create')
            ->notEmptyString('model');

        $validator
            ->uuid('created_by')
            ->allowEmptyString('created_by');

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
        return $rules;
    }

    /**
     * @param  $inventory_id
     * @param  $quantity
     * @param  $action_id
     * @param  $model
     * @param  $foreign_key
     * @param bool $save
     * @return InventoryTransaction|void
     */
    public function record($inventory_id, $quantity, $action_id, $model, $foreign_key, bool $save = true)
    {
        $action = $this->TransactionActions->get($action_id);
        switch ($action->operation) {
            case TransactionActionsTable::SET:
                $inventory = $this->Inventories->get($inventory_id);
                $difference = $quantity - $inventory->current_stock;
                break;
            case TransactionActionsTable::ADD:
                $difference = $quantity;
                break;
            case TransactionActionsTable::REMOVE:
                $difference = $quantity * -1;
                break;
            default:
                throw new \Exception('Invalid Transaction Action Operation');
        }

        $record = $this->newEntity([
            'inventory_id' => $inventory_id,
            'transaction_action_id' => $action_id,
            'quantity' => $quantity,
            'difference' => $difference,
            'model' => $model,
            'foreign_key' => $foreign_key,
        ]);

        if (!$save) {
            return $record;
        }
        if (!$this->save($record)) {
            throw new ValidationException($record);
        }
    }

    /**
     * Log an inventory transaction
     *
     * @param int $transaction_action_id The transaction
     * @param int $current_stock The current stock
     * @param int $difference The difference change
     * @param int $inventory_id
     * @return void
     */
    public function log(int $transaction_action_id, int $current_stock, int $difference, int $inventory_id)
    {
        $data = [
            'transaction_action_id' => $transaction_action_id,
            'quantity' => $current_stock,
            'difference' => $difference,
            'inventory_id' => $inventory_id,
            'model' => 'Inventories',
        ];
        $entity = $this->newEntity($data);
        if (!$this->save($entity)) {
            throw new ValidationException($entity);
        }
    }

    public function afterSave(EventInterface $event, InventoryTransaction $inventoryTransaction, ArrayObject $options) {
        $inventory = $this->Inventories->get($inventoryTransaction->inventory_id);
        $inventory->current_stock = $inventory->current_stock + $inventoryTransaction->difference;
        $this->Inventories->save($inventory);
    }
}
