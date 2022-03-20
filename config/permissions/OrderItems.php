<?php
return [
    [
        'role' => '*',
        'prefix' => 'api',
        'controller' => 'OrderItems',
        'action' => [
            'getActiveStoresExpectedDeliveries',
            'dashboardWidget',
            'updateOrderitemStatus',
            'getActiveStoresPurchasesInRoute',
            'getActiveStoresPurchaseHistory',
            'getActiveStoresExpectedDeliveries',
            'receiveOrdertime',
        ],
    ],
    [
        'role' => ['owner', 'manager'],
        'prefix' => 'api',
        'controller' => ['OrderItems'],
        'action' => [
            'updateStatus',
            'approvedOrderItems',
            'markOrdered',
            'purchaseOrderItems',
            'markReceived',
            'historyOrderItems',
            'getItemCounts',
        ],
    ],
];
