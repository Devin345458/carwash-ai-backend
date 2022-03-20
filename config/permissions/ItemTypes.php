<?php

use Cake\Network\Request;
use Cake\ORM\TableRegistry;

return [
    [
        'role' => ['owner'],
        'prefix' => 'api',
        'controller' => ['ItemTypes'],
        'action' => ['add', 'index'],
    ],
    [
        'role' => ['owner'],
        'prefix' => 'api',
        'controller' => ['ItemTypes'],
        'action' => ['edit', 'delete'],
        'allow' => function (array $user, $role, Request $request) {
            $itemtype_id = $request->getParam('pass.0');
            $itemtype = TableRegistry::getTableLocator()->get('ItemTypes')->get($itemtype_id);

            return $user['company_id'] ===  $itemtype->company_id;
        },
    ],
];
