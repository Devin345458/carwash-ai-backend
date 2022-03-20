<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MyuserDevice Entity
 *
 * @property int $id
 * @property string $device_id
 * @property string $myuser_id
 *
 * @property \App\Model\Entity\Device $device
 * @property \App\Model\Entity\Myuser $myuser
 */
class MyuserDevice extends Entity
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
        'device_id' => true,
        'myuser_id' => true,
        'device' => true,
        'myuser' => true,
    ];
}
