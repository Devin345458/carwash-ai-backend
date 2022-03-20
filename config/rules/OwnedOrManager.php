<?php


namespace CarWashAI\Rules;

use Cake\Network\Request;
use Cake\Utility\Hash;
use CakeDC\Auth\Rbac\Rules\AbstractRule;
use Psr\Http\Message\ServerRequestInterface;

class OwnedOrManager extends AbstractRule
{
    /**
     * Check the current entity is owned by the logged in user
     *
     * @param  array   $user    Auth array with the logged in data
     * @param  string  $role    role of the user
     * @param  Request $request current request, used to get a default table if not provided
     * @return bool
     */
    public function allowed(array $user, $role, ServerRequestInterface $request)
    {
        $id = $request->getParam('pass.0') | $request->getData('id');
        $entity = $this->_getTable($request)->get($id);
        $userId = Hash::get($user, 'id');
        if ($entity->created_by === $userId || (in_array($entity->store_id, array_column($user['stores'], 'id')) && $role !== 'user')) {
            return true;
        }
    }
}
