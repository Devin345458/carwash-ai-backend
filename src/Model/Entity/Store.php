<?php
namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Store Entity
 *
 * @property int $id
 * @property string $name
 * @property int $number
 * @property FrozenTime $created
 * @property FrozenTime $modified
 * @property $photo
 * @property string $dir
 * @property string $type
 * @property int $size
 * @property int $company_id
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $country
 * @property int $zipcode
 * @property string $subscription_id
 * @property bool $canceled
 * @property FrozenTime $cancel_date
 * @property string $cancel_reason
 * @property int setup_id
 * @property int plan_id
 * @property int store_type_id
 * @property int current_car_count
 * @property bool allow_car_counts
 * @property int maintenance_due_days_offset
 * @property int maintenance_due_cars_offset
 * @property int upcoming_days_offset
 * @property int upcoming_cars_offset
 * @property int time_zone
 * @property bool require_scan
 *
 * @property Company $company
 * @property Repair[] $repairs
 * @property Myuser[] $users
 * @property Equipment[] $equipments
 * @property Inventory[] $inventories
 * @property CarCount[] $car_counts
 * @property Location[] $locations
 */
class Store extends Entity
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
        'name' => true,
        'number' => true,
        'created' => true,
        'modified' => true,
        'file_id' => true,
        'company_id' => true,
        'plan_id' => true,
        'setup_id' => true,
        'company' => true,
        'repairs' => true,
        'address' => true,
        'city' => true,
        'state' => true,
        'country' => true,
        'zipcode' => true,
        'subscription_id' => true,
        'cancel_date' => true,
        'canceled' => true,
        'cancel_reason' => true,
        'users' => true,
        'equipments' => true,
        'store_setting' => true,
        'store_type_id' => true,
        'allow_car_counts' => true,
        'maintenance_due_days_offset' => true,
        'maintenance_due_cars_offset' => true,
        'upcoming_days_offset' => true,
        'upcoming_cars_offset' => true,
        'time_zone' => true,
        'require_scan' => true,
    ];
}
