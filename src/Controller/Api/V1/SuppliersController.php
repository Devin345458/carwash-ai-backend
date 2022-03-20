<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Supplier;
use App\Model\Table\SuppliersTable;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;

/**
 * Suppliers Controller
 *
 * @property SuppliersTable $Suppliers
 * @method Supplier[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class SuppliersController extends AppController
{
    public function index($store_id = null)
    {
        $this->getRequest()->allowMethod('GET');
        $suppliers = $this->Suppliers->find();

        if ($store_id) {
            $suppliers->where(['store_id =' => $store_id]);
        } else {
            $suppliers->matching('Stores.Users', function (Query $query) {
                return $query->where(['Users.id' => $this->Authentication->getUser()->id]);
            });
        }

        $this->set(compact('suppliers'));
    }

    public function upsert($storeId) {
        $data = $this->getRequest()->getData();
        if ($data['id']) {
            $supplier = $this->Suppliers->get($data['id']);
        } else {
            $supplier = $this->Suppliers->newEntity([
                'store_id' => $storeId
            ]);
        }

        $supplier = $this->Suppliers->patchEntity($supplier, $data);
        if (!$this->Suppliers->save($supplier)) {
            throw new ValidationException($supplier);
        }
    }

    public function delete($supplierId)
    {
        $supplier = $this->Suppliers->get($supplierId);
        if (!$this->Suppliers->delete($supplier)) {
            throw new ValidationException($supplier);
        }
    }
}
