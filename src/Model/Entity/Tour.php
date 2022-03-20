<?php
namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Tour Entity
 *
 * @property int $id
 * @property string $Users_id
 * @property string $name
 * @property FrozenTime $created
 *
 * @property Myuser $myuser
 */
class Tour extends Entity
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
        'Users_id' => true,
        'name' => true,
        'created' => true,
        'myuser' => true,
    ];
}
