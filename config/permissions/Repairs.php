<?php

use CarWashAI\Rules\OwnedOrManager;
use CarWashAI\Rules\Store;

// Repair Permission
// 2. Anyone can add a repair to any store they belong to
// 3. Users can only edit repairs they created or assigned.  
//4. Users can only complete repairs they are assigned
//5. Users can only assign themselves to repair they created.
//6. Users can only complete subtasks of repairs they created or are assigned to
//6. Managers and Owners can do everything

return [
    [
        'role' => '*',
        'prefix' => 'api',
        'controller' => 'Repairs',
        'action' => ['updateField', 'completeRepair', 'addItem', 'edit', 'delete', 'index', 'view', 'uploadImage', 'dashboardWidget'],
        'allowed' => true,
    ],
    [
        'role' => '*',
        'prefix' => 'api',
        'controller' => ['Repairs'],
        'action' => ['add'],
        'allow' => new Store(),
    ],
    [
        'role' => '*',
        'prefix' => 'api',
        'controller' => ['Repairs'],
        'action' => ['delete'],
        'allow' => new OwnedOrManager(),
    ],
    [
        'role' => '*',
        'prefix' => 'api',
        'controller' => ['RepairReminders'],
        'action' => ['add'],
    ],

];
