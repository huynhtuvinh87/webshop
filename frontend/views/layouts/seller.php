<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use common\components\Constant;

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
                        <i class="fa fa-bars" aria-hidden="true"></i>
                        <div class="left">
                            <div id="logo">
                                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl('/') ?>">
                                    <img src="<?= Yii::$app->setting->get('siteurl_cdn') ?>/images/logo_beta.png">
                                </a>
                            </div>
                        </div>

                        <nav class="navigation">
                                <ul class="main-menu">
                                    <li><a href="#">Giới thiệu</a></li>
                                    <li><a href="#">Tìm hiểu chính sách</a></li>
                                    <li><a target="_blank" href="/huong-dan-dang-san-pham">Hướng dẫn đăng ký bán hàng</a></li>
                                </ul>
                            </nav>
                        <div class="right">
                            <div class="button-block"><a href="<?= Yii::$app->user->isGuest ? Yii::$app->setting->get('siteurl_id') . '/login?url=' . Constant::redirect(Yii::$app->setting->get('siteurl_seller')) : Yii::$app->setting->get('siteurl_seller') ?>" class="btn btn-custom">Đăng ký bán hàng</a></div>
                        </div>
                    </div>
                </div>
            </header>

            <div id="content">
                <?= $content ?>
            </div>
        </main>

        <?= \frontend\widgets\FooterWidget::widget(['layout' => 'seller_about']) ?>
    </div>

    <?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>

