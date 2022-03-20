<?php

use Cake\Network\Request;

return [
    [
        'role' => '*',
        'controller' => 'Users',
        'action' => ['imitationLogout', 'activeUser', 'stripeKey', 'userActivities', 'storeUsers', 'pushNotification'],
    ],
    [
        'role' => ['owner', 'manager'],
        'prefix' => 'api',
        'controller' => ['Users'],
        'action' => ['add', 'removeStore'],
        'allow' => function (array $user, $role, Request $request) {
            $myuser = $request->getData();
            // Check that the user has the store they are assigning to
            foreach ($myuser['stores']['_ids'] as $id) {
                if (!in_array($id, array_column($user['stores'], 'id'))) {
                    return false;
                }
            }

            // If setting user return true;
            if ($myuser['role'] === 'user') {
                return true;
            }

            // Check that the user is an owner if assigning another role
            if ($role === 'owner') {
                return true;
            }

            return false;
        },
    ],
    [
        'role' => ['owner', 'manager'],
        'prefix' => 'api',
        'controller' => ['Users'],
        'action' => ['edit'],
    ],
];
