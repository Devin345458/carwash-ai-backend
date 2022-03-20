<?php
return [
    // Allows all users to conduct maintenance
    [
        'role' => '*',
        'prefix' => 'api',
        'controller' => 'Maintenances',
        'action' => ['storesMaintenance', 'completeMaintenance'],
    ],
    [
        'role' => ['owner', 'manager'],
        'prefix' => 'api',
        'controller' => 'Maintenances',
        'action' => ['savePhoto', 'add', 'edit', 'delete', 'maintenanceCatalogue', 'getMaintenances', 'getMaintenance', 'dashboardWidget'],
    ],
];
