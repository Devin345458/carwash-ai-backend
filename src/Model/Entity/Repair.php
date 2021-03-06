<?php
namespace App\Model\Entity;

use App\Classes\ActivityLoggableInterface;
use Aws\S3\S3Client;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Repair Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $status
 * @property FrozenTime $created
 * @property FrozenTime $modified
 * @property bool $completed
 * @property FrozenTime last_completed_date
 * @property FrozenTime $reminder
 * @property int $priority
 * @property float $health_impact
 * @property string $completed_by
 * @property FrozenTime $completed_datetime
 * @property string $assigned_by_id
 * @property string $assigned_to_id
 * @property FrozenTime $assigned_datetime
 * @property string $created_by_id
 * @property string $modified_by_id
 * @property int $store_id
 * @property int $equipment_id
 * @property string completed_reason
 *
 * @property User $created_by
 * @property User $assigned_to
 * @property User $assignedBy
 * @property Store $store
 * @property Equipment $equipment
 * @property Subtask[] $subtasks
 * @property Item[] $items
 * @property File[] $files
 * @property Comment[] $comments
 */
class Repair extends Entity implements ActivityLoggableInterface
{
    public const STATUS_COMPLETE = 'Complete';
    public const STATUS_PENDING = 'Pending Assignment';
    public const STATUS_IN_PROGRESS = 'In Progress';
    public const STATUS_ASSIGNED = 'Assigned';
    public const STATUS_SCHEDULED = 'Scheduled';
    public const STATUS_WAITING = 'Waiting';
    public const STATUS_MONITORING = 'Monitoring';

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
    ];

    public function getMessage($user, string $action): string
    {
        switch ($action) {
            case 'created':
                return $user->full_name . ' created ' . $this->name;
            case 'updated':
                return $user->full_name . ' edited ' . $this->name;
            case 'deleted':
                return $user->full_name . ' deleted ' . $this->name;
            default:
                return 'No Details';
        }
    }
}
