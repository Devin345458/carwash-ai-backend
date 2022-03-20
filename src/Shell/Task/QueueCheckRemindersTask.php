<?php
namespace App\Shell\Task;

use App\Model\Table\RepairRemindersTable;
use App\Utility\NotificationManager;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\Log\Log;
use Pusher\PusherException;
use Queue\Shell\Task\QueueTask;
use RuntimeException;

/**
 * Class QueueCheckRemindersTask
 *
 * @property RepairRemindersTable $RepairReminders
 * @package  App\Shell\Task
 */
class QueueCheckRemindersTask extends QueueTask
{
    /**
     * @var int
     */
    public $timeout = 20;

    /**
     * @var int
     */
    public $retries = 1;

    /**
     * @param  array $data  The array passed to QueuedJobsTable::createJob()
     * @param  int   $jobId The id of the QueuedJob entity
     * @return void
     * @throws PusherException
     */
    public function run(array $data, int $jobId): void
    {
        $this->loadModel('RepairReminders');
        $reminders = $this->RepairReminders
            ->find()
            ->where(['RepairReminders.reminder <' => new Time(), 'RepairReminders.sent' => false])
            ->contain(['Repairs']);
        if ($reminders) {
            foreach ($reminders as $reminder) {
                $repair = $this->RepairReminders->Repairs->findById($reminder['repair_id'])->contain('Photos')->firstOrFail();
                NotificationManager::instance()->notify(
                    [
                    'users' => [$reminder['myuser_id']],
                    'data' => [
                        'title' => 'Repair Reminder',
                        'description' => 'This is a reminder for ' . $repair->name,
                        'image_url' => isset($repair->photos[0]) ? $repair->photos[0]->thumbnail : null,
                        'to' => '/repair/' . $repair->id,
                    ],
                    ]
                );
                $reminder->sent = true;
                $this->RepairReminders->save($reminder);
            }
        }
        $this->QueuedJobs->createJob(
            'CheckReminders',
            [],
            ['notBefore' => Time::now()->modify('+2 minutes'), 'reference' => 'checkReminders']
        );
    }
}
