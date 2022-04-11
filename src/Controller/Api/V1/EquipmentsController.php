<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Equipment;
use App\Model\Table\ActivityLogsTable;
use App\Model\Table\EquipmentsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Query;
use Exception;

/**
 * Equipments Controller
 *
 * @property EquipmentsTable $Equipments
 * @property ActivityLogsTable $ActivityLogs
 * @method Equipment[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class EquipmentsController extends AppController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        $this->loadModel('ActivityLogs');
        parent::initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * Returns all equipment for a store
     *
     * @param int|string|null $store_id The stores equipment to get
     * @return void
     */
    public function getEquipment($store_id = null)
    {
        $this->getRequest()->allowMethod(['GET']);
        $equipment = $this->Equipments->find();

        if (!$store_id) {
            $equipment = $this->Equipments->find()->matching('Stores', function (Query $query) {
                return $query->matching('Users', function (Query $query) {
                    return $query->where(['Users.id' => $this->Authentication->getUser()->id]);
                });
            });
        } else {
            $equipment->where([
                'Equipments.store_id =' => $store_id,
            ]);
        }

        $equipment->contain(['DisplayImage', 'Locations', 'Stores', 'Manufacturers']);
        $equipment->select([
            'Equipments.id',
            'Equipments.location_id',
            'Equipments.store_id',
            'Stores.name',
            'DisplayImage.name',
            'DisplayImage.dir',
            'Locations.name',
            'Equipments.name',
            'Equipments.position',
            'Manufacturers.id',
            'Manufacturers.name',
        ]);

        $equipment = $equipment->toArray();

        $this->set(compact('equipment'));
    }

    /**
     * Add equipment to active store
     *
     * @return void
     * @throws Exception
     */
    public function add()
    {
        $this->getRequest()->allowMethod('POST');
        $equipment = $this->Equipments->newEntity($this->getRequest()->getData());
        $defaultLocation = $this->Equipments->Locations->find()->where([
            'store_id' => $equipment->store_id,
            'default_location' => true,
        ])->firstOrFail();
        $equipment->location_id = $defaultLocation->id;
        if (!$this->Equipments->save($equipment)) {
            throw new ValidationException($equipment);
        }
        $this->set(compact('equipment'));
    }

    /**
     * Add equipment to active store
     *
     * @return void
     * @throws Exception
     */
    public function edit($id = null)
    {
        $this->getRequest()->allowMethod('POST');
        $equipment = $this->Equipments->get($id, [
            'contain' => [
                'Categories',
                'Locations',
            ],
        ]);
        $equipment = $this->Equipments->patchEntity($equipment, $this->getRequest()->getData());
        if (!$this->Equipments->save($equipment, ['associated' => ['Categories']])) {
            throw new ValidationException($equipment);
        }
        $this->set(compact('equipment'));
    }

    /**
     * Get equipment by id
     *
     * @param string|int $id The id of the equipment to get
     * @return void
     */
    public function view($id)
    {
        $equipment = $this->Equipments->get($id, [
            'contain' => [
                'Stores',
                'Categories',
                'Locations',
            ],
        ]);

        $completed_maintenance_count = $this->Equipments->Maintenances->MaintenanceSessionsMaintenances
            ->find()
            ->innerJoinWith('Maintenances.Equipments', function (Query $query) use ($id) {
                return $query->where(['Equipments.id' => $id]);
            })
            ->where(['status' => 1])
            ->count();

        $repair_count = $this->Equipments->Repairs
            ->find()
            ->where(['Repairs.equipment_id' => $id, 'Repairs.status' => 'Completed'])
            ->count();

        $this->set(compact('equipment', 'repair_count', 'completed_maintenance_count'));
    }

    /**
     * Retrieve all activities on the equipment
     *
     * @param int|string $id The id of the equipment to get activities for
     * @return void
     */
    public function equipmentActivities($id)
    {
        $this->getRequest()->allowMethod(['JSON', 'POST']);

        /** @var ActivityLogsTable $activityLogs */
        $activityLogs = $this->getTableLocator()->get('ActivityLogs');

        $equipmentActivity = $activityLogs->find()
            ->where(['ActivityLogs.scope_model' => 'Equipments'])
            ->innerJoinWith('Equipments', function (Query $query) use ($id) {
                return $query->where(['Equipments.id' => $id]);
            })
            ->select([
                'id' => 'ActivityLogs.id',
                'created_at' => 'ActivityLogs.created_at',
                'scope_model' => 'ActivityLogs.scope_model',
                'scope_id' => 'ActivityLogs.scope_id',
                'issuer_model' => 'ActivityLogs.issuer_model',
                'issuer_id' => 'ActivityLogs.issuer_id',
                'object_model' => 'ActivityLogs.object_model',
                'object_id' => 'ActivityLogs.object_id',
                'level' => 'ActivityLogs.level',
                'action' => 'ActivityLogs.action',
                'message' => 'ActivityLogs.message',
                'data' => 'ActivityLogs.data',
            ]);

        $repairActivity = $activityLogs->find()
            ->where(['ActivityLogs.scope_model' => 'Repairs'])
            ->innerJoinWith('Repairs', function (Query $query) use ($id) {
                return $query->where(['Repairs.equipment_id' => $id]);
            })
            ->select([
                'id' => 'ActivityLogs.id',
                'created_at' => 'ActivityLogs.created_at',
                'scope_model' => 'ActivityLogs.scope_model',
                'scope_id' => 'ActivityLogs.scope_id',
                'issuer_model' => 'ActivityLogs.issuer_model',
                'issuer_id' => 'ActivityLogs.issuer_id',
                'object_model' => 'ActivityLogs.object_model',
                'object_id' => 'ActivityLogs.object_id',
                'level' => 'ActivityLogs.level',
                'action' => 'ActivityLogs.action',
                'message' => 'ActivityLogs.message',
                'data' => 'ActivityLogs.data',
            ]);

        $maintenance = $activityLogs->find()
            ->where(['ActivityLogs.scope_model' => 'Maintenances'])
            ->innerJoinWith('Maintenances', function (Query $query) use ($id) {
                return $query->where(['Maintenances.equipment_id' => $id]);
            })
            ->select([
                'id' => 'ActivityLogs.id',
                'created_at' => 'ActivityLogs.created_at',
                'scope_model' => 'ActivityLogs.scope_model',
                'scope_id' => 'ActivityLogs.scope_id',
                'issuer_model' => 'ActivityLogs.issuer_model',
                'issuer_id' => 'ActivityLogs.issuer_id',
                'object_model' => 'ActivityLogs.object_model',
                'object_id' => 'ActivityLogs.object_id',
                'level' => 'ActivityLogs.level',
                'action' => 'ActivityLogs.action',
                'message' => 'ActivityLogs.message',
                'data' => 'ActivityLogs.data',
            ]);

        $comments = $activityLogs->find()
            ->where(['ActivityLogs.scope_model' => 'Comments'])
            ->innerJoinWith('Comments', function (Query $query) use ($id) {
                return $query->where([
                    'Comments.commentable_id = ' => $id,
                    'Comments.commentable_type' => 'Equipments',
                ]);
            })
            ->select([
                'id' => 'ActivityLogs.id',
                'created_at' => 'ActivityLogs.created_at',
                'scope_model' => 'ActivityLogs.scope_model',
                'scope_id' => 'ActivityLogs.scope_id',
                'issuer_model' => 'ActivityLogs.issuer_model',
                'issuer_id' => 'ActivityLogs.issuer_id',
                'object_model' => 'ActivityLogs.object_model',
                'object_id' => 'ActivityLogs.object_id',
                'level' => 'ActivityLogs.level',
                'action' => 'ActivityLogs.action',
                'message' => 'ActivityLogs.message',
                'data' => 'ActivityLogs.data',
            ]);

        $completedMaintenaces = $activityLogs->find()
            ->where([
                'ActivityLogs.scope_model' => 'MaintenanceSessionsMaintenances',
                'ActivityLogs.action' => 'updated',
            ])
            ->innerJoinWith('MaintenanceSessionsMaintenances.Maintenances', function (Query $query) use ($id) {
                return $query->where(['Maintenances.equipment_id' => $id]);
            })
            ->select([
                'id' => 'ActivityLogs.id',
                'created_at' => 'ActivityLogs.created_at',
                'scope_model' => 'ActivityLogs.scope_model',
                'scope_id' => 'ActivityLogs.scope_id',
                'issuer_model' => 'ActivityLogs.issuer_model',
                'issuer_id' => 'ActivityLogs.issuer_id',
                'object_model' => 'ActivityLogs.object_model',
                'object_id' => 'ActivityLogs.object_id',
                'level' => 'ActivityLogs.level',
                'action' => 'ActivityLogs.action',
                'message' => 'ActivityLogs.message',
                'data' => 'ActivityLogs.data',
            ]);

        $activity_logs = $equipmentActivity
            ->union($repairActivity)
            ->union($maintenance)
            ->union($comments)
            ->union($completedMaintenaces);

        $query = $activityLogs
            ->find()
            ->from([
                $activityLogs->getAlias() => $activity_logs,
            ])
            ->contain(['Users'])
            ->select([
                'Users.id',
                'Users.first_name',
                'Users.last_name',
                'Users.file_id',
                'ActivityLogs.id',
                'ActivityLogs.message',
                'ActivityLogs.issuer_id',
                'ActivityLogs.object_model',
                'ActivityLogs.created_at',
                'ActivityLogs.data',
                'ActivityLogs.action',
            ])
            ->order(['ActivityLogs.created_at' => 'DESC']);

        $this->set(['activity_logs' => $this->paginate($query, [''])]);
    }

    /**
     * Delete method
     *
     * @param  string|null $id Equipment id.
     * @return void
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $equipment = $this->Equipments->get($id);
        if (!$this->Equipments->delete($equipment)) {
            throw new ValidationException($equipment);
        }
        $this->set(['message' => __('The {0} has been deleted.', 'Equipment'),]);
    }

    /**
     * Retrieve a searched list of
     *
     * @param null $store_id The store to search in
     */
    public function byStore($store_id = null)
    {
        if (!$store_id) {
            $this->set(['equipment' => []]);

            return;
        }
        $store = $this->Equipments->Stores
            ->find()
            ->where(['id' => $store_id])
            ->contain([
                'Equipments' => function (Query $query) {
                    return $query->where(
                        ['Equipments.name LIKE' => '%' . $this->getRequest()->getQuery('search') . '%' ]
                    )->contain(['Manufacturers', 'DisplayImage']);
                },
            ])
            ->first();
        $this->Authorization->authorize($store, 'view');
        $this->set(['equipment' => $store->equipments]);
    }

    /**
     * Catalogue method
     */
    public function catalogue()
    {
        $this->getRequest()->allowMethod(['GET', 'JSON']);

        $equipmentCatalogue = $this->Equipments->find()->matching('Stores', function (Query $q) {
            return $q->where(['Stores.company_id in' => [$this->Authentication->getUser()->company_id, 1]]);
        })->contain(['Stores', 'Manufacturers']);

        if ($this->getRequest()->getQuery('search')) {
            $equipmentCatalogue->where(['Equipments.name LIKE' => '%' . $this->getRequest()->getQuery('search') . '%']);
        }

        $equipmentCatalogue = $this->paginate($equipmentCatalogue);
        $equipmentCatalogue->each(function (Equipment $equipment) {
            $equipment->quantity = 1;
        });

        $this->set(['catalogue' => $equipmentCatalogue]);
    }

    /**
     * @throws Exception
     */
    public function reorder($locationId)
    {
        $equipmentIds = $this->getRequest()->getData('equipmentIds');
        if (!count($equipmentIds)) {
            return;
        }
        $equipments = $this->Equipments->find()->whereInList('id', $equipmentIds);
        foreach ($equipments as $equipment) {
            $equipment->location_id = $locationId;
        }
        $this->Equipments->saveMany($equipments);
        $this->Equipments->setOrder($equipmentIds);
    }

    public function copyEquipment($storeId)
    {
        $equipments = $this->getRequest()->getData('equipment');
        $companyItems = $this->Equipments->Maintenances->Items->find()
            ->where([
                'company_id' => $this->Authentication->getUser()->company_id,
            ])
            ->all()
            ->indexBy('name')
            ->toArray();

        $defaultLocation = $this->Equipments->Locations->find()->where([
            'store_id' => $storeId,
            'default_location' => true,
        ])->firstOrFail();

        $newEquipments = [];
        foreach ($equipments as $equipment) {
            for ($i = 0; $i < $equipment['quantity']; $i++) {
                $newEquipment = $this->Equipments->get($equipment['id'], [
                    'contain' => [
                        'Maintenances.Items',
                    ],
                ]);
                $newEquipment->id = null;
                $newEquipment->location_id = $defaultLocation->id;
                $newEquipment->name = $newEquipment->name . ' - Copy ' . ($i + 1);
                $newEquipment->manufacturer_id = 0;
                $newEquipment->store_id = $storeId;
                $newEquipment->isNew(true);
                $newEquipment->position = null;

                foreach ($newEquipment->maintenances as $maintenance) {
                    $maintenance->id = null;
                    $maintenance->store_id = $storeId;
                    $maintenance->equipment_id = null;
                    $maintenance->isNew(true);

                    foreach ($maintenance->items as $item) {
                        if (isset($companyItems[$item->name])) {
                            $item = $companyItems[$item->name];
                            $inventory = $this->Equipments
                                ->Maintenances
                                ->Items
                                ->Inventories
                                ->find()
                                ->where([
                                    'item_id' => $item->id,
                                    'store_id' => $storeId,
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
                            $inventory = $this->Equipments
                                ->Maintenances
                                ->Items
                                ->Inventories
                                ->newEntity([
                                    'store_id' => $storeId,
                                    'cost' => 0,
                                    'supplier_id' => 0,
                                    'current_stock' => 0,
                                    'initial_stock' => 0,
                                    'desired_stock' => 0,
                                ]);
                            $item->inventories = [$inventory];
                        }
                    }
                }
                $newEquipments[] = $newEquipment;
            }
        }

        $saved = $this->Equipments->saveMany($newEquipments, [
            'associated' => [
                'Maintenances' => [
                    'Items' => [
                        'Inventories',
                    ],
                ],
            ],
        ]);

        if (!$saved) {
            throw new ValidationException($newEquipment);
        }
    }
}
