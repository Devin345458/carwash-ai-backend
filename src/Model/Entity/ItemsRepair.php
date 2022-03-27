<?php
declare(strict_types=1);
namespace App\Model\Entity;
use App\Classes\ActivityLoggableInterface;
use App\Model\Table\ItemsRepairsTable;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * ItemsRepair Entity
 *
 * @property int $id
 * @property int $repair_id
 * @property int $item_id
 * @property int $quantity
 *
 * @property Repair $repair
 * @property Item $item
 * @OA\Schema(title="ItemsRepair", description="Entity",
 * @OA\Property( type="integer", property="id", description="id"),
 * @OA\Property( type="integer", property="repair_id", description="repair_id"),
 * @OA\Property( type="integer", property="item_id", description="item_id"),
 * @OA\Property( type="integer", property="quantity", description="quantity"),
 * @OA\Property( type="object", property="repair", description="repair" , ref="#/components/schemas/Repair"),
 * @OA\Property( type="object", property="item", description="item" , ref="#/components/schemas/Item"),
 * )
 */
class ItemsRepair extends Entity implements ActivityLoggableInterface
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
        'repair_id' => 1,
        'item_id' => 1,
        'quantity' => 1,
        'repair' => 1,
        'item' => 1,
    ];

    public function getMessage($user, string $action): string
    {
        /** @var ItemsRepairsTable $itemsRepairsTable */
        $itemsRepairsTable = TableRegistry::getTableLocator()->get('ItemsRepairs');
        $itemsRepairsTable->loadInto($this, ['Items']);
        switch ($action) {
            case 'created':
                return $user->full_name . ' added ' . $this->quantity . ' ' . $this->item->name . ' to the repair';
            case 'updated':
                return $user->full_name . ' changed the quantity used of ' . $this->item->name . ' to ' . $this->quantity;
            case 'deleted':
                return $user->full_name . ' removed ' . $this->item->name;
            default:
                return 'No Details';
        }

    }
}
