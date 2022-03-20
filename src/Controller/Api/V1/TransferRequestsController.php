<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\OrderItem;
use App\Model\Entity\TransferRequest;
use App\Model\Table\OrderItemsTable;
use App\Model\Table\TransferRequestsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;
use Exception;

/**
 * TransferRequests Controller
 *
 * @property TransferRequestsTable $TransferRequests
 * @method TransferRequest[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class TransferRequestsController extends AppController
{

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     * @throws Exception
     */
    public function add()
    {
        $transferRequests = $this->TransferRequests->newEntities($this->getRequest()->getData());

        $orderItems = $this->TransferRequests->OrderItems->find()
            ->whereInList('OrderItems.id', collection($this->getRequest()->getData())->extract('order_item_id')->toArray())->all();
        $orderItems->each(function (OrderItem $orderItem) { $orderItem->order_item_status_id =  OrderItemsTable::TRANSFER_REQUESTED; });

        $this->TransferRequests->getConnection()->begin();
        try {
            if (!$this->TransferRequests->saveMany($transferRequests)) {
                throw new ValidationException($transferRequests);
            }
            if (!$this->TransferRequests->OrderItems->saveMany($orderItems)) {
                throw new ValidationException($orderItems);
            }
            $this->TransferRequests->getConnection()->commit();
        } catch (Exception $exception) {
            $this->TransferRequests->getConnection()->rollback();
            throw $exception;
        }
    }

    public function getItemCounts($store_id = null)
    {
        $this->set([
            'pendingCount' => $this->TransferRequests->itemCount(TransferRequestsTable::TRANSFER_REQUEST, $this->Authentication->getUser()->id, $store_id),
            'pickupCount' => $this->TransferRequests->itemCount(TransferRequestsTable::TRANSFER_APPROVED_FOR_PICKUP, $this->Authentication->getUser()->id, $store_id),
            'deliveryCount' => $this->TransferRequests->itemCount(TransferRequestsTable::TRANSFER_APPROVED_FOR_DELIVERY, $this->Authentication->getUser()->id, $store_id),
        ]);
    }

    /**
     * @throws Exception
     */
    public function getTransfersByStatus($storeId = null)
    {
        $statusIds = $this->getRequest()->getQuery('statusIds');
        if (!$statusIds || !is_array($statusIds)) {
            throw new Exception('Invalid Status Ids');
        }
        $transfers = $this->TransferRequests->getTransfersByStatus(
            $statusIds,
            $this->Authentication->getUser()->id,
            $storeId
        )->toArray();
        $this->set(compact('transfers'));
    }

    public function history()
    {
        $active_store = $this->Auth->user('active_store');
        $transfers = $this->TransferRequests->findAllByTransferStatusId(6)
            ->contain(
                [
                'OrderItems.Inventories' => [
                    'Items',
                ],
                'FromStores',
                'ToStores',
                'ApprovedBy',
                ]
            );

        if ($active_store === 'Dashboard') {
            $transfers->where(['TransferRequests.from_store_id in' => array_column($this->Auth->user('stores'), 'id')]);
        } else {
            $transfers->where(['TransferRequests.from_store_id' => $active_store]);
        }

        $this->set(
            [
            'transfers' => $transfers,
            '_serialize' => ['transfers'],
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function updateStatus()
    {
        $data = $this->getRequest()->getData();
        $transfers = $this->TransferRequests->find()
            ->where(['TransferRequests.id in' => $data['transfer_ids']])
            ->contain(['OrderItems'])
            ->all()
            ->map(
                function (TransferRequest $transfer) use ($data) {
                    $transfer->transfer_status_id = $data['transfer_status_id'];
                    $transfer->approved_by_id = $this->Authentication->getUser()->id;
                    if ($transfer->transfer_status_id === TransferRequestsTable::TRANSFER_DENIED) {
                        $transfer->denial_reason = $data['denial_reason'];
                        $transfer->order_item->order_item_status_id = OrderItemsTable::ACCEPTED;
                    } else {
                        $transfer->order_item->expected_delivery_date = $data['expected_delivery_date'];
                    }
                    $transfer->setDirty('order_item', true);

                    return $transfer;
                }
            )->toArray();

        if (!$this->TransferRequests->saveMany($transfers, ['associated' => ['OrderItems']])) {
            throw new ValidationException($transfers);
        }
    }

    /**
     * Delete method
     *
     * @param  string|null $id Transfer Request id.
     * @return Response|null Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $transferRequest = $this->TransferRequests->get($id);
        if ($this->TransferRequests->delete($transferRequest)) {
            $this->Flash->success(__('The transfer request has been deleted.'));
        } else {
            $this->Flash->error(__('The transfer request could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
