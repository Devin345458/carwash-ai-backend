<?php
namespace App\Model\Table;

use App\Error\Exception\ValidationException;
use App\Model\Entity\OrderItem;
use ArrayObject;
use Cake\Collection\Collection;
use Cake\Collection\CollectionInterface;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Exception;

/**
 * Orderitems Model
 *
 * @property InventoriesTable|BelongsTo $Inventories
 * @method OrderItem get($primaryKey, $options = [])
 * @method OrderItem newEntity($data = null, array $options = [])
 * @method OrderItem[] newEntities(array $data, array $options = [])
 * @method OrderItem|bool save(EntityInterface $entity, $options = [])
 * @method OrderItem|bool saveOrFail(EntityInterface $entity, $options = [])
 * @method OrderItem patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method OrderItem[] patchEntities($entities, array $data, array $options = [])
 * @method OrderItem findOrCreate($search, callable $callback = null, $options = [])
 */
class OrderItemsTable extends Table
{

    public const PENDING = 1;
    public const DENIED = 2;
    public const ACCEPTED = 3;
    public const ORDERED = 4;
    public const RECEIVED = 5;
    public const TRANSFER_REQUESTED = 6;

    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('order_items');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('WhoDidIt');

        $this->belongsTo('Stores');

        $this->belongsTo('Inventories', [
            'foreignKey' => 'inventory_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('ReceivedBy', [
            'className' => 'Users',
            'propertyName' => 'receivedBy',
            'foreignKey' => 'received_by',
        ]);

        $this->hasMany('TransferRequests');

        $this->hasOne('ActiveTransfers', [
            'className' => 'TransferRequests',
            'foreignKey' => 'order_item_id',
            'conditions' => ['transfer_status_id in' => [
                TransferRequestsTable::TRANSFER_REQUEST,
                TransferRequestsTable::TRANSFER_APPROVED_FOR_PICKUP,
                TransferRequestsTable::TRANSFER_APPROVED_FOR_DELIVERY,
            ]],
        ]);

        $this->hasMany('OrderItemStatusHistories');
    }

    /**
     * Default validation rules.
     *
     * @param  Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->integer('quantity')
            ->requirePresence('quantity', 'create')
            ->allowEmptyString('quantity', false);

        $validator
            ->integer('order_id')
            ->allowEmptyString('order_id', 'create');

        $validator
            ->integer('inventory_id')
            ->requirePresence('inventory_id', 'create')
            ->allowEmptyString('inventory_id', false);

        $validator
            ->dateTime('created')
            ->allowEmptyDateTime('created', 'create');

        $validator
            ->dateTime('modified')
            ->allowEmptyDateTime('modified', 'create');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param  RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['store_id'], 'Stores'));
        $rules->add($rules->existsIn(['inventory_id'], 'Inventories'));
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy', 'The user you created this with no longer exists'));
        $rules->add($rules->existsIn(['modified_by_id'], 'ModifiedBy', 'The user you modified this with no longer exists'));
        $rules->add($rules->existsIn(['received_by'], 'ReceivedBy', 'The user you created this with no longer exists'));

        return $rules;
    }

    public function itemCount(array $order_item_status_id, string $user_id, $store_id = null): int
    {
        $query = $this->find()->where(['OrderItems.order_item_status_id IN' => $order_item_status_id]);

        if ($store_id) {
            $query->where(['OrderItems.store_id' => $store_id]);
        } else {
            $query->matching('Stores', function (Query $query) use ($user_id) {
                return $query->matching('Users', function (Query $query) use ($user_id) {
                    return $query->where(['Users.id' => $user_id]);
                });
            });
        }


        return $query->count();
    }

    /**
     * @throws Exception
     */
    public function afterSave(Event $event, OrderItem $entity, ArrayObject $options)
    {
        if ($entity->actual_delivery_date && $entity->isDirty('actual_delivery_date')) {
            $this->Inventories->InventoryTransactions->record($entity->inventory_id, $entity->quantity, 2, get_class($this), $entity->id);
        }
    }

    /**
     * @param Collection $data
     * @param string $store_id
     * @param string $method
     * @param bool $save
     * @return OrderItem[] | void
     * @throws Exception
     */
    public function order(CollectionInterface $data, string $store_id, string $method, bool $save = true)
    {
        if (!$data->count()) {
            return;
        }

        $orderItems = $data->map( function ($orderItem) use ($store_id, $method) {
            return $this->newEntity([
                'store_id' => $store_id,
                'method' => $method,
                'quantity' => $orderItem['id'],
                'order_item_status_id' => self::PENDING,
                'inventory_id' => $orderItem['order'],
            ]);
        })->toArray();

        if ($save) {
            if (!$this->saveMany($orderItems)) {
                throw new ValidationException($orderItems);
            }
        } else {
            return $orderItems;
        }
    }
}
