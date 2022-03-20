<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\MaintenanceSessionsMaintenance;
use App\Model\Table\MaintenanceSessionsMaintenancesTable;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;

/**
 * MaintenanceSessionsMaintenances Controller
 *
 * @property MaintenanceSessionsMaintenancesTable $MaintenanceSessionsMaintenances
 * @method MaintenanceSessionsMaintenance[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class MaintenanceSessionsMaintenancesController extends AppController
{
    /**
     * Adds a comment to a maintenance session
     *
     * @param int|string $maintenance_session_maintenance_id The maintenance session id
     * @return void
     */
    public function addComment($maintenance_session_maintenance_id)
    {
        $data = $this->getRequest()->getData();
        $maintenance_session_maintenance = $this->MaintenanceSessionsMaintenances->get($maintenance_session_maintenance_id);
        $comment = $this->MaintenanceSessionsMaintenances->Comments->newEntity([
            'content' => $data['comment'],
        ]);

        if (!$this->MaintenanceSessionsMaintenances->Comments->save($comment)) {
            throw new ValidationException($comment);
        }

        $this->MaintenanceSessionsMaintenances->Comments->link($maintenance_session_maintenance, [$comment]);

        $comment = $this->MaintenanceSessionsMaintenances->Comments->loadInto($comment, [
            'CreatedBy.Files',
        ]);
        $this->set(compact('comment'));
    }

    /**
     * Get comments to a maintenance session maintenance items
     *
     * @param int|string $maintenance_session_maintenance_id The maintenance session id
     * @return void
     */
    public function getComments($maintenance_session_maintenance_id)
    {
        $comments = $this->MaintenanceSessionsMaintenances
            ->Comments
            ->find()
            ->matching('MaintenanceSessionsMaintenances', function (Query $query) use ($maintenance_session_maintenance_id) {
                return $query->where(['MaintenanceSessionsMaintenances.id' => $maintenance_session_maintenance_id]);
            });
        $comments->toArray();
        $this->set(compact('comments'));
    }


}
