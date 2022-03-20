<?php
namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Location Entity
 *
 * @property int $id
 * @property int $store_id
 * @property string $name
 * @property string $description
 * @property bool $default_location
 * @property FrozenTime $created
 * @property FrozenTime $modified
 * @property string $created_by
 * @property string $modified_by
 *
 * @property Store $store
 * @property Equipment[] $equipments
 */
class Location extends Entity
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
        'store_id' => true,
        'name' => true,
        'description' => true,
        'default_location' => true,
        'created' => true,
        'modified' => true,
        'created_by' => true,
        'modified_by' => true,
        'store' => true,
        'equipments' => true,
    ];
}
