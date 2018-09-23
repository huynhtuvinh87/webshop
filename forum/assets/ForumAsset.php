<?php

namespace forum\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class ForumAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $cssOptions = ['version' => '1.0'];

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
        'http://vinagex.com/template/style.css',
        'css/forum.css',
        'css/responsive.css',
        'http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css'
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js',
        'https://cloud.tinymce.com/stable/tinymce.min.js',
        'js/summernote.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
