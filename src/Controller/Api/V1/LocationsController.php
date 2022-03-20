<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Location;
use App\Model\Table\LocationsTable;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;

/**
 * Locations Controller
 *
 * @property LocationsTable $Locations
 * @method Location[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class LocationsController extends AppController
{
    public function getStoresLocations($store_id)
    {
        $locations = $this->Locations->find('storeLocations', ['store_id' => $store_id]);
        $this->set(compact('locations'));
    }

    public function upsert($storeId) {
        $data = $this->getRequest()->getData();
        if ($data['id']) {
            $location = $this->Locations->get($data['id']);
        } else {
            $location = $this->Locations->newEntity([
                'store_id' => $storeId
            ]);
        }

        $location = $this->Locations->patchEntity($location, $data);
        if (!$this->Locations->save($location)) {
            throw new ValidationException($location);
        }
    }

    public function reorder() {
        $ids = $this->getRequest()->getData('locationIds');
        $this->Locations->setOrder($ids);
    }

    public function delete($locationId)
    {
        $location = $this->Locations->get($locationId);
        if (!$this->Locations->delete($location)) {
            throw new ValidationException($location);
        }
    }

}
