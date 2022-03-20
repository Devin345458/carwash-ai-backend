<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\ItemType;
use App\Model\Table\ItemTypesTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;
use Cake\ORM\Query;
use Exception;

/**
 * ItemTypes Controller
 *
 * @property ItemTypesTable $ItemTypes
 * @method ItemType[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class ItemTypesController extends AppController
{
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $itemTypes = $this->ItemTypes->find()
            ->whereInList('ItemTypes.company_id', [$this->Authentication->getUser()->company_id, 1])
            ->contain([
                'Companies' => function (Query $query) {
                    return $query->select(['Companies.id', 'Companies.name']);
                }
            ]);
        $itemTypes = $this->paginate($itemTypes);
        $this->set(compact('itemTypes'));
    }

    /**
     * Add method
     *
     * @return void
     */
    public function add()
    {
        $itemType = $this->ItemTypes->newEntity($this->request->getData());
        $itemType->company_id = $this->Authentication->getUser()->company_id;
        if (!$this->ItemTypes->save($itemType)) {
            throw new ValidationException($itemType);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Item Type id.
     * @return null Redirects on successful edit, renders view otherwise.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit(string $id = null)
    {
        $data = $this->request->getData();
        unset($data['company']);
        $itemType = $this->ItemTypes->get($id);
        $itemType = $this->ItemTypes->patchEntity($itemType, $data);
        if (!$this->ItemTypes->save($itemType)) {
            throw new ValidationException($itemType);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Item Type id.
     * @return null Redirects to index.
     * @throws RecordNotFoundException When record not found.
     * @throws Exception
     */
    public function delete(string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $itemType = $this->ItemTypes->get($id, [
            'contain' => [
                'Items'
            ]
        ]);
        if (count($itemType->items)) {
            throw new Exception('You must delete all items associated with this item type before you can delete it');
        }
        if (!$this->ItemTypes->delete($itemType)) {
            throw new ValidationException($itemType);
        }
    }
}
