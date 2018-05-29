<?php

return [
    'settings' => [
        'displayErrorDetails' => true,
        'routerCacheFile' => false,//change on production to __DIR__ . '/../tmp/routerCache.php
        'logger' => [
            'name' => 'UltraStar',
            'level' => Monolog\Logger::DEBUG,
            'path' => __DIR__ . '/../logs/' . (new DateTime())->format('Y-m-d') . '-app.log',
        ],
    ],
];
