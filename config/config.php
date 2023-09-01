<?php

$db = require __DIR__ . '/db.php';

return [
    'id' => 'app',
    'basePath' => __DIR__ . '/../',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\controllers',
    'aliases' => [
        '@app' => __DIR__ . '/../',
    ],
    'components' => [
        'db' => $db,
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'POST api/0/token' => 'token/create',
                'GET api/0/token' => 'token/index',
                'POST api/0/wallet' => 'wallet/create',
                'GET api/0/wallet' => 'wallet/view',
                'POST api/0/transaction' => 'transaction/create',
                'GET api/0/transaction' => 'transaction/view',
                'POST api/0/order' => 'order/create',
            ],
        ],
        'log' => [
            'targets' => [
                'file' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['trace'],
                ],
            ],
        ],
    ],
    'params' => [
        'DOMUrl' => 'http://depthofmarket_app_1:8000/api/0/',
        'mainCurrency' => 'KZT',
    ],
];
