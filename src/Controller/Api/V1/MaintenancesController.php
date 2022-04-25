<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Controller\Component\InventoryComponent;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Equipment;
use App\Model\Entity\EquipmentGroup;
use App\Model\Entity\Maintenance;
use App\Model\Table\MaintenancesTable;
use App\Model\Table\SuppliersTable;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;
use Cake\Utility\Inflector;

/**
 * Maintenances Controller
 *
 * @property MaintenancesTable $Maintenances
 * @property SuppliersTable $Suppliers
 * @method   Maintenance[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class MaintenancesController extends AppController
{
    /**
     * Add method
     *
     * @return void
     */
    public function add()
    {
        $data = $this->getRequest()->getData();

        $maintenance = $this->Maintenances->newEntity($data['maintenance']);
        $maintenance = $this->Maintenances->patchEntity($maintenance, $data);
        $maintenance->maintainable_type = Inflector::camelize(Inflector::tableize($maintenance->maintainable_type));
        if (!$maintenance->maintainable_id) {
            return;
        }

        /** @var Equipment|EquipmentGroup $entity */
        $entity = $this->getTableLocator()
            ->get($maintenance->maintainable_type)
            ->get($maintenance->maintainable_id);

        $maintenance->store_id = $entity->store_id;

        if (!$this->Maintenances->save($maintenance)) {
            throw new ValidationException($maintenance);
        }

        $this->set(['success' => true]);
    }

    /**
     * Edit method
     *
     * @param $id
     * @return void
     */
    public function edit($id)
    {
        $data = $this->getRequest()->getData();
        unset($data['equipment']);

        $maintenance = $this->Maintenances->get($id, [
            'contain' => [
                'Items'
            ]
        ]);
        $maintenance = $this->Maintenances->patchEntity($maintenance, $data, [
            'associated' => ['Items']
        ]);

        if (!$this->Maintenances->save($maintenance, ['associated' => ['Items']])) {
            throw new ValidationException($maintenance);
        }

        $this->set(['success' => true]);
    }

    /**
     * Delete method
     *
     * @param  string|null $id Maintenance id.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $this->request->allowMethod(['post', 'delete']);
        $maintenance = $this->Maintenances->get($id);
        if (!$this->Maintenances->delete($maintenance)) {
            throw new ValidationException($maintenance);
        }
        $this->set([
            'success' => true,
            'message' => __('The {0} has been deleted.', 'Maintenance'),
        ]);
    }

    /**
     * Returns store maintenance grouped by due and upcoming and grouped equipment and sorted by equipment order
     * @param string $store_id The store to check for
     * @return void
     */
    public function storesMaintenance(string $store_id)
    {
        $dueMaintenance = $this->Maintenances->dueEquipmentMaintenance($store_id, $this->Authentication->getUser()->id, true);
        $upcomingMaintenance = $this->Maintenances->dueEquipmentMaintenance($store_id, $this->Authentication->getUser()->id, false);
        $this->set(compact('dueMaintenance', 'upcomingMaintenance'));
    }

    public function getMaintenances()
    {
        $maintenances = $this->Maintenances->find()
            ->where(['Maintenances.store_id' => $this->Auth->user('active_store')])
            ->contain([
                'Items.Inventories',
                'Procedures',
                'Maintainables',
            ]);
        $this->set(['maintenances' => $maintenances]);
    }

    public function dashboardWidget($store_id = null)
    {
        $dueMaintenance = $this->Maintenances->dueEquipmentMaintenance($store_id, $this->Authentication->getUser()->id, true);
        $upcomingMaintenance = $this->Maintenances->dueEquipmentMaintenance($store_id, $this->Authentication->getUser()->id, false);

        $this->set([
            'dueMaintenance' => count($dueMaintenance),
            'upcomingMaintenance' => count($upcomingMaintenance),
        ]);
    }

    public function getMaintenance($id)
    {
        $maintenance = $this->Maintenances->get(
            $id,
            [
            'contain' => ['Maintainables', 'Items.ActiveStoreInventories', 'Procedures'],
            ]
        );
        $this->set(['maintenance' => $maintenance]);
    }

    public function catalogue()
    {
        $this->request->allowMethod(['JSON']);
        $catalogue = $this->Maintenances->find()
            ->innerJoinWith('Stores', function (Query $query) {
                return $query->where(['Stores.company_id in' => [$this->Authentication->getUser()->company_id, 1]]);
            })
            ->contain([
                'Maintainables',
                'Items',
                'Stores',
            ])->toArray();

        $this->set(compact('catalogue'));
    }

    public function getMaintenancesByIds()
    {
        $ids = $this->getRequest()->getData('ids');
        $maintenances = $this->Maintenances->find()
            ->where(['Maintenances.id in' => $ids])
            ->contain([
                'Items.Inventories',
                'Procedures',
            ]);
        $this->set(['maintenances' => $maintenances]);
    }

    public function view($id) {
        $maintenance = $this->Maintenances->get($id, [
            'contain' => [
                'Items' => ['ActiveStoreInventories'],
                'Maintainables'
            ]
        ]);
        $this->set(compact('maintenance'));
    }

    public function equipment($id = null) {
        $maintenances = $this->Maintenances->find()->where([
            'Maintenances.maintainable_id' => $id,
            'Maintenances.maintainable_type' => 'Equipments'
        ]);
        $this->set(compact('maintenances'));
    }

    public function copyMaintenance($maintainableType, $maintainableId) {
        $maintenances = $this->getRequest()->getData('maintenances');
        $maintainableTable = $this->getTableLocator()->get(Inflector::camelize(Inflector::tableize($maintainableType)));
        /** @var Equipment|EquipmentGroup $maintainable */
        $maintainable = $maintainableTable->get($maintainableId);

        $companyItems = $this->Maintenances->Items->find()
            ->where([
                'company_id' => $this->Authentication->getUser()->company_id
            ])
            ->all()
            ->indexBy('name')
            ->toArray();

        $newMaintenance = [];
        foreach ($maintenances as $maintenance) {
            $maintenance = $this->Maintenances->get($maintenance['id'], [
                'contain' => [
                    'Items'
                ]
            ]);

            $maintenance->id = null;
            $maintenance->store_id = $maintainable->store_id;
            $maintenance->maintainable_id = $maintainable->id;
            $maintenance->maintainable_type = Inflector::camelize(Inflector::tableize($maintainableType));
            $maintenance->isNew(true);

            foreach ($maintenance->items as $item) {
                if (isset($companyItems[$item->name])) {
                    $item = $companyItems[$item->name];
                    $inventory = $this->Maintenances
                        ->Items
                        ->Inventories
                        ->find()
                        ->where([
                            'item_id' => $item->id,
                            'store_id' => $maintainable->store_id
                        ])
                        ->first();
                    if ($inventory) {
                        $item->inventories = [$inventory];
                    }
                } else {
                    $item->id = null;
                    $item->company_id = $this->Authentication->getUser()->company_id;
                    $item->isNew(true);
                }

                if (!$item->inventories) {
                    $inventory = $this->Maintenances
                        ->Items
                        ->Inventories
                        ->newEntity([
                            'store_id' => $maintainable->store_id,
                            'cost' => 0,
                            'supplier_id' => 0,
                            'current_stock' => 0,
                            'initial_stock' => 0,
                            'desired_stock' => 0,
                        ]);
                    $item->inventories = [$inventory];
                }
            }
            $newMaintenance[] = $maintenance;
        }

        $saved = $this->Maintenances->saveMany($newMaintenance, [
            'associated' => [
                'Items' => [
                    'Inventories'
                ]
            ]
        ]);

        if (!$saved) {
            throw new ValidationException($newMaintenance);
        }
    }
}
