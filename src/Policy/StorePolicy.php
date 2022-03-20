<?php


namespace App\Policy;


use App\Model\Entity\Store;
use Authorization\IdentityInterface;
use Cake\ORM\Locator\LocatorAwareTrait;

class StorePolicy
{
    use LocatorAwareTrait;

    /**
     * Checks to see if a user has a store
     */
    public function canView(IdentityInterface $user, Store $store) {
        $storeUsersTable = $this->getTableLocator()->get('StoresUsers');
        $store_user = $storeUsersTable->find()->where([
            'user_id' => $user->id,
            'store_id' => $store->id,
        ])->first();
        if ($store_user) return true;
        return false;
    }
}
