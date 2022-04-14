<?php
namespace App\Model\Entity;

use Cake\Collection\Collection;
use Cake\Log\Log;
use Cake\ORM\Entity;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\TableRegistry;
use Throwable;

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

    /**
     * @return float|int
     */
    protected function _getTotal()
    {
        return $this->get('current_stock') * $this->get('cost');
    }

    /**
     * @return string
     */
    protected function _getLastUsedBy(): string
    {
        try {
            /** @var null|InventoryTransaction $inventory_transaction */
            $inventory_transaction = TableRegistry::getTableLocator()->get('InventoryTransactions')->query()->orderDesc('InventoryTransactions.created')->where(['inventory_id' => $this->id])->first();
            if ($inventory_transaction) {
                return $inventory_transaction->created_by->full_name;
            }
        } catch (Throwable $exception) {
            Log::debug($exception->getMessage());
        }

        return 'Never Used';
    }

    protected $_virtual = ['total', 'lastUsedBy'];
}
