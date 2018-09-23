<?php

namespace transport\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class TransportAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public function init() {
        parent::init();
        // resetting BootstrapAsset to not load own css filesß
        \Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
            'css' => ['css/bootstrap.min.css'],
            'js' => ['js/bootstrap.min.js']
        ];
    }

    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.4/datepicker.css',
        'https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css',
        'css/transport.css',
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js',
        'https://code.jquery.com/ui/1.11.4/jquery-ui.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
