<?php
namespace App\Model\Entity;

use Cake\I18n\Date;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Carcount Entity
 *
 * @property int $id
 * @property int $carcount
 * @property int $store_id
 * @property FrozenTime $created
 * @property FrozenTime $modified
 * @property string $myuser_id
 * @property FrozenDate  date_of_cars
 *
 * @property Store $store
 * @property Myuser $myuser
 */
class CarCount extends Entity
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
        'carcount' => true,
        'store_id' => true,
        'created' => true,
        'modified' => true,
        'myuser_id' => true,
        'date_of_cars' => true,
        'store' => true,
        'myuser' => true,
    ];
}
