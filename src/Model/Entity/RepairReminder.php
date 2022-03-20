<?php
namespace App\Model\Entity;

use Cake\I18n\Date;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * RepairReminder Entity
 *
 * @property int $id
 * @property FrozenTime $reminder
 * @property int $repair_id
 * @property string $user_id
 * @property bool|null $sent
 *
 * @property Repair $repair
 * @property User $user
 */
class RepairReminder extends Entity
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
        'reminder' => true,
        'repair_id' => true,
        'user_id' => true,
        'sent' => true,
        'repair' => true,
        'user' => true,
    ];
}
