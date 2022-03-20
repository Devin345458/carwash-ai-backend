<?php
namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Tip Entity
 *
 * @property int $id
 * @property string $name
 * @property int $store_id
 * @property string $created_by
 * @property FrozenTime $created
 * @property FrozenTime $modified
 *
 * @property Store $store
 */
class Tip extends Entity
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
        'store_id' => true,
        'created_by' => true,
        'created' => true,
        'modified' => true,
        'store' => true,
    ];
}
