<?php

$config = [
    'Users' => [
        'controller' => 'Users',
        'table' => 'Users',
        'Email' => [
            'required' => true,
            'validate' => false
        ],
        'Token' => ['expiration' => 3600],
        'Registration' => [
            'active' => true,
            'reCaptcha' => false,
            'allowLoggedIn' => false,
            'ensureActive' => true,
            'defaultRole' => 'owner',
        ],
        'Tos' => [
            'required' => false,
        ],
    ],
    'Auth' => [
        'loginAction' => 'false',
        'authenticate' => [
            'all' => [
                'finder' => 'auth',
            ],
            'ADmad/JwtAuth.Jwt' => [
                'parameter' => 'token',
                'userModel' => 'Users',
                'finder' => 'Auth',
                'fields' => [
                    'username' => 'id',
                ],
                'queryDatasource' => true,
            ],
        ],
        'authorize' => [
            'c/Auth.Superuser',
            'CakeDC/Auth.SimpleRbac',
        ],
        'storage' => 'Memory',
        'unauthorizedRedirect' => false,
        'checkAuthIn' => 'Controller.initialize',
    ],
];

return $config;
