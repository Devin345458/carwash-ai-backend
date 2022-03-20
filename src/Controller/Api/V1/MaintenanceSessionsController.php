<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\MaintenanceSession;
use App\Model\Table\MaintenanceSessionsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;
use Cake\I18n\Time;
use Cake\ORM\Query;

/**
 * MaintenanceSessions Controller
 *
 * @property MaintenanceSessionsTable $MaintenanceSessions
 * @method MaintenanceSession[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class MaintenanceSessionsController extends AppController
{
    /**
     * Get Active User Sessions
     *
     * @param string $store_id Thee store to check for an active maintenance session for the user
     * @return void
     */
    public function getActiveUserSessionByStoreId(string $store_id)
    {
        $session = $this->MaintenanceSessions
            ->find()
            ->where([
                'created_by_id' => $this->Authentication->getUser()->id,
                'store_id' => $store_id,
                'end_time IS' => null,
            ])
            ->contain([
                'Maintenances' => [
                    'Tools.Files',
                    'Parts.Files',
                    'Consumables.Files',
                    'Equipments',
                ],
            ])->first();
        $this->set(compact('session'));
    }

    /**
     * Complete the posted maintenances
     *
     * @param string|int $session_id The session to complete maintenance for
     * @return void
     */
    public function completeMaintenance($session_id)
    {
        $maintenanceSessionMaintenancesTable = $this->getTableLocator()->get('MaintenanceSessionsMaintenances');
        $maintenance_ids = $this->getRequest()->getData('maintenanceIds');
        $maintenance_session_maintenances = $maintenanceSessionMaintenancesTable
            ->find()
            ->where([
                'maintenance_session_id' => $session_id,
                'maintenance_id IN' => $maintenance_ids,
            ])->toArray();

        foreach ($maintenance_session_maintenances as $session_maintenance) {
            $session_maintenance->status = 1;
        }
        if (!$maintenanceSessionMaintenancesTable->saveMany($maintenance_session_maintenances)) {
            throw new ValidationException($maintenance_session_maintenances);
        }
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->getRequest()->allowMethod(['post']);
        $session = $this->MaintenanceSessions->newEntity($this->getRequest()->getData());
        $session->start_time = new Time();
        if (!$this->MaintenanceSessions->save($session)) {
            throw new ValidationException($session);
        }
        $this->set(compact('session'));
    }

    /**
     * Complete Session method
     *
     * @return void
     * @throws RecordNotFoundException When record not found.
     */
    public function completeMaintenanceSession()
    {
        $this->getRequest()->allowMethod(['post']);
        $data = $this->getRequest()->getData();
        $session = $this->MaintenanceSessions->get($data['session_id']);
        $session->end_time = new Time();
        foreach ($data['items_used'] as $item_used) {
            $item = $this->MaintenanceSessions->Maintenances->Items->get($item_used['id']);
            $this->MaintenanceSessions->Maintenances->Items->Inventories->use($item, (int)$item_used['quantity'], $session->store_id, 5);
        }
        if (!$this->MaintenanceSessions->save($session)) {
            throw new ValidationException($session);
        }
    }

    public function dashboardWidget($store_id = null) {
        $this->set(['maintenance_cost' => 1183]);
    }
}
