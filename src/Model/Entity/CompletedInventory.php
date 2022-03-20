<?php
namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Completedinventory Entity
 *
 * @property int $id
 * @property string|null $user_id
 * @property int|null $time_to_complete
 * @property FrozenTime|null $created
 * @property FrozenTime|null $completed_date
 * @property int|null $store_id
 * @property int|null $item_count
 * @property int|null $item_skip_count
 *
 * @property Myuser $myuser
 * @property Store $store
 */
class CompletedInventory extends Entity
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
        'user_id' => true,
        'time_to_complete' => true,
        'created' => true,
        'completed_date' => true,
        'store_id' => true,
        'item_count' => true,
        'item_skip_count' => true,
        'created_by_id' => true,
        'modified_by_id' => true,
        'myuser' => true,
        'store' => true,
    ];
}
