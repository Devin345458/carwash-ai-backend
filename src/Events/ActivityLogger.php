<?php


namespace App\Events;


use App\Model\Table\ActivityLogsTable;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Locator\LocatorAwareTrait;

class ActivityLogger implements EventListenerInterface
{
    use LocatorAwareTrait;

    public function implementedEvents(): array
    {
        return [
            'Model.afterSave' => 'logActivity',
            'Model.afterDelete' => 'logActivity'
        ];
    }

    public function logActivity(EventInterface $event, EntityInterface $entity, ArrayObject $options) {
        /** @var ActivityLogsTable $activitiesTable */
        $activitiesTable = $this->getTableLocator()->get('ActivityLogs');
        $activitiesTable->logActivity($event, $entity, $options);
    }

}
