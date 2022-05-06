<?php
declare(strict_types=1);
namespace App\Model\Entity;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * EquipmentGroup Entity
 *
 * @property int $id
 * @property string $name
 * @property FrozenTime $created_at
 * @property FrozenTime|null $updated_at
 * @property Equipment[] $equipments
 * @OA\Schema(title="EquipmentGroup", description="Entity",
 * @OA\Property( type="integer", property="id", description="id"),
 * @OA\Property( type="string", property="name", description="name"),
 * @OA\Property( type="timestamp", property="created_at", description="created_at"),
 * @OA\Property( type="timestamp", property="updated_at", description="updated_at"),
 * )
 */
class EquipmentGroup extends Entity
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
        'store_id' => 1,
        'equipments' => 1,
        'created_at' => 1,
        'updated_at' => 1,
    ];
}
