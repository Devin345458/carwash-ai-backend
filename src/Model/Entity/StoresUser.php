<?php
declare(strict_types=1);
namespace App\Model\Entity;
use Cake\ORM\Entity;

/**
 * StoresUser Entity
 *
 * @property int $id
 * @property int $store_id
 * @property string $user_id
 *
 * @property \App\Model\Entity\Store $store
 * @property \App\Model\Entity\User $user
 * @OA\Schema(title="StoresUser", description="Entity",
 * @OA\Property( type="integer", property="id", description="id"),
 * @OA\Property( type="integer", property="store_id", description="store_id"),
 * @OA\Property( type="string", property="user_id", description="user_id"),
 * @OA\Property( type="object", property="store", description="store" , ref="#/components/schemas/Store"),
 * @OA\Property( type="object", property="user", description="user" , ref="#/components/schemas/User"),
 * )
 */
class StoresUser extends Entity
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
        'store_id' => true,
        'user_id' => true,
        'store' => true,
        'user' => true,
    ];
}