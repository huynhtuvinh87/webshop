<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use transport\assets\TransportAsset;

TransportAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <style>
            .list-group-item{
                border-radius: 0 !important;
            }
        </style>

    </head>
    <body>
        <?php $this->beginBody() ?>
        <?php
        NavBar::begin([
            'brandLabel' => '<img src="'.Yii::$app->setting->get('siteurl_cdn').'/images/logo.png" width=125>',
            'brandUrl' => Yii::$app->setting->get('siteurl'),
            'innerContainerOptions' => ['class' => 'container'],
            'options' => [
            ],
        ]);

        $menuItems = [
                // ['label' => 'Xin chào ' . $user['name'], 'url' => ['#']],
        ];

        $menuItems[] = '<div class="tabs">';
        $menuItems[] = '<a href="/">Trang chủ</a>';
        $menuItems[] = '<a>Dịch vụ vẩn chuyển hàng hoá</a>';
        $menuItems[] = '<a>Liên hệ: 0905951699</a>';

        $menuItems[] = '</div>';

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right top'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>

        <?= $content ?>
        <?php $this->endBody() ?>


        <?php ob_start(); ?>
        <script>
            $("#item-logout").on("click", function () {
                $('#logout').submit();
            });
        </script>
        <?php $this->registerJs(preg_replace('~^\s*<script.*>|</script>\s*$~ U', '', ob_get_clean())) ?>
    </body>
</html>

<?php
$this->endPage();
exit;
?>
                        

