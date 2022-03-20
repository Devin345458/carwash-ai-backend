<?php
namespace App\Model\Table;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Inventory;
use App\Model\Entity\Item;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Exception;

/**
 * Inventories Model
 *
 * @property ItemsTable|BelongsTo $Items
 * @property StoresTable|BelongsTo $Stores
 * @property SuppliersTable|BelongsTo $Suppliers
 * @property InventoryTransactionsTable|HasMany $InventoryTransactions
 * @property OrderItemsTable|HasMany $OrderItems
 * @method Inventory get($primaryKey, $options = [])
 * @method Inventory newEntity($data = null, array $options = [])
 * @method Inventory[] newEntities(array $data, array $options = [])
 * @method Inventory|bool save(EntityInterface $entity, $options = [])
 * @method Inventory saveOrFail(EntityInterface $entity, $options = [])
 * @method Inventory patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Inventory[] patchEntities($entities, array $data, array $options = [])
 * @method Inventory findOrCreate($search, callable $callback = null, $options = [])
 */
class InventoriesTable extends Table
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

        $this->setTable('inventories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('WhoDidIt');

        $this->belongsTo('Items', [
            'foreignKey' => 'item_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Suppliers', [
            'foreignKey' => 'supplier_id',
            'joinType' => 'LEFT',
        ]);

        $this->belongsTo('Files', [
            'foreignKey' => 'file_id',
        ]);

        $this->hasMany('InventoryTransactions', [
            'foreignKey' => 'inventory_id',
        ]);
        $this->hasMany('OrderItems', [
            'foreignKey' => 'inventory_id',
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
            ->numeric('cost')
            ->allowEmptyString('cost');

        $validator
            ->integer('current_stock')
            ->requirePresence('current_stock', 'create')
            ->allowEmptyString('current_stock', false);

        $validator
            ->integer('initial_stock')
            ->allowEmptyString('initial_stock', 'create');

        $validator
            ->integer('desired_stock')
            ->allowEmptyString('desired_stock');

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
        $rules->add($rules->existsIn(['item_id'], 'Items'));
        $rules->add($rules->existsIn(['store_id'], 'Stores'));
        $rules->add(function ($entity, $options) use ($rules) {
            return $entity->supplier_id === 0 || $rules->existsIn(['supplier_id'], 'Suppliers');
        }, 'supplier');
        $rules->add($rules->isUnique(['store_id', 'item_id'], 'There is already an inventory record for this item at this store'));

        return $rules;
    }

    /**
     * If the entity is new set initial stock to current stock
     *
     * @param Event           $event
     * @param Inventory $inventory
     * @param ArrayObject     $options
     */
    public function beforeSave(Event $event, Inventory $inventory, ArrayObject $options)
    {
        if ($inventory->isNew()) {
            $inventory->initial_stock = $inventory->current_stock;
            $inventory->inventory_transactions = [
                $this->InventoryTransactions->newEntity(
                    [
                    'transaction_action_id' => 1,
                    'quantity' => $inventory->current_stock,
                    'difference' => $inventory->current_stock,
                    'model' => 'Inventories',
                    ]
                ),
            ];
        }
    }

    /**
     * Use an inventory item
     * @param Item $item The item to use
     * @param int $quantity How much was used
     * @param string $store_id The store whos inventory to get
     * @param int $transaction_action_id The type of action
     * @return void
     * @throws Exception
     */
    public function use(Item $item, int $quantity, string $store_id, int $transaction_action_id)
    {
        $inventory = $this->findOrCreate(['item_id' => $item->id, 'store_id' => $store_id], function (Inventory $inventory) {
            $inventory->current_stock = 0;
            $inventory->supplier_id = 0;
            $inventory->cost = 0;
            $inventory->desired_stock = 0;
        });
        $this->InventoryTransactions->record($inventory->id, $quantity, $transaction_action_id, ItemsTable::class, $item->id);
    }
}
