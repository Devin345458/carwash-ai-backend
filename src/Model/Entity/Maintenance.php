<?php
namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Maintenance Entity
 *
 * @property int $id
 * @property string $method
 * @property string $name
 * @property string $time
 * @property int $store_id
 * @property int $frequency_years
 * @property int $frequency_months
 * @property int $frequency_days
 * @property int $frequency_car
 * @property int $created
 * @property int $modified
 * @property int $equipment_id
 * @property string $photo
 * @property int $type
 * @property int last_cars_completed
 * @property FrozenTime last_completed_date
 * @property int $size
 * @property array $procedures
 * @property Object _joinData
 *
 * @property   Equipment $equipment
 * @property   Item[] $items
 * @property   Item[] $parts
 * @property   Item[] $tools
 * @property   Item[] $consumables
 * @property   Tip[] $tips
 */
class Maintenance extends Entity
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
}
