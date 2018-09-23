<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use common\components\Constant;
use yii\widgets\Breadcrumbs;

frontend\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <link rel="icon" href="/images/favico.ico" />
        <?php $this->head() ?>
    </head>
    <body id="main">
        <?php $this->beginBody() ?>
        <div id="disabled"></div>
        <div id="wrapper">
            <header class="intro-header">
                <div class="top-bar">
                    <div class="container">
                        <div class="left">
                            <ul>
                                <li><a href="#">Kinh doanh thương mại điện tử cùng Vinagex</a></li>
                            </ul>
                        </div>
                        <div class="right">
                            <ul>
                                <li>Email: <a href="#">support@vinagex.com</a></li>
                                <li>Hotline: <a href="#">0868.444.554</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="bottom">
                    <div class="container">
                       <!--  <i class="fa fa-bars" aria-hidden="true"></i> -->
                        <div class="left">
                            <div id="logo">
                                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl('/') ?>">
                                    <img style="width: 175px; padding-right: 44px;" src="<?= Yii::$app->setting->get('siteurl_cdn') ?>/images/logo_beta.png">
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </header>

            <div id="content">

                <div class="banner-search">
                    <div class="container">
                        <h2>Xin chào. Chúng tôi có thể giúp gì cho bạn?</h2>

                    </div>
                </div>
               
                <div class="container">
                     <?=
                Breadcrumbs::widget([
                    'homeLink' => ['label' => 'Trang chủ'],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]);
                ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <a href="javascript:void(0)" class="btn btn-default btn-help"><i class="fa fa-align-justify"></i> Danh mục quản lý</a>
                            <div class="panel panel-default list-help">
                                <ul class="list-group">
                                    <li class="list-group-item"><a href="/help/join">Hướng dẫn đăng ký</a></li>
                                    <li class="list-group-item"><a href="/huong-dan-dang-san-pham">Hướng dẫn đăng sản phẩm</a></li>
                                    <li class="list-group-item"><a href="/huong-dan-xu-li-don-hang">Hướng dẫn xử lý đơn hàng</a></li>
                                    <li class="list-group-item"><a href="#">Hướng dẫn mua hàng</a></li>
                                    <li class="list-group-item"><a href="#">Hướng dẫn thanh toán</a></li>
                                </ul>

                            </div>
                        </div>
                        <div class="col-sm-9">
                            <?= $content ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?= \frontend\widgets\FooterWidget::widget(['layout' => 'seller_about']) ?>
    </div>

    <?php $this->endBody() ?>
    <?php ob_start(); ?>
    
<script>
    $('.btn-help').click(function(){
        $('.list-help').toggle();
    });
</script>

<?php $this->registerJs(preg_replace('~^\s*<script.*>|</script>\s*$~ U', '', ob_get_clean())) ?>
</body>
</html>
<?php $this->endPage() ?>

