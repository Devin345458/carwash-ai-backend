<?php
declare(strict_types=1);
namespace App\Model\Entity;
use Cake\ORM\Entity;

/**
 * IncidentFormVersion Entity
 *
 * @property int $id
 * @property int $incident_form_id
 * @property int $version
 * @property string $data
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\IncidentForm $incident_form
 * @property \App\Model\Entity\IncidentFormSubmission[] $incident_form_submissions
 * @OA\Schema(title="IncidentFormVersion", description="Entity",
 * @OA\Property( type="integer", property="id", description="id"),
 * @OA\Property( type="integer", property="incident_form_id", description="incident_form_id"),
 * @OA\Property( type="integer", property="version", description="version"),
 * @OA\Property( type="text", property="data", description="data"),
 * @OA\Property( type="timestamp", property="created", description="created"),
 * @OA\Property( type="object", property="incident_form", description="incident_form" , ref="#/components/schemas/IncidentForm"),
 * @OA\Property( type="array", property="incident_form_submissions", description="incident_form_submissions" , @OA\Items( ref="#/components/schemas/IncidentFormSubmission")),
 * )
 */
class IncidentFormVersion extends Entity
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
        'incident_form_id' => 1,
        'version' => 1,
        'data' => 1,
        'created' => 1,
        'incident_form' => 1,
        'incident_form_submissions' => 1,
    ];
}