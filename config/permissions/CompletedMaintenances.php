<?php

use CarWashAI\Rules\Store;

return [
    [
        'role' => '*',
        'prefix' => 'api',
        'controller' => 'CompletedMaintenances',
        'action' => ['getRecentMaintenance'],
        'allow' => new Store(),
    ],
    [
        'role' => ['owner', 'manager'],
        'prefix' => 'api',
        'controller' => ['CompletedMaintenances'],
        'action' => ['dashboardWidget', 'history'],
    ],
];
