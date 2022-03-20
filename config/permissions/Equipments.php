<?php

use Cake\Network\Request;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

return [
    [
        'role' => '*',
        'prefix' => 'api',
        'controller' => 'Equipments',
        'action' => ['getActiveStoresEquipment', 'getStoresEquipment'],
    ],
    // Allow all users to access equipment and view individual pieces
    [
        'role' => '*',
        'prefix' => 'api',
        'controller' => 'Equipments',
        'action' => ['view', 'equipmentActivities'],
        'allow' => function (array $user, $role, Request $request) {
            $equipmentID = $request->getParam('pass.0');
            $equipment = TableRegistry::getTableLocator()->get('Equipments')->get($equipmentID);
            $userStores = array_column(Hash::get($user, 'stores'), 'id');
            if (in_array($equipment->store_id, $userStores)) {
                return true;
            }

            return false;
        },
    ],
    [
        'role' => ['owner', 'manager'],
        'prefix' => 'api',
        'controller' => ['Equipments'],
        'action' => ['catalogue', 'add', 'delete', 'addMaintenances', 'addMany'],
    ],
];
