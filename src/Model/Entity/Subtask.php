<?php
namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Subtask Entity
 *
 * @property int $id
 * @property string $content
 * @property FrozenTime $created
 * @property FrozenTime $modified
 * @property int $repair_id
 * @property int $complete
 *
 * @property Repair $repair
 */
class Subtask extends Entity
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
        'content' => true,
        'created' => true,
        'modified' => true,
        'repair_id' => true,
        'created_by_id' => true,
        'assigned_to' => true,
        'complete' => true,
        'repair' => true,
        'myuser' => true,
    ];
}
