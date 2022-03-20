<?php
namespace App\Model\Entity;

use Cake\Collection\Collection;
use Cake\ORM\Entity;

/**
 * Inventory Entity
 *
 * @property int $id
 * @property int $item_id
 * @property int $store_id
 * @property int $supplier_id
 * @property float|null $cost
 * @property int $current_stock
 * @property int $initial_stock
 * @property int|null $desired_stock
 *
 * @property Item $item
 * @property Store $store
 * @property Supplier $supplier
 * @property InventoryTransaction[] $inventory_transactions
 * @property OrderItem[] $order_items
 */
class Inventory extends Entity
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

    protected function _getTotal()
    {
        return $this->get('current_stock') * $this->get('cost');
    }

    protected function _getLastUsedBy()
    {
        $inventory_transactions = $this->get('inventory_transactions');
        if ($inventory_transactions) {
            $inventory_transactions = new Collection($inventory_transactions);
            $inventory_transactions = $inventory_transactions->sortBy('created');

            return $inventory_transactions->first()['createdBy']['full_name'];
        }

        return 'Never Used';
    }

    protected $_virtual = ['total', 'lastUsedBy'];
}
