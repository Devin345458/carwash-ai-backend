<?php
namespace App\Model\Entity;

use App\Classes\ActivityLoggableInterface;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Equipment Entity
 *
 * @property int $id
 * @property string $name
 * @property int $carcount
 * @property FrozenTime $lastmaintenance
 * @property int $weekday
 * @property int $position
 * @property int $store_id
 * @property FrozenTime $created
 * @property FrozenTime $modified
 * @property string $created_by_id
 * @property string $modified_by_id
 * @property int $location_id
 * @property int $created_from_id
 * @property FrozenDate purchase_date
 * @property FrozenDate install_date
 * @property string installer
 * @property FrozenDate warranty_expiration
 * @property string model_number
 *
 * @property File $file
 * @property Store $store
 * @property Myuser $created_by
 * @property Myuser $modified_by
 * @property Location $location
 * @property Category[] $categories
 * @property Maintenance[] $maintenances
 * @property Repair[] $repairs
 * @property File[] $files
 */

class Equipment extends Entity implements ActivityLoggableInterface
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
