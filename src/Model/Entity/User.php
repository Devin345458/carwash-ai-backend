<?php
declare(strict_types=1);
namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property string $id
 * @property string $username
 * @property string|null $email
 * @property string $password
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $token
 * @property FrozenTime|null $token_expires
 * @property string|null $api_token
 * @property FrozenTime|null $activation_date
 * @property string|null $secret
 * @property bool|null $secret_verified
 * @property FrozenTime|null $tos_date
 * @property bool $active
 * @property bool $is_superuser
 * @property string|null $role
 * @property int $company_id
 * @property string|null $active_store
 * @property FrozenTime $created
 * @property FrozenTime $modified
 * @property int|null $file_id
 * @property bool $intro
 * @property string|null $about
 * @property string|null $time_zone
 *
 * @property Company $company
 * @property File $photo
 * @property Notification[] $notifications
 * @property Store[] $stores
 * @OA\Schema(title="User", description="Entity",
 * @OA\Property( type="string", property="id", description="id"),
 * @OA\Property( type="string", property="username", description="username"),
 * @OA\Property( type="string", property="email", description="email"),
 * @OA\Property( type="string", property="password", description="password"),
 * @OA\Property( type="string", property="first_name", description="first_name"),
 * @OA\Property( type="string", property="last_name", description="last_name"),
 * @OA\Property( type="string", property="token", description="token"),
 * @OA\Property( type="datetime", property="token_expires", description="token_expires"),
 * @OA\Property( type="string", property="api_token", description="api_token"),
 * @OA\Property( type="datetime", property="activation_date", description="activation_date"),
 * @OA\Property( type="string", property="secret", description="secret"),
 * @OA\Property( type="boolean", property="secret_verified", description="secret_verified"),
 * @OA\Property( type="datetime", property="tos_date", description="tos_date"),
 * @OA\Property( type="boolean", property="active", description="active"),
 * @OA\Property( type="boolean", property="is_superuser", description="is_superuser"),
 * @OA\Property( type="string", property="role", description="role"),
 * @OA\Property( type="integer", property="company_id", description="company_id"),
 * @OA\Property( type="string", property="active_store", description="active_store"),
 * @OA\Property( type="datetime", property="created", description="created"),
 * @OA\Property( type="datetime", property="modified", description="modified"),
 * @OA\Property( type="integer", property="file_id", description="file_id"),
 * @OA\Property( type="boolean", property="intro", description="intro"),
 * @OA\Property( type="string", property="about", description="about"),
 * @OA\Property( type="string", property="time_zone", description="time_zone"),
 * @OA\Property( type="object", property="company", description="company" , ref="#/components/schemas/Company"),
 * @OA\Property( type="object", property="photo", description="photo" , ref="#/components/schemas/Photo"),
 * @OA\Property( type="array", property="notifications", description="notifications" , @OA\Items( ref="#/components/schemas/Notification")),
 * @OA\Property( type="array", property="users_devices", description="users_devices" , @OA\Items( ref="#/components/schemas/UsersDevice")),
 * )
 */
class User extends Entity
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
        'username' => true,
        'email' => true,
        'password' => true,
        'first_name' => true,
        'last_name' => true,
        'token' => true,
        'token_expires' => true,
        'api_token' => true,
        'activation_date' => true,
        'secret' => true,
        'secret_verified' => true,
        'tos_date' => true,
        'active' => true,
        'is_superuser' => true,
        'role' => true,
        'company_id' => true,
        'active_store' => true,
        'created' => true,
        'modified' => true,
        'file_id' => true,
        'about' => true,
        'time_zone' => true,
        'company' => true,
        'file' => true,
        'notifications' => true,
        'users_devices' => true,
        'stores' => true,
    ];
    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
        'token',
    ];

    protected function _setPassword(string $password)
    {
        $hasher = new DefaultPasswordHasher();

        return $hasher->hash($password);
    }

    public function _getFullName()
    {
        return $this->get('first_name') . ' ' . $this->get('last_name');
    }

    protected $_virtual = ['full_name'];
}
