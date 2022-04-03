<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Item;
use App\Model\Table\ItemsTable;
use Cake\Datasource\ResultSetInterface;

/**
 * Items Controller
 *
 * @property ItemsTable $Items
 * @method Item[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class ItemsController extends AppController
{
    public function search()
    {
        $items = $this->Items->find();

        if ($this->getRequest()->getQuery('search')) {
            $items->where([
                'OR' => [
                    ['Items.name LIKE' => '%' . $this->getRequest()->getQuery('search') . '%'],
                    ['Items.description LIKE' => '%' . $this->getRequest()->getQuery('search') . '%'],
                ]
            ]);
        }

        $items
            ->where([
                'Items.company_id' => $this->Authentication->getIdentityData('company_id')
            ])
            ->order(['Items.id' => 'ASC'])
            ->contain([ 'ActiveStoreInventories', 'Files']);

        if ($this->getRequest()->getQuery('type_id')) {
            $items->where(['Items.item_type_id' => (int) $this->getRequest()->getQuery('type_id')]);
        }

        $items = $items->toArray();

        if ($this->getRequest()->getQuery('selected')) {
            $item = $this->Items->get($this->getRequest()->getQuery('selected'));
            $items[] = $item;
        }


        $this->set(compact('items'));
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function upsert()
    {
        $this->getRequest()->allowMethod('POST');
        $data = $this->getRequest()->getData();
        if ($data['id']) {
            $item = $this->Items->get($data['id'], [
                'contain' => ['ActiveStoreInventories'],
            ]);
        } else {
            $item = $this->Items->newEmptyEntity();
        }
        $item = $this->Items->patchEntity($item, $data);
        $item->company_id = $this->Authentication->getIdentityData('company_id');
        if (!$this->Items->save($item)) {
            throw new ValidationException($item);
        }
        $this->Items->loadInto($item, ['Files']);
        $this->set(compact('item'));
    }
}
