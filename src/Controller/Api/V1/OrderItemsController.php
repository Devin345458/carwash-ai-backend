<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\OrderItem;
use App\Model\Table\OrderItemsTable;
use App\Model\Table\TransferRequestsTable;
use Cake\Collection\Collection;
use Cake\Datasource\ResultSetInterface;
use Cake\I18n\Date;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;

/**
 * Orderitems Controller
 *
 * @property OrderItemsTable $OrderItems
 * @method OrderItem[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrderItemsController extends AppController
{
    public const PENDING = 1;
    public const DENIED = 2;
    public const ACCEPTED = 3;
    public const ORDERED = 4;
    public const RECEIVED = 5;

    /**
     * @throws \Exception
     */
    public function updateStatus()
    {
        $data = new Collection($this->getRequest()->getData());
        $ids = $data->map(function ($value) {
                return $value['id'];
        })->toArray();

        $order_items = $this->OrderItems->find()->where(['OrderItems.id in' => $ids])->toArray();

        foreach ($order_items as &$order_item) {
            $status = $data->firstMatch(['id' => $order_item->id]);
            $order_item->set('order_item_status_id', $status['status']);
        }

        if (!$this->OrderItems->saveMany($order_items)) {
            throw new ValidationException($order_items);
        }
    }

    public function approvedOrderItems($store_id = null)
    {
        $items = $this->OrderItems->find()
            ->where(['OrderItems.order_item_status_id =' => 3])
            ->contain([
                'Stores',
                'Inventories' => [
                    'Items',
                    'Suppliers'
                ]
            ]);

        if ($store_id) {
            $items->where(['OrderItems.store_id' => $store_id]);
        } else {
            $items->matching('Stores', function (Query $query) {
                return $query->matching('Users', function (Query $query) {
                    return $query->where(['Users.id' => $this->Authentication->getUser()->id]);
                });
            });
        }

        $this->set(compact('items'));
    }

    public function purchaseOrderItems($store_id = null)
    {
        $ids = $this->getRequest()->getData('ids') ? $this->getRequest()->getData('ids') : [4, 6];
        $items = $this->OrderItems->find()
            ->where(['OrderItems.order_item_status_id in' => $ids])
            ->contain([
                'Stores',
                'Inventories' => [
                    'Items',
                    'Suppliers',
                ],
                'ActiveTransfers',
            ]);

        if ($store_id) {
            $items->where(['OrderItems.store_id' => $store_id]);
        } else {
            $items->matching('Stores', function (Query $query) {
                return $query->matching('Users', function (Query $query) {
                    return $query->where(['Users.id' => $this->Authentication->getUser()->id]);
                });
            });
        }

        $items->toArray();

        $this->set(compact('items'));
    }

    public function pendingDelivery($store_id = null)
    {
        $ids = $this->getRequest()->getData('ids') ? $this->getRequest()->getData('ids') : [4, 6];
        $items = $this->OrderItems->find()
            ->where(['OrderItems.order_item_status_id in' => $ids])
            ->contain([
                'Stores',
                'Inventories' => [
                    'Items',
                    'Suppliers',
                ],
                'ActiveTransfers',
            ]);

        $now = new FrozenDate();
        if ($this->getRequest()->getParam('late')) {
            $items->where(['OrderItems.expected_delivery_date <' => $now]);
        } else {
            $items->where(['OrderItems.expected_delivery_date >=' => $now]);
        }

        if ($store_id) {
            $items->where(['OrderItems.store_id' => $store_id]);
        } else {
            $items->matching('Stores', function (Query $query) {
                return $query->matching('Users', function (Query $query) {
                    return $query->where(['Users.id' => $this->Authentication->getUser()->id]);
                });
            });
        }

        $items->toArray();

        $this->set(compact('items'));
    }

    public function history($store_id = null)
    {
        $items = $this->OrderItems->find()
            ->where(['OrderItems.order_item_status_id IN' => [self::RECEIVED, self::DENIED]])
            ->contain([
                'Stores',
                'Inventories.Items',
                'ReceivedBy'
            ])->orderDesc('OrderItems.actual_delivery_date');

        if ($store_id) {
            $items->where(['OrderItems.store_id' => $store_id]);
        } else {
            $items->matching('Stores', function (Query $query) {
                return $query->matching('Users', function (Query $query) {
                    return $query->where(['Users.id' => $this->Authentication->getUser()->id]);
                });
            });
        }

        $items = $this->paginate($items);

        $this->set(compact('items'));
    }

    public function markOrdered()
    {
        $data = collection($this->getRequest()->getData());
        $orderItems = $this->OrderItems->find()->whereInList('OrderItems.id', $data->extract('id')->toArray())->toArray();
        $orderItems = $this->OrderItems->patchEntities($orderItems, $data->toArray());
        if (!$this->OrderItems->saveMany($orderItems)) {
            throw new ValidationException($orderItems);
        }
    }

    /**
     * @throws \Exception
     */
    public function markReceived()
    {
        $data = $this->getRequest()->getData();
        $orderItems = $this->OrderItems->find()
            ->where(['OrderItems.id in' => collection($data)->extract('id')->toArray()])
            ->contain(['ActiveTransfers'])
            ->toArray();

        $orderItems = collection($orderItems)->map(function (OrderItem $orderItem, $index) use ($data) {
                if ($orderItem->active_transfer) {
                    $orderItem->active_transfer->transfer_status_id = TransferRequestsTable::TRANSFER_COMPLETED;
                    $orderItem->setDirty('active_transfer', true);
                }
                $orderItem->order_item_status_id = self::RECEIVED;
                $orderItem->received_by = $this->Authentication->getUser()->id;
                $orderItem->receiving_comment = $data[$index]['receiving_comment'];
                $orderItem->location = $data[$index]['location'];
                $orderItem->actual_delivery_date = new FrozenTime();

                return $orderItem;
        })->toArray();

        if (!$this->OrderItems->saveMany($orderItems)) {
            throw new ValidationException($orderItems);
        }
    }

    public function pendingOrderItems($store_id = null) {
        $items = $this->OrderItems->find()
            ->where(['OrderItems.order_item_status_id =' => OrderItemsTable::PENDING])
            ->contain([
                'Stores',
                'Inventories.Items'
            ]);

        if ($store_id) {
            $items->where(['OrderItems.store_id' => $store_id]);
        } else {
            $items->innerJoinWith('Stores.Users', function (Query $query) {
                return $query->where(['Users.id' => $this->Authentication->getUser()->id]);
            });
        }


        $this->set(compact('items'));
    }

    public function getItemCounts($store_id = null)
    {
        $this->set([
            'order_counts' => [
                'pendingCount' => $this->OrderItems->itemCount([1], $this->Authentication->getUser()->id, $store_id),
                'acceptedCount' => $this->OrderItems->itemCount([3], $this->Authentication->getUser()->id, $store_id),
                'purchaseCount' => $this->OrderItems->itemCount([4, 6], $this->Authentication->getUser()->id,$store_id),
            ]
        ]);
    }

    public function orderItemsByInventory($id)
    {
        $this->set([
            'items' => $this->paginate($this->OrderItems->find()->where(['OrderItems.inventory_id' => $id]))
        ]);
    }

    public function dashboardWidget()
    {
        $date = new Date();
        if ($this->Auth->user('active_store') === 'Dashboard') {
            $query = $this->OrderItems->find()->matching(
                'Orders',
                function (Query $q) {
                    return $q->where(['Orders.store_id in' => array_column($this->Auth->user('stores'), 'id')]);
                }
            );
            $query->contain(['Orders.Stores']);
        } else {
            $query = $this->OrderItems->find()->matching(
                'Orders',
                function (Query $q) {
                    return $q->where(['Orders.store_id' => $this->Auth->user('active_store')]);
                }
            );
        }

        $query->contain([
            'Inventories' => [
                'Items',
            ],
        ]);
        $query->where(['OrderItems.expected_delivery_date <=' => $date, 'OrderItems.actual_delivery_date IS' => null]);

        $this->set(['items' => $query]);
    }
}
