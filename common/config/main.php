<?php

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Asia/Ho_Chi_Minh',
    'bootstrap' => ['log'],
    'components' => [
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://45.77.253.47:27017/giataivuon',
            'options' => [
                "username" => "giataivuon",
                "password" => "gtv.c0m"
            ]
        ],
        'log' => [
            'flushInterval' => 1,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'exportInterval' => 1,
                ],
            ],
        ],
        'sendmail' => [
            'class' => 'common\components\SendMail',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'session' => [
            'name' => '_giataivuon',
            'class' => 'yii\mongodb\Session',
            'sessionCollection' => 'session',
            'timeout' => 2 * 24 * 60 * 60,
            'cookieParams' => [
                'path' => '/',
                'domain' => ".vinagex.com",
            ],
        ],
        's3' => [
            'class' => 'common\components\S3',
            'key' => 'AKIAIM7TZ36UCAARNVDQ',
            'secret' => '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',
            'bucket' => 'giataivuon',
        ],
        'setting' => [
            'class' => 'common\components\SettingComponent'
        ],
    ],
];
