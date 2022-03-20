<?php
$config = [
    'Queue' => [
        'sleeptime' => 10,
        'gcprob' => 10,
        'defaultworkertimeout' => 1800,
        'defaultworkerretries' => 3,
        'workermaxruntime' => 900,
        'exitwhennothingtodo' => false,
        'cleanuptimeout' => 2592000,
        'maxworkers' => 3,
        'multiserver' => true,
    ],
];

return $config;
