<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;

/**
 * RepairReminders Controller
 *
 * @property \App\Model\Table\RepairRemindersTable $RepairReminders
 *
 * @method \App\Model\Entity\RepairReminder[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RepairRemindersController extends AppController
{

    /**
     * Upsert method
     *
     * @OA\Post(path="/v1/RepairReminders",
     *   operationId="upsert",
     *   summary="Upsert a RepairReminders",
     *   tags={"RepairReminders"},
     *   @OA\RequestBody(
     *       required=true,
     *       description="The Repair Reminder you want to add",
     *       @OA\JsonContent(ref="#/components/schemas/Repairreminder")
     *   ),
     *   @OA\Response(
     *    response="200",
     *    description="Successful operation",
     *    @OA\JsonContent(ref="#/components/schemas/Repairreminder")
     *   ),
     *  )
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function upsert()
    {
        $this->getRequest()->allowMethod(['post']);
        if ($this->getRequest()->getData('id')) {
            $reminder = $this->RepairReminders->get($this->getRequest()->getData('id'));
            $reminder = $this->RepairReminders->patchEntity($reminder, $this->getRequest()->getData());
        } else {
            $reminder = $this->RepairReminders->newEntity($this->getRequest()->getData());
            $reminder->user_id = $this->Authentication->getUser()->id;
        }
        if (!$this->RepairReminders->save($reminder)) {
            throw new ValidationException($reminder);
        }
        $this->set(compact('reminder'));
    }
}
