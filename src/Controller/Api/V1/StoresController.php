<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Store;
use App\Model\Table\StoresTable;
use Cake\Datasource\ResultSetInterface;
use Cake\I18n\Date;
use Cake\I18n\FrozenDate;
use ChargeBee_Subscription;
use Exception;

/**
 * Stores Controller
 *
 * @property StoresTable $Stores
 * @method Store[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class StoresController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Authentication->addUnauthenticatedActions(['subscriptionEndPoint']);
    }

    /**
     * Return the users active store
     *
     * @return void
     */
    public function getActiveStore()
    {
        $active_store_id = $this->Authentication->getIdentityData('active_store');
        $store = false;
        if ($active_store_id) {
            $store = $this->Stores->get($active_store_id);
        }
        $this->set(compact('store'));
    }

    /**
     * Return the users stores
     *
     * @param bool $pagination Wether to return all or paginate the request
     * @return void
     */
    public function getUsersStores($pagination = true)
    {
        $stores = $this->Stores->find();
        $stores
            ->select(['user_count' => $stores->func()->count('Users.id')])
            ->innerJoinWith('Users', function ($q) {
                    return $q->where(['Users.id =' => $this->Authentication->getUser()->id]);
            })
            ->enableAutoFields()
            ->where(['store_type_id' => 1])
            ->group('Stores.id');

        if ($pagination) {
            $stores = $this->paginate($stores);
        }

        $stores = $stores->toArray();

        $this->set(compact('stores'));
    }

    public function getUsersWarehouses($pagination = true)
    {
        $stores = $this->Stores->find()
            ->matching(
                'Users',
                function ($q) {
                    return $q->where(['Users.id =' => $this->Authentication->getIdentityData('id')]);
                }
            )->where(['store_type_id' => 2]);

        if ($pagination) {
            $stores = $this->paginate($stores);
        }

        $this->set(compact('stores'));
    }

    /**
     * Changes the users active store
     *
     * @param $store_id
     * @return void
     */
    public function setStore($store_id)
    {
        $user = $this->Authentication->getUser();
        if ($store_id === '0') {
            $user->active_store = 0;
            $store = 0;
        } else {
            $store = $this->Stores->get($store_id);
            $this->Authorization->authorize($store, 'view');
            $user->active_store = $store->id;
        }
        if (!$this->Stores->Users->save($user)) {
            throw new ValidationException($user);
        }
        $this->set(compact('store', 'user'));
    }

    public function settings()
    {
        $this->getRequest()->allowMethod('GET');
        $store = $this->Stores->find('settings', ['user_id' => $this->Authentication->getUser()->active_store])->first();
        $this->set(compact('store'));
    }

    public function saveSettings($id)
    {
        $data = $this->getRequest()->getData();

        $store = $this->Stores->get($id);
        $this->Stores->patchEntity($store, $data);
        if (!$this->Stores->save($store)) {
            throw new ValidationException($store);
        }
        $this->set(['store' => $store]);
    }

    /**
     * Return the users for the store
     *
     * @param int|string $store_id The store id to get users for
     */
    public function users($store_id)
    {
        $store = $this->Stores->get($store_id, [
            'contain' => ['Users'],
        ]);
        $this->Authorization->authorize($store, 'view');
        $this->set(['users' => $store->users]);
    }

    public function subscriptionEndPoint()
    {
        $data = $this->getRequest()->getData();
        if ($data['event_type'] === 'subscription_cancellation_scheduled' || $data['event_type'] === 'subscription_deleted' || $data['event_type'] === 'subscription_cancelled') {
            /** @var Store $store */
            $store = $this->Stores->find()->where(['subscription_id' => $data['content']['subscription']['id']])->firstOrFail();
            $store->canceled = true;
            $store->cancel_date = new FrozenDate($data['content']['subscription']['current_term_end']);
            $store->cancel_reason = $data['content']['subscription']['cancel_reason'] ?: 'Initiated By User';
            $this->Stores->save($store);
        } elseif ($data['event_type'] === 'subscription_scheduled_cancellation_removed' || $data['event_type'] === 'subscription_renewed') {
            /** @var Store $store  */
            $store = $this->Stores->find()->where(['subscription_id' => $data['content']['subscription']['id']])->firstOrFail();
            $store->canceled = false;
            $store->cancel_date = null;
            $store->cancel_reason = null;
            $this->Stores->save($store);
        }
    }

    /**
     * @param  $store_id
     * @throws Exception
     */
    public function reactivate($store_id)
    {
        $store = $this->Stores->get($store_id);
        $result = ChargeBee_Subscription::reactivate($store->subscription_id, [
            'invoiceImmediately' => true,
        ]);
        $subscription = $result->subscription();
        if (!$subscription) {
            throw new ValidationException('Unable to reactivate subscription, please contact support', 500);
        }

        $store->canceled = false;
        $store->cancel_date = null;
        $store->cancel_reason = null;
        if (!$this->Stores->save($store)) {
            throw new ValidationException($store);
        }
    }

    public function delete($store_id)
    {
        $store = $this->Stores->get($store_id);
        if (!$this->Stores->delete($store)) {
            throw new ValidationException($store);
        }
    }

    /**
     * @param  $store_id
     * @throws Exception
     */
    public function cancelSubscription($store_id)
    {
        $store = $this->Stores->get($store_id);
        $result = ChargeBee_Subscription::cancel($store->subscription_id, [
            'endOfTerm' => true,
        ]);
        $subscription = $result->subscription();
        if (!$subscription) {
            throw new Exception('Unable to cancel subscription, please contact support', 500);
        }

        $store->canceled = true;
        $store->cancel_date = new Date($subscription->currentTermEnd);
        $store->cancel_reason = 'Initiated By User';
        if (!$this->Stores->save($store)) {
            throw new Exception('Something went wrong when canceling your subscription, please contact support', 500);
        }
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add(): void
    {
        $owners_ids = $this->Stores->Users->find()
            ->where(['company_id' => $this->Authentication->getUser()->company_id, 'role' => 'owner'])
            ->select('id')
            ->all()
            ->extract('id')
            ->toArray();


        $data = $this->request->getData();
        // Add all owners to every store
        if (!isset($data['Users'])) {
            $data['Users'] = ['_ids' => []];
        }
        $data['Users']['_ids'] = array_merge($data['Users']['_ids'], $owners_ids);
        $data['company_id'] = $this->Authentication->getUser()->company_id;
        if ($data['store_type_id'] === 2) {
            $data['plan_id'] = '0';
        }
        $store = $this->Stores->newEntity( $data);

        if (!$this->Stores->save($store)) {
            throw new ValidationException($store);
        }
    }

    public function view($id = null): void
    {
        $store = $this->Stores->get($id);
        $this->set(compact('store'));
    }

}
