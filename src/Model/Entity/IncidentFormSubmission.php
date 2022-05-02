<?php
declare(strict_types=1);
namespace App\Model\Entity;
use App\Classes\ActivityLoggableInterface;
use Cake\ORM\Entity;

/**
 * IncidentFormSubmission Entity
 *
 * @property int $id
 * @property array $data
 * @property int $incident_form_version_id
 * @property string $user_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\IncidentFormVersion $incident_form_version
 * @property \App\Model\Entity\User $user
 * @OA\Schema(title="IncidentFormSubmission", description="Entity",
 * @OA\Property( type="integer", property="id", description="id"),
 * @OA\Property( type="text", property="data", description="data"),
 * @OA\Property( type="integer", property="incident_form_version_id", description="incident_form_version_id"),
 * @OA\Property( type="string", property="user_id", description="user_id"),
 * @OA\Property( type="timestamp", property="created", description="created"),
 * @OA\Property( type="timestamp", property="updated", description="updated"),
 * @OA\Property( type="object", property="incident_form_version", description="incident_form_version" , ref="#/components/schemas/IncidentFormVersion"),
 * @OA\Property( type="object", property="user", description="user" , ref="#/components/schemas/User"),
 * )
 */
class IncidentFormSubmission extends Entity implements ActivityLoggableInterface
{
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
        'data' => 1,
        'incident_form_version_id' => 1,
        'user_id' => 1,
        'created' => 1,
        'updated' => 1,
        'incident_form_version' => 1,
        'user' => 1,
        'recordings' => 1,
        'contact_logs' => 1,
    ];

    public function getMessage($user, string $action): string
    {
        switch ($action) {
            case 'created':
                return "$user->full_name filed a incident report for {$this->data['first_name']} {$this->data['last_name']}";
            case 'updated':
                return "$user->full_name edited incident report for {$this->data['first_name']} {$this->data['last_name']}";
            case 'deleted':
                return "$user->full_name deleted a incident report for {$this->data['first_name']} {$this->data['last_name']}";
            default:
                return 'No Details';
        }
    }
}
