<?php

use CarWashAI\Rules\Store;

return [
    // Allow all users to change to stores that they belong to
    [
        'role' => '*',
        'prefix' => 'api',
        'controller' => 'Stores',
        'action' => ['setStore'],
        'allow' => new Store(),
    ],
    [
        'role' => '*',
        'prefix' => 'api',
        'controller' => 'Stores',
        'action' => ['getUsersStores'],

    ],
    [
        'role' => ['owner', 'manager'],
        'prefix' => 'api',
        'controller' => ['Stores'],
        'action' => ['settings', 'saveSettings'],
    ],
    [
        'role' => 'owner',
        'prefix' => 'api',
        'controller' => ['Stores'],
        'action' => ['add', 'delete', 'reactivate', 'cancelSubscription'],
    ],

];
