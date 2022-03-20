<?php

use Cake\Network\Request;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

return [
    [
        'role' => '*',
        'prefix' => 'api',
        'controller' => ['Inventories'],
        'action' => [
            'add',
            'getActiveStoresInventorys',
            'getInventoryHistory',
            'saveActiveStoresInventorys',
            'getAllActiveStoresInventorys',
            'getInventoryList',
            'index',
            'search',
            'upsert',
        ],
    ],
    [
        'role' => '*',
        'prefix' => 'api',
        'controller' => ['Inventories'],
        'action' => ['view', 'edit'],
        'allow' => function (array $user, $role, Request $request) {
            $inventoryID = $request->getParam('pass.0');
            $inventory = TableRegistry::getTableLocator()->get('Inventories')->get($inventoryID);
            $userStores = array_column(Hash::get($user, 'stores'), 'id');
            if (in_array($inventory->store_id, $userStores)) {
                return true;
            }

            return false;
        },
    ],
    [
        'role' => ['owner', 'manager'],
        'prefix' => 'api',
        'controller' => ['Inventories'],
        'action' => 'getInventoryOrders',
        'allow' => function (array $user, $role, Request $request) {
            $inventoryID = $request->getParam('pass.0');
            $inventory = TableRegistry::getTableLocator()->get('Inventories')->get($inventoryID);
            $userStores = array_column(Hash::get($user, 'stores'), 'id');
            if (in_array($inventory->store_id, $userStores)) {
                return true;
            }

            return false;
        },
    ],
    [
        'role' => ['owner', 'manager'],
        'prefix' => 'api',
        'controller' => ['Inventories'],
        'action' => ['dashboardWidget', 'history'],
    ],

];
