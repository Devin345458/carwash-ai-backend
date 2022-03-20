<?php
namespace App\Model\Entity;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Item Entity
 *
 * @property int $id
 * @property string $name
 * @property int $item_type_id
 * @property string|null $description
 * @property int|null $company_id
 * @property FrozenTime|null $created
 * @property FrozenTime|null $modified
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property int|null $file_id
 *
 * @property ItemType $item_type
 * @property Company $company
 * @property File $photo
 * @property Inventory[] $inventories
 * @property Inventory $inventory
 */
class Item extends Entity
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
        'name' => true,
        'item_type_id' => true,
        'description' => true,
        'company_id' => true,
        'created' => true,
        'modified' => true,
        'created_by' => true,
        'modified_by' => true,
        'file_id' => true,
        'item_type' => true,
        'company' => true,
        'photo' => true,
        'inventories' => true,
        'active_store_inventory' => true,
    ];

    protected function _setName($name)
    {
        return ucwords(strtolower($name));
    }


    public function setStoreInventory($store_id) {
        if (!$this->get('inventories')) $this->set('inventory', null);
        $inventories = new Collection($this->get('inventories'));
        $this->set('inventory', $inventories->filter(function ($inventory) use ($store_id) {
            return $inventory->store_id = $store_id;
        })->first());
    }
}
