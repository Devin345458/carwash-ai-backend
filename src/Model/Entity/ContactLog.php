<?php
declare(strict_types=1);
namespace App\Model\Entity;
use Cake\ORM\Entity;

/**
 * ContactLog Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime $when
 * @property string $spoke_to
 * @property string $details
 * @property string $user_id
 * @property int $incident_form_submission_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\IncidentFormSubmission $incident_form_submission
 * @OA\Schema(title="ContactLog", description="Entity",
 * @OA\Property( type="integer", property="id", description="id"),
 * @OA\Property( type="datetime", property="when", description="when"),
 * @OA\Property( type="string", property="spoke_to", description="spoke_to"),
 * @OA\Property( type="string", property="details", description="details"),
 * @OA\Property( type="string", property="user_id", description="user_id"),
 * @OA\Property( type="integer", property="incident_form_submission_id", description="incident_form_submission_id"),
 * @OA\Property( type="object", property="user", description="user" , ref="#/components/schemas/User"),
 * @OA\Property( type="object", property="incident_form_submission", description="incident_form_submission" , ref="#/components/schemas/IncidentFormSubmission"),
 * )
 */
class ContactLog extends Entity
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
        'when' => 1,
        'spoke_to' => 1,
        'details' => 1,
        'user_id' => 1,
        'incident_form_submission_id' => 1,
        'user' => 1,
        'incident_form_submission' => 1,
    ];
}