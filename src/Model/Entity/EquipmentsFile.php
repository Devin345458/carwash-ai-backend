<?php
declare(strict_types=1);
namespace App\Model\Entity;
use Cake\ORM\Entity;

/**
 * EquipmentsFile Entity
 *
 * @property int $id
 * @property int $equipment_id
 * @property int $file_id
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime|null $updated_at
 *
 * @property \App\Model\Entity\Equipment $equipment
 * @property \App\Model\Entity\File $file
 * @OA\Schema(title="EquipmentsFile", description="Entity",
 * @OA\Property( type="integer", property="id", description="id"),
 * @OA\Property( type="integer", property="equipment_id", description="equipment_id"),
 * @OA\Property( type="integer", property="file_id", description="file_id"),
 * @OA\Property( type="timestamp", property="created_at", description="created_at"),
 * @OA\Property( type="timestamp", property="updated_at", description="updated_at"),
 * @OA\Property( type="object", property="equipment", description="equipment" , ref="#/components/schemas/Equipment"),
 * @OA\Property( type="object", property="file", description="file" , ref="#/components/schemas/File"),
 * )
 */
class EquipmentsFile extends Entity
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
        'equipment_id' => 1,
        'file_id' => 1,
        'created_at' => 1,
        'updated_at' => 1,
        'equipment' => 1,
        'file' => 1,
    ];


    public function getMessage($user, string $action): string
    {
        switch ($action) {
            case 'created':
                return $user->full_name . ' added a file ' . $this->file->name;
            case 'deleted':
                return $user->full_name . ' deleted a file ' . $this->file->name;
            default:
                return 'No Details';
        }
    }
}
