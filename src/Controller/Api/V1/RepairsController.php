<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Item;
use App\Model\Entity\Repair;
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
    public function index(string $store_id = null)
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
        $this->set(['repairs' => $this->paginate($repairs)]);
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
                'CreatedBy' => [
                    'fields' => [
                        'CreatedBy.first_name',
                        'CreatedBy.last_name',
                    ],
                ]
            ]
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

        $repair = $this->Repairs->get($id);

        $field = $this->getRequest()->getData('field');
        $value = $this->getRequest()->getData('value');

        if ($field === 'due_date') {
            $repair[$field] = new FrozenTime($value);
        } else {
            $repair[$field] = $value;
        }

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

    public function dashboardWidget() {
        $date = new FrozenDate();
        $upcoming_date = new FrozenDate('+7 days');
        $due = $this->Repairs->find('repairs')->where(['due_date =' => $date]);
        $overdue = $this->Repairs->find('dashboard')->where(['due_date <' => $date]);
        $upcoming = $this->Repairs->find('dashboard')->where(['due_date >' => $date, 'due_date <' => $upcoming_date]);

        $this->set(compact('due', 'overdue', 'upcoming'));
    }
}
