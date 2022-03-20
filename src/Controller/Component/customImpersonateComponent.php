<?php

/*
* To override impersonate compnet
* 30.04.2019
*/

//namespace CakeImpersonate\Controller\Component;
namespace App\Controller\Component;

use App\Model\Entity\Myuser;
use Cake\Controller\Component;
use CakeImpersonate\Controller\Component\ImpersonateComponent;

class CustomImpersonateComponent extends ImpersonateComponent
{
    protected $_defaultConfig = [
        'userModel' => 'Users',
        'finder' => 'all',
        'stayLoggedIn' => true,
    ];

    /**
     * Function impersonate
     *
     * @param  mixed $id ID of user to impersonate
     * @return bool
     * @throws \Exception If userModal is not loaded in the Controller
     */
    public function login($id)
    {

        if (!is_string($this->getSessionKey())) {
            throw new AuthSecurityException('You must configure the Impersonate.sessionKey in config/app.php when impersonating a user.');
        }
        if (!$this->isPosted()) {
            throw new AuthSecurityException('You can only call the login function with a request that is POST, PUT, or DELETE');
        }
        $userModel = $this->getConfig('userModal', 'Users');
        $this->getController()->loadModel($userModel);

        $finder = $this->getConfig('finder', 'all');
        /**
 * @var \Cake\ORM\Table $usersTable
*/
        $usersTable = $this->getController()->{$userModel};
        $userArray = $usersTable->find($finder)->where([$usersTable->getAlias() . '.id' => $id])->firstOrFail()->toArray();
        $originalAuth = $this->getController()->Auth->user();
        $this->getController()->Auth->setUser($userArray);
        $this->getController()->getRequest()->getSession()->write($this->getSessionKey(), $originalAuth);

        return true;
    }

    /**
     * Function logout
     *
     * To log out of impersonated account
     *
     * @return bool|string Normalized config `logoutRedirect`
     */
    public function logout()
    {
        if (!is_string($this->getSessionKey())) {
            return true;
        }
        if ($this->isImpersonated()) {
            $Auth = $this->getController()->getRequest()->getSession()->read($this->getSessionKey());
            $this->getController()->Auth->setUser($Auth);
            $this->getController()->getRequest()->getSession()->delete($this->getSessionKey());
            $stayLoggedIn = $this->getConfig('stayLoggedIn', true);
            if (!$stayLoggedIn) {
                return $this->getController()->Auth->logout();
            }
        }

        return true;
    }

    /**
     * Function isImpersonated
     *
     * To check if current Auth is being impersonated
     *
     * @return bool
     */
    // public function isImpersonated()
    // {
    //     if (!is_string($this->getSessionKey())) {
    //         return false;
    //     }

    //     return $this->getController()->getRequest()->getSession()->check($this->getSessionKey());

    // }
}
