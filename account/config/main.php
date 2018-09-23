<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php',
        require __DIR__ . '/params.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'account\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['user/login'],
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is
            //required by cookie validation
            'csrfParam' => '_csrf-account',
            'cookieValidationKey' => '5GR63NLLzWqbOnHNbKbuZSnqGuNb7diD',
//            'csrfCookie' => [
//                'httpOnly' => true,
//                'path' => '/admin',
//            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login' => 'site/login',
                '<controller:\w+>/<action:\w+>/<id:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@frontend/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => FALSE,
        ],
        'city' => [
            'class' => 'common\components\City',
        ],
    ],
    'params' => $params,
];
