<?php
declare(strict_types=1);
namespace App\Model\Entity;

use App\Model\Table\UsersTable;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * MaintenanceSession Entity
 *
 * @property int $id
 * @property FrozenTime $start_time
 * @property FrozenTime|null $end_time
 * @property string $created_by_id
 * @property string $modified_by
 * @property string $store_id
 *
 * @property UsersTable $created_by
 * @property Store $store
 * @property Maintenance[] $maintenances
 * @OA\Schema(title="MaintenanceSession", description="Entity",
 * @OA\Property( type="integer", property="id", description="id"),
 * @OA\Property( type="timestamp", property="start_time", description="start_time"),
 * @OA\Property( type="timestamp", property="end_time", description="end_time"),
 * @OA\Property( type="string", property="created_by_id", description="created_by_id"),
 * @OA\Property( type="string", property="modified_by", description="modified_by"),
 * @OA\Property( type="string", property="store_id", description="store_id"),
 * @OA\Property( type="object", property="created_by", description="created_by" , ref="#/components/schemas/CreatedBy"),
 * @OA\Property( type="object", property="store", description="store" , ref="#/components/schemas/Store"),
 * @OA\Property( type="array", property="maintenances", description="maintenances" , @OA\Items( ref="#/components/schemas/Maintenance")),
 * )
 */
class MaintenanceSession extends Entity
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
        'start_time' => true,
        'end_time' => true,
        'created_by_id' => true,
        'modified_by' => true,
        'store_id' => true,
        'created_by' => true,
        'store' => true,
        'maintenances' => true,
    ];
}
