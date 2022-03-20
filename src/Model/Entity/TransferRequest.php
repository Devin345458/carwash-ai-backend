<?php
namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * TransferRequest Entity
 *
 * @property int $id
 * @property int $to_store_id
 * @property int $from_store_id
 * @property int $transfer_status_id
 * @property int approved_by_id
 * @property string denial_reason
 * @property string|null $modified_by
 * @property FrozenTime|null $requested
 * @property FrozenTime|null $modified
 * @property string $requested_by
 * @property int $order_item_id
 *
 * @property Store $to_store
 * @property Store $from_store
 * @property TransferStatus $transfer_status
 * @property OrderItem $order_item
 */
class TransferRequest extends Entity
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
        'to_store_id' => true,
        'from_store_id' => true,
        'transfer_status_id' => true,
        'approved_by_id' => true,
        'order_item_id' => true,
        'created' => true,
        'modified' => true,
        'modified_by_id' => true,
        'created_by_id' => true,
        'to_store' => true,
        'from_store' => true,
        'order_item' => true,
    ];
}
