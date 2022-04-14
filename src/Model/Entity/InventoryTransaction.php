<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * InventoryTransaction Entity
 *
 * @property int $id
 * @property int|null $quantity
 * @property int $difference
 * @property int $inventory_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property string|null $created_by_id
 * @property string|null $modified_by_id
 * @property int|null $transaction_action_id
 * @property User|null $created_by
 * @property User|null $modified_by
 *
 * @property \App\Model\Entity\TransactionAction $transaction_action
 */
class InventoryTransaction extends Entity
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
        'quantity' => true,
        'difference' => true,
        'created' => true,
        'created_by' => true,
        'transaction_action_id' => true,
        'transaction_action' => true,
        'model' => true,
        'foreign_key' => true,
        'inventory_id' => true,
    ];
}
