<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Inventory;
use App\Model\Table\InventoriesTable;
use Cake\Collection\Collection;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;
use Cake\ORM\Query;
use Exception;

/**
 * Inventories Controller
 *
 * @property InventoriesTable $Inventories
 * @method Inventory[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class InventoriesController extends AppController
{
    /**
     * Index method
     *
     * @return Response|void
     */
    public function index($store_id = null)
    {
        $inventories = $this->Inventories
            ->find()
            ->contain([
                'Items' => ['ItemTypes'],
                'Stores',
                'Suppliers'
            ]);

        if ($store_id) {
           $inventories->where(['Inventories.store_id' => $store_id]);
        } else {
            $inventories->matching('Stores.Users', function (Query $query) {
                return $query->where(['Users.id' => $this->Authentication->getUser()->id]);
            });
        }

        if ($this->getRequest()->getQuery('search')) {
            $inventories->matching('Items', function (Query $query) {
                return $query->where(['Items.name LIKE' => '%' . $this->getRequest()->getQuery('search') . '%']);
            });
        }

        if ($this->getRequest()->getQuery('type_ids')) {
            $inventories->matching('Items.ItemTypes', function (Query $query) {
                return $query->where(['ItemTypes.id IN' => $this->getRequest()->getQuery('type_ids')]);
            });
        }


        $inventories = $this->paginate($inventories);
        $this->set(compact('inventories'));
    }

    /**
     * View method
     *
     * @param string|null $id Inventory id.
     * @return Response|void
     * @throws RecordNotFoundException When record not found.
     */
    public function view(string $id = null)
    {
        $inventory = $this->Inventories->get($id, [
            'contain' => ['Items'],
        ]);

        $this->set(compact('inventory'));
    }

    /**
     * upsert method
     *
     * @throws Exception
     */
    public function upsert()
    {
        $message = 'Successfully saved inventory record';
        $data = $this->getRequest()->getData();
        if (isset($data['id'])) {
            $inventory = $this->Inventories->get($data['id'], [
                'contain' => 'Items',
            ]);
        } else {
            $inventory = $this->Inventories->newEmptyEntity();
            // Look to see if the item already exists in the database
            $itemLookUp = $this->Inventories->Items->find()->where([
                'name =' => ucwords(strtolower($data['item']['name'])),
                'company_id in' => [1, $this->Authentication->getUser()->company_id],
            ])->contain('Inventories')->first();

            // If we find an item check that if it has an inventory record for the store
            if ($itemLookUp) {
                $collection = new Collection($itemLookUp['inventories']);
                $found = $collection->firstMatch(['store_id' => $data['store_id']]);
                if ($found) {
                    throw new Exception('An inventory record for this item already exists', 422);
                }

                // Remove their item creation and set it to the existing item
                unset($data['item']);
                $message = 'There was already item in the catalogue with this name so we created you a inventory record for it';
                $data['item_id'] = $itemLookUp->id;
            }
        }
        $inventory = $this->Inventories->patchEntity($inventory, $data, ['associated' => ['Items']]);
        if (!$this->Inventories->save($inventory, false)) {
            throw new ValidationException($inventory);
        }
        $inventory = $this->Inventories->get($inventory->id, [
            'contain' => ['Items'],
        ]);
        $this->set(['success_message' => $message, 'inventory' => $inventory]);
    }

    public function storesWithInventory($itemId, $quantity, $storeId = null) {
        $stores = $this->Inventories
            ->Stores
            ->find()
            ->innerJoinWith('Inventories', function (Query $query) use ($itemId, $quantity){
                return $query->where([
                    'Inventories.current_stock >=' => $quantity,
                    'Inventories.item_id' => $itemId
                ]);
            })
            ->contain([
                'Inventories' => function (Query $query) use ($itemId) {
                    return $query->where(['Inventories.item_id' => $itemId]);
                }
            ])
            ->where([
                'Stores.id !=' => $storeId,
                'Stores.company_id' => $this->Authentication->getUser()->company_id
            ])
            ->toArray();

        $this->set(compact('stores'));
    }

    public function types() {
        $types = $this->Inventories->Items->ItemTypes->find()->whereInList('ItemTypes.company_id', [$this->Authentication->getUser()->company_id, 1]);
        $this->set(compact('types'));
    }

    public function dashboardWidget($store_id = null)
    {
        $query = $this->Inventories->find();
        if ($store_id === 'undefined') {
            $query->innerJoinWith('Stores.Users', function (Query $query) {
                return $query->where(['Users.id' => $this->Authentication->getUser()->id]);
            });
        } else {
            $query->where(['store_id' => $store_id]);
        }

        $total = $query->all()->sumOf('total');

        $this->set(compact('total'));
    }
}
