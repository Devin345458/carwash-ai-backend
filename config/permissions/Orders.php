<?php
return [
    [
        'role' => '*',
        'prefix' => 'api',
        'controller' => ['Orders'],
        'action' => ['add', 'getMyOrders'],
    ],
    [
        'role' => ['owner', 'manager'],
        'prefix' => 'api',
        'controller' => ['Orders'],
        'action' => ['getActiveStoresOrders', 'getActiveStoresApprovedOrders', 'getActiveStoresOrders', 'denyEntireOrderWithOrderitems', 'saveActiveStoresOrderitems', 'updateOrderStatus', 'approveEntireOrderWithOrderitems', 'getPendingOrders'],
    ],
];
