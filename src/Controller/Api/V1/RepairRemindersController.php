<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\RepairReminder;
use Cake\I18n\FrozenTime;

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
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function upsert()
    {
        $this->getRequest()->allowMethod(['post']);
        $reminder = $this->RepairReminders->findOrCreate([
            'repair_id' => $this->getRequest()->getData('id'),
            'user_id' => $this->Authentication->getUser()->id
        ], function (RepairReminder $reminder) {
            $reminder->reminder = new FrozenTime($this->getRequest()->getData('reminder'));
        });

        $reminder->reminder = new FrozenTime($this->getRequest()->getData('reminder'));

        if (!$this->RepairReminders->save($reminder)) {
            throw new ValidationException($reminder);
        }
        $this->set(compact('reminder'));
    }

    public function getReminder($id)
    {
        $this->getRequest()->allowMethod(['get']);
        $reminder = $this->RepairReminders->find()->where([
            'repair_id' => $id,
            'user_id' => $this->Authentication->getUser()->id
        ])->first();

        $this->set(compact('reminder'));
    }
}
