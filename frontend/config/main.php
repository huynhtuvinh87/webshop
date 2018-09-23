<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'province' => [
            'class' => 'frontend\storage\ProvinceStorage',
        ],
        'cart' => [
            'class' => 'common\components\Cart',
            'storageClass' => 'common\storage\DbSessionStorage',
            'calculatorClass' => 'common\components\Calculator',
            'params' => [
                'key' => 'cart',
                'expire' => 604800,
                'productClass' => 'common\models\Product'
            ],
        ],
        'seller' => [
            'class' => 'frontend\storage\SellerItem',
            'userClass' => 'common\models\User'
        ],
        'constant' => 'common\components\Constant',
        's3' => [
            'class' => 'common\components\S3',
            'key' => 'AKIAIM7TZ36UCAARNVDQ',
            'secret' => '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',
            'bucket' => 'giataivuon',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => '/user/login',
            'identityCookie' => ['name' => 'frontend_identity', 'httpOnly' => true],
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'cookieValidationKey' => 'ymoaYrebZHa8gURuolioHGlK8fLXCKjO123456',
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
                'huong-dan-xu-li-don-hang' => 'help/order',
                'huong-dan-dang-san-pham' => 'help/product',
                'san-pham-sap-giao' => 'product/pending',
                'san-pham-da-luu' => 'customer/wishlist',
                'tra-cuu-don-hang' => 'order/tracking',
                'nha-vuon-da-dong-bao-hiem' => 'seller/insurrance',
                'nha-cung-cap-uy-tin' => 'seller/level',
                'nha-vuon/<id>' => 'seller/view',
                'category/<category>' => 'product/category',
                'filter' => 'filter/index',
                '<slug>-<id>' => 'product/view',
                'nhan-xet/<slug>-<id>' => 'review/view',
                'province/<id>' => 'site/province',
                'message/<id>' => 'message/index',
                "http://vinh.giataivuon.loc/test" => 'testmail/index',
                '<controller:\w+>/<action:\w+>/<id:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@frontend/mail',
            'useFileTransport' => FALSE,
        ],
    ],
    'params' => $params,
];
