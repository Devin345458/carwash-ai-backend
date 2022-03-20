<?php
namespace CarWashAI\Rules;

use Cake\Network\Request;
use Cake\Utility\Hash;
use CakeDC\Auth\Rbac\Rules\AbstractRule;
use Psr\Http\Message\ServerRequestInterface;

class Store extends AbstractRule
{
    /**
     * Store constructor.
     *
     * @param bool $dashboard
     */
    private $dashboard;

    function __construct($dashboard = false)
    {
        parent::__construct();
        $this->dashboard = $dashboard;
    }

    /**
     * Check the current entity is owned by the logged in user
     *
     * @param  array                  $user    Auth array with the logged in data
     * @param  string                 $role    role of the user
     * @param  ServerRequestInterface $request current request, used to get a default table if not provided
     * @return bool
     */
    public function allowed(array $user, $role, ServerRequestInterface $request)
    {
        $storeId = $request->getParam('pass.0') ? $request->getParam('pass.0') : $request->getData('store_id');
        if ($storeId === 'Dashboard') {
            return true;
        }
        $store = $this->_getTable($request, 'Stores')->get(
            $storeId,
            [
            'contain' => [
                'Users',
            ],
            ]
        );
        $userId = Hash::get($user, 'id');
        foreach ($store->Users as $storeuser) {
            if ($storeuser->id === $userId) {
                return true;
            }
        }

        return false;
    }
}
