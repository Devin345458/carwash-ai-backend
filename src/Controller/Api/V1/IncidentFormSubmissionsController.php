<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\IncidentFormSubmission;
use App\Model\Table\ActivityLogsTable;
use App\Model\Table\IncidentFormSubmissionsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;

/**
 * IncidentFormSubmissions Controller
 *
 * @property IncidentFormSubmissionsTable $IncidentFormSubmissions
 *
 * @method IncidentFormSubmission[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class IncidentFormSubmissionsController extends AppController
{
    /**
     * Index method
     *
     */
    public function index(string $store_id = null)
    {
        $incidentFormSubmissions = $this->IncidentFormSubmissions->find()->contain(['Users', 'Stores']);
        $search = $this->getRequest()->getQuery('search');
        $status = $this->getRequest()->getQuery('status');

        if (!$store_id) {
            $incidentFormSubmissions->innerJoinWith('Stores.Users', function (Query $query) {
                return $query->where(['Users.id' => $this->Authentication->getUser()->id]);
            });
        } else {
            $incidentFormSubmissions->where([
                'IncidentFormSubmissions.store_id =' => $store_id,
            ]);
        }

        if ($status) {
            $incidentFormSubmissions->where(['IncidentFormSubmissions.status' => $status]);
        }

        $incidentFormSubmissions = $this->paginate($incidentFormSubmissions);

        $this->set(compact('incidentFormSubmissions'));
    }

    /**
     * View method
     *
     * @param string|null $id Incident Form Submission id.
     * @return void
     * @throws RecordNotFoundException When record not found.
     */
    public function view(string $id = null)
    {
        $incidentFormSubmission = $this->IncidentFormSubmissions->get($id, [
            'contain' => ['IncidentFormVersions', 'Users', 'Recordings', 'ContactLogs'],
        ]);

        $this->set(compact('incidentFormSubmission'));
    }

    /**
     * Add method
     *
     * @return void
     */
    public function add($storeId)
    {
        $this->getRequest()->allowMethod(['post']);
        $incidentFormSubmission = $this->IncidentFormSubmissions->newEntity($this->getRequest()->getData());
        $incidentFormSubmission->user_id = $this->Authentication->getUser()->id;
        $incidentFormSubmission->store_id = $storeId;
        if (!$this->IncidentFormSubmissions->save($incidentFormSubmission)) {
            throw new ValidationException($incidentFormSubmission);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Incident Form Submission id.
     * @return void
     * @throws RecordNotFoundException When record not found.
     */
    public function edit(string $id = null)
    {
        $this->getRequest()->allowMethod(['post']);
        $incidentFormSubmission = $this->IncidentFormSubmissions->get($id, [
            'contain' => ['IncidentFormVersions', 'Users', 'Recordings', 'ContactLogs']
        ]);

        $incidentFormSubmission = $this->IncidentFormSubmissions->patchEntity($incidentFormSubmission, $this->request->getData());
        if (!$this->IncidentFormSubmissions->save($incidentFormSubmission)) {
            throw new ValidationException($incidentFormSubmission);
        }
        $this->set(compact('incidentFormSubmission'));
    }

    public function changeStatus($id = null) {
        $incidentFormSubmission = $this->IncidentFormSubmissions->get($id);
        $incidentFormSubmission->status = $this->getRequest()->getData('status');
        if (!$this->IncidentFormSubmissions->save($incidentFormSubmission)) {
            throw new ValidationException($incidentFormSubmission);
        }

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

        $activity = $activityLogs->find()
            ->where(['ActivityLogs.scope_model' => 'IncidentFormSubmissions', 'ActivityLogs.scope_id' => $id])
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
                    'Comments.commentable_type' => 'IncidentFormSubmissions',
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

        $activity_logs = $activity
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
            ->order(['ActivityLogs.created_at' => 'ASC']);

        $this->set(['activity_logs' => $activityLogs->toArray()]);
    }
}
