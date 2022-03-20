<?php
return [
    [
        'role' => '*',
        'prefix' => 'api',
        'controller' => 'Subtasks',
        'action' => ['add', 'edit', 'complete', 'uncomplete', 'delete'],
        'allowed' => true,
    ],
];
