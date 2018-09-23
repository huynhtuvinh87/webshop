<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php'
);
return [
    'id' => 'app-transport',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'transport\controllers',
    'components' => [
        'transport' => [
            'class' => 'transport\models\Transport',
            'storageClass' => 'common\storage\SessionStorage',
            'calculatorClass' => 'common\components\Calculator',
            'params' => [
                'key' => 'transport',
                'expire' => 604800
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-transport',
            'cookieValidationKey' => 'ymoaYrebZHa8gURuolioHGlK8fLXCKjOe4transport',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['user/login'],
            'identityCookie' => ['name' => '_identity-transport', 'httpOnly' => true],
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
                '<controller:\w+>/<action:\w+>/<id:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => FALSE,
        ],
        'city' => [
            'class' => 'common\components\City',
        ],
        'helper' => [
            'class' => 'message\components\Helper',
        ],
    ],
    'params' => $params,
];
