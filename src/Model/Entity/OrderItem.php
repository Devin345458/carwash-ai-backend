<?php
namespace App\Model\Entity;

use App\Model\Entity\Traits\UploadTrait;
use Aws\S3\S3Client;
use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Cake\Log\Log;
use Cake\ORM\Entity;

/**
 * Orderitem Entity
 *
 * @property int $id
 * @property int $order_id
 * @property int $inventory_id
 * @property int $quantity
 * @property int|null $received_by
 * @property string|null $receiving_comment
 * @property string|null $location
 * @property array|null $transfer_requests
 * @property int $order_item_status_id
 * @property TransferRequest|null $active_transfer
 * @property FrozenTime|null expected_delivery_date
 * @property FrozenTime|null actual_delivery_date
 * @property string|null trackingnumber
 * @property string|null purchase_cost
 * @property Inventory $inventory
 */
class OrderItem extends Entity
{
    use UploadTrait;

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
        'id' => true,
        'quantity' => true,
        'order_item_status_id' => true,
        'store_id' => true,
        'method' => true,
        'inventory_id' => true,
        'created_by' => true,
        'modified_by' => true,
        'created_by_id' => true,
        'modified_by_id' => true,
        'received_by' => true,
        'receiving_comment' => true,
        'shippingslip' => true,
        'location' => true,
        'expected_delivery_date' => true,
        'actual_delivery_date' => true,
        'trackingnumber' => true,
        'purchase_cost' => true,
        'transfer_requests' => true,
    ];

    protected function _getPurchaseCost($cost)
    {
        return $cost ? $cost : '';
    }

    protected $_virtual = ['S3url', 'Thumbnail'];
}
