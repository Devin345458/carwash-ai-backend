<?php
declare(strict_types=1);
namespace App\Model\Entity;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * IncidentForm Entity
 *
 * @property int $id
 * @property string $name
 * @property int $version
 * @property string $store_id
 * @property FrozenTime $created
 * @property FrozenTime $updated
 *
 * @property Store $store
 * @property IncidentFormVersion[] $incident_form_versions
 * @property IncidentFormSubmission $current_version
 * @OA\Schema(title="IncidentForm", description="Entity",
 * @OA\Property( type="integer", property="id", description="id"),
 * @OA\Property( type="string", property="name", description="name"),
 * @OA\Property( type="integer", property="version", description="version"),
 * @OA\Property( type="string", property="store_id", description="store_id"),
 * @OA\Property( type="timestamp", property="created", description="created"),
 * @OA\Property( type="timestamp", property="updated", description="updated"),
 * @OA\Property( type="object", property="store", description="store" , ref="#/components/schemas/Store"),
 * @OA\Property( type="array", property="incident_form_versions", description="incident_form_versions" , @OA\Items( ref="#/components/schemas/IncidentFormVersion")),
 * )
 */
class IncidentForm extends Entity
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
        'name' => 1,
        'version' => 1,
        'store_id' => 1,
        'created' => 1,
        'updated' => 1,
        'store' => 1,
        'incident_form_versions' => 1,
        'current_version' => 1,
    ];
}
