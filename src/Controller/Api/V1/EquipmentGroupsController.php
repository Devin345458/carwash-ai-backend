<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\EquipmentGroup;
use App\Model\Table\EquipmentGroupsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;
use Cake\ORM\Query;

/**
 * EquipmentGroups Controller
 *
 * @property EquipmentGroupsTable $EquipmentGroups
 *
 * @method EquipmentGroup[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class EquipmentGroupsController extends AppController
{
    /**
     * Index method
     *
     * @return void
     */
    public function index(string $store_id = null)
    {
        $equipmentGroups = $this->EquipmentGroups->find();

        if (!$store_id) {
            $equipmentGroups->innerJoinWith('Stores.Users', function (Query $query) {
                return $query->where(['Users.id' => $this->Authentication->getUser()->id]);
            });
        } else {
            $equipmentGroups->where([
                'EquipmentGroups.store_id =' => $store_id,
            ]);
        }

        $equipmentGroups->leftJoinWith('Equipments');
        $equipmentGroups->select(['equipment_count' => $equipmentGroups->func()->count('Equipments.id')])->enableAutoFields(true);
        $equipmentGroups->group('EquipmentGroups.id');

        $equipmentGroups = $equipmentGroups->toArray();

        $this->set(compact('equipmentGroups'));
    }

    /**
     * View method
     *
     * @param string|null $id Equipment Group id.
     * @return void
     * @throws RecordNotFoundException When record not found.
     */
    public function view(string $id = null)
    {
        $equipmentGroup = $this->EquipmentGroups->get($id, [
            'contain' => [
                'Equipments.Locations',
                'Maintenances',
            ]
        ]);

        $this->set(compact('equipmentGroup'));
    }

    /**
     * Add method
     *
     * @return void
     */
    public function add()
    {
        $this->getRequest()->allowMethod(['post']);
        $equipmentGroup = $this->EquipmentGroups->newEntity($this->getRequest()->getData());
        if (!$this->EquipmentGroups->save($equipmentGroup, ['associated' => ['Equipments']])) {
            throw new ValidationException($equipmentGroup);
        }
        $this->set(compact('equipmentGroup'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Equipment Group id.
     * @return void
     * @throws RecordNotFoundException When record not found.
     */
    public function edit(string $id = null)
    {
        $this->getRequest()->allowMethod(['post']);
        $equipmentGroup = $this->EquipmentGroups->get($id, [
            'contain' => [
                'Equipments',
                'Maintenances',
            ]
        ]);
        $equipmentGroup = $this->EquipmentGroups->patchEntity($equipmentGroup, $this->request->getData());
        if (!$this->EquipmentGroups->save($equipmentGroup, ['associated' => ['Equipments']])) {
            throw new ValidationException($equipmentGroup);
        }
        $this->set(compact('equipmentGroup'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Equipment Group id.
     * @return void
     * @throws RecordNotFoundException When record not found.
     */
    public function delete(string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $equipmentGroup = $this->EquipmentGroups->get($id);
        if (!$this->EquipmentGroups->delete($equipmentGroup)) {
            throw new ValidationException($equipmentGroup);
        }
        $this->set(['success' => true]);
    }
}
