<?php
declare(strict_types=1);
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MaintenanceSessionsMaintenance Entity
 *
 * @property int $id
 * @property int $maintenance_id
 * @property int $maintenance_session_id
 * @property int $status
 *
 * @property Maintenance $maintenance
 * @property MaintenanceSession $maintenance_session
 * @OA\Schema(title="MaintenanceSessionsMaintenance", description="Entity",
 * @OA\Property( type="integer", property="id", description="id"),
 * @OA\Property( type="integer", property="maintenance_id", description="maintenance_id"),
 * @OA\Property( type="integer", property="maintenance_session_id", description="maintenance_session_id"),
 * @OA\Property( type="tinyinteger", property="status", description="status"),
 * @OA\Property( type="object", property="maintenance", description="maintenance" , ref="#/components/schemas/Maintenance"),
 * @OA\Property( type="object", property="maintenance_session", description="maintenance_session" , ref="#/components/schemas/MaintenanceSession"),
 * )
 */
class MaintenanceSessionsMaintenance extends Entity
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
        'maintenance_id' => true,
        'maintenance_session_id' => true,
        'status' => true,
        'maintenance' => true,
        'maintenance_session' => true,
    ];
}
