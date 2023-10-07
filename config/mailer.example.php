<?php
return
[
    'class' => 'yii\symfonymailer\Mailer',
    'useFileTransport' => true,         //set false to real mailing
    'transport' => [
        'scheme' => 'smtps',
        'host' => 'smtp.mail.com',      //set to real smtp server
        'username' => 'username',       //set to real username
        'password' => 'password',       //set to real password
        'port' => 465,
        'dsn' => 'native://default',
    ],
];
