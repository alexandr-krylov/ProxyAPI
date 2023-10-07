<?php

$db = require __DIR__ . '/db.php';
$params = require __DIR__ . '/params.php';
$mailer = require __DIR__ . '/mailer.php';

return [
    'id' => 'myapp-console', 
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\commands',
    'components' => [
        'db' => $db,
        'mailer' => $mailer,
    ],
    'params' => $params,
];
