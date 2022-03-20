<?php
namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Company Entity
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $city
 * @property string $state
 * @property int $zip
 * @property string $country
 * @property string $email
 * @property string $billing_last_name
 * @property string $billing_first_name
 * @property int $chargebee_customer_id
 * @property bool $allow_car_count
 * @property FrozenTime $created
 * @property FrozenTime $modified
 * @property string $created_by
 * @property string $modified_by
 * @property Item[] $items
 * @property Store[] $stores
 * @property User[] $users
 */
class Company extends Entity
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
        'chargebee_customer_id' => true,
        'created' => true,
        'modified' => true,
        'address' => true,
        'city' => true,
        'state' => true,
        'zipcode' => true,
        'country' => true,
        'email' => true,
        'billing_last_name' => true,
        'billing_first_name' => true,
        'stores' => true,
        'items' => true,
        'users' => true,
    ];
}
