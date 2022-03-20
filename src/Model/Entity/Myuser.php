<?php
namespace App\Model\Entity;

use Aws\S3\S3Client;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use CakeDC\Users\Model\Entity\SocialAccount;
use CakeDC\Users\Model\Entity\User;

/**
 * Myuser Entity
 *
 * @property string $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $password_confirm
 * @property string $first_name
 * @property string $last_name
 * @property string $token
 * @property FrozenTime $token_expires
 * @property string $api_token
 * @property FrozenTime $activation_date
 * @property string $secret
 * @property bool $secret_verified
 * @property FrozenTime $tos_date
 * @property bool $active
 * @property bool $is_superuser
 * @property string $role
 * @property int $company_id
 * @property FrozenTime $created
 * @property FrozenTime $modified
 * @property string $active_store
 * @property SocialAccount[] $social_accounts
 * @property Company $company
 * @property Store[] $stores
 */
class Myuser extends User
{
    protected function _getFullName()
    {
        return $this->get('first_name') . '  ' . $this->get('last_name');
    }

    /**
     * _getData
     *
     * Getter for the vars-column.
     *
     * @param  $data
     * @return mixed
     */
    protected function _getDashboard()
    {
        if ($this->get('active_store') === 'Dashboard') {
            return true;
        }

        return false;
    }

    protected function _getActiveStoreModel($active_store)
    {
        if ($active_store) {
            return $active_store;
        }

        return [
            'name' => 'Dashboard',
        ];
    }

    protected $_virtual = ['full_name', 'dashboard', 'store_ids'];
}
