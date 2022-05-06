<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Item;
use App\Model\Entity\Repair;
use App\Model\Table\ActivityLogsTable;
use App\Model\Table\ItemsRepairsTable;
use App\Model\Table\RepairsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;
use Cake\ORM\Query;

/**
 * Repairs Controller
 *
 * @property RepairsTable $Repairs
 * @method Repair[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class RepairsController extends AppController
{
    /**
     * Index method
     *
     * @param string|null $store_id The store to get repairs for
     * @return void
     */
    public function index(?string $store_id = null)
    {
        $repairs = $this->Repairs->find('repairs', $this->getRequest()->getData('filters'));

        if ($store_id) {
            $repairs->where(['Repairs.store_id =' => $store_id]);
        } else {
            $repairs->matching('Stores', function (Query $query) {
                return $query->matching('Users', function (Query $query) {
                    return $query->where(['Users.id' => $this->Authentication->getUser()->id]);
                });
            });
        }
        $this->set(['repairs' => $this->paginate($repairs, [
            'page' => $this->getRequest()->getData('page'),
            'limit' => $this->getRequest()->getData('perPage'),
            'sort' => $this->getRequest()->getData('sort'),
            'direction' => $this->getRequest()->getData('direction', 'desc'),
        ])]);
    }

    public function add()
    {
        $repair = $this->Repairs->newEntity([
            'name' => $this->getRequest()->getData('name'),
            'status' => 'Pending Assignment',
            'store_id' => $this->getRequest()->getData('store_id'),
            'priority' => 0,
        ]);
        if (!$this->Repairs->save($repair)) {
            throw new ValidationException($repair);
        }
        $repair = $this->Repairs->find('repairs')->where(['Repairs.id' => $repair->id])->firstOrFail();
        $this->set(compact('repair'));
    }

    public function view($id)
    {
        $repair = $this->Repairs->get($id, [
            'contain' => [
                'Items.ActiveStoreInventories',
                'Files',
                'CreatedBy' => [
                    'fields' => [
                        'CreatedBy.first_name',
                        'CreatedBy.last_name',
                    ],
                ],
            ],
        ]);
        $this->set(compact('repair'));
    }

    /**
     * Update a field on a repair
     *
     * @param int|string $id The id of the repair to update e
     */
    public function updateField($id)
    {
        $this->getRequest()->allowMethod('POST');

        $repair = $this->Repairs->get($id, [
            'contain' => [
                'Files',
                'Items.ActiveStoreInventories',
            ],
        ]);

        $field = $this->getRequest()->getData('field');
        $value = $this->getRequest()->getData('value');

        if ($field === 'due_date') {
            $value = new FrozenTime($value);
        }
        $this->Repairs->patchEntity($repair, [$field => $value]);

        if (!$this->Repairs->save($repair)) {
            throw new ValidationException($repair);
        }

        $repair = $this->Repairs->find('repairs')->where(['Repairs.id' => $id])->first();

        $this->set(compact('repair'));
    }

    public function delete($repair_id)
    {
        $repair = $this->Repairs->get($repair_id);
        if (!$this->Repairs->delete($repair)) {
            throw new ValidationException($repair);
        }
    }

    public function completeRepair($id)
    {
        $repair = $this->Repairs->get($id);

        $data = $this->getRequest()->getData();

        if ($repair->completed) {
            $repair->completed = 0;
            $repair->status = $repair->assigned_by_id ? 'Assigned' : 'Pending Assignment';
            $repair->completed_by = null;
            $repair->completed_datetime = null;
            $repair->completed_reason = null;
        } else {
            $repair->completed = 1;
            $repair->status = 'Complete';
            $repair->completed_by = $this->Authentication->getUser()->id;
            $repair->completed_datetime = new Time();
            $repair->completed_reason = $data['reason'];
        }
        if (!$this->Repairs->save($repair)) {
            throw new ValidationException($repair);
        }
        $repair = $this->Repairs->find('repairs')->where(['Repairs.id' => $repair->id])->firstOrFail();
        $this->set(compact('repair'));
    }

    public function dashboardWidget()
    {
        $date = new FrozenDate();
        $upcoming_date = new FrozenDate('+7 days');
        $due = $this->Repairs->find('repairs')->where(['due_date =' => $date]);
        $overdue = $this->Repairs->find('dashboard')->where(['due_date <' => $date]);
        $upcoming = $this->Repairs->find('dashboard')->where(['due_date >' => $date, 'due_date <' => $upcoming_date]);

        $this->set(compact('due', 'overdue', 'upcoming'));
    }

    /**
     * Retrieve all activities on the equipment
     *
     * @param int|string $id The id of the equipment to get activities for
     * @return void
     */
    public function activities($id)
    {
        $this->getRequest()->allowMethod(['JSON', 'GET']);

        /** @var ActivityLogsTable $activityLogs */
        $activityLogs = $this->getTableLocator()->get('ActivityLogs');

        $repairActivity = $activityLogs->find()
            ->where(['ActivityLogs.scope_model' => 'Repairs', 'ActivityLogs.scope_id' => $id])
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
                    'Comments.commentable_type' => 'Repairs',
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

        $items = $activityLogs->find()
            ->where(['ActivityLogs.scope_model' => 'ItemsRepairs'])
            ->innerJoinWith('ItemsRepairs', function (Query $query) use ($id) {
                return $query->where([
                    'ItemsRepairs.repair_id' => $id,
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

        $activity_logs = $repairActivity
            ->union($items)
            ->union($comments);

        $activityLogs = $activityLogs
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

        $this->set(['activity_logs' => $this->paginate($activityLogs)]);
    }

    public function deleteItemFromRepair($id, $itemId)
    {
        /** @var ItemsRepairsTable $itemsRepairsTable */
        $itemsRepairsTable = $this->getTableLocator()->get('ItemsRepairs');
        $itemRepair = $itemsRepairsTable->find()->where(['repair_id' => $id, 'item_id' => $itemId])->firstOrFail();
        if (!$itemsRepairsTable->delete($itemRepair)) {
            throw new ValidationException($itemRepair);
        }
    }

    public function bulkImport()
    {
        $repairs = collection($this->getRequest()->getData());
        $repairs = $repairs->map(function ($field) {
            foreach ($field as $header => $value) {
                if (in_array($header, ['created', 'due_date'])) {
                    $field[$header] = new FrozenTime($value);
                }
            }

            return $field;
        })->toArray();
        $repairs = $this->Repairs->newEntities($repairs);

        if (!$this->Repairs->saveMany($repairs)) {
            throw new ValidationException($repairs);
        }
    }
}
