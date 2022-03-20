<?php
namespace App\Model\Entity;

use Cake\I18n\Time;
use Cake\ORM\Entity;

/**
 * ActivityLog Entity
 *
 * @property int $id
 * @property Time $created_at
 * @property string $scope_model
 * @property string $scope_id
 * @property string|null $issuer_model
 * @property string|null $issuer_id
 * @property string|null $object_model
 * @property string|null $object_id
 * @property string $level
 * @property string|null $action
 * @property string|null $message
 * @property string|null $data
 *
 * @property Object $object
 */
class ActivityLog extends Entity
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
        'created_at' => true,
        'scope_model' => true,
        'scope_id' => true,
        'issuer_model' => true,
        'issuer_id' => true,
        'object_model' => true,
        'object_id' => true,
        'level' => true,
        'action' => true,
        'message' => true,
        'data' => true,
        'scope' => true,
        'issuer' => true,
        'object' => true,
    ];
}
