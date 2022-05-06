<?php
declare(strict_types=1);
namespace App\Model\Entity;
use Cake\ORM\Entity;

/**
 * Recording Entity
 *
 * @property int $id
 * @property string $camera
 * @property string $start_time
 * @property string $end_time
 * @property string $incident_form_submission_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\IncidentFormSubmission $incident_form_submission
 * @OA\Schema(title="Recording", description="Entity",
 * @OA\Property( type="integer", property="id", description="id"),
 * @OA\Property( type="string", property="camera", description="camera"),
 * @OA\Property( type="string", property="start_time", description="start_time"),
 * @OA\Property( type="string", property="end_time", description="end_time"),
 * @OA\Property( type="string", property="incident_form_submission_id", description="incident_form_submission_id"),
 * @OA\Property( type="timestamp", property="created", description="created"),
 * @OA\Property( type="timestamp", property="modified", description="modified"),
 * @OA\Property( type="object", property="incident_form_submission", description="incident_form_submission" , ref="#/components/schemas/IncidentFormSubmission"),
 * )
 */
class Recording extends Entity
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
        'camera' => 1,
        'start_time' => 1,
        'end_time' => 1,
        'incident_form_submission_id' => 1,
        'created' => 1,
        'modified' => 1,
        'incident_form_submission' => 1,
    ];
}