<?php

$db = require __DIR__ . '/db.php';
$params = require __DIR__ . '/params.php';

return [
    'id' => 'myapp-console', 
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\commands',
    'components' => [
        'db' => $db,
    ],
    'params' => $params,
];
