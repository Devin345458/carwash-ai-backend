<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\CarCount;
use App\Model\Table\CarCountsTable;
use Cake\Datasource\ResultSetInterface;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;

/**
 * CarCounts Controller
 *
 * @property CarCountsTable $CarCounts
 * @method CarCount[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class CarCountsController extends AppController
{
    public function checkCarCount()
    {
        if (!$this->Authentication->getUser()->active_store) {
            $this->set(['status' => false]);
            return;
        }

        $store = $this->CarCounts->Stores->get($this->Authentication->getUser()->active_store);

        if (!$store->allow_car_counts) {
            $this->set(['status' => false]);
            return;
        }

        $car_count = $this->CarCounts->find()
            ->where(['store_id =' => $this->Authentication->getUser()->active_store])
            ->last();

        if (!$car_count) {
            $this->set(['status' => 'Never Set']);
            return;
        }

        $today = new FrozenTime();
        $today->setTimezone($this->getRequest()->getQuery('timezone'));

        if ($car_count->date_of_cars->toDateString() === $today->toDateString()) {
            $status = false;
        } else {
            $status = $car_count->date_of_cars;
        }
        $this->set(['count_status' => $status]);
    }
}
