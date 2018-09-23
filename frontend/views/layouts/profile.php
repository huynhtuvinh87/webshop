<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

frontend\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <link rel="icon" href="/images/favico.ico" />
        <?php $this->head() ?>
    </head>
    <body class="page-<?= Yii::$app->controller->id ?>">
        <?php $this->beginBody() ?>
        <?= \frontend\widgets\HeaderWidget::widget() ?>
        <div id="disabled"></div>
        <div id="content">
            <div class="container container-mobile">
                <?=
                Breadcrumbs::widget([
                    'homeLink' => ['label' => 'Trang chủ'],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]);
                ?>
                <?= Alert::widget() ?>
                <div class="row">
                    <div class="col-sm-3">

                        <div class="profiles">
                            <p>Tài khoản của </p>
                            <h4><?= !empty(Yii::$app->user->identity->fullname) ? Yii::$app->user->identity->fullname : Yii::$app->user->identity->username ?></h4>
                        </div>
                        <a class="btn btn-default profile-btn">Chọn danh mục</a>

                        <div class="panel panel-default panel-profile">
                            <ul class="list-group list-profile">
                                <li class="list-group-item"><a href="/user/profile">Thông tin tài khoản</a></li>
                                <li class="list-group-item"><a href="/invoice/history">Quản lý đơn hàng</a></li>
                                <li class="list-group-item"><a href="<?= Yii::$app->setting->get('siteurl_message') ?>">Hộp thư của bạn <span class="badge countMsg"></span></a></li>
                                <li class="list-group-item"><a href="/user/password">Thay đổi mật khẩu</a></li>
                            </ul>

                        </div>
                    </div>
                    <div class="col-sm-9">
                        <?= $content ?>
                    </div>
                </div>

            </div>
        </div>

        <?= \frontend\widgets\FooterWidget::widget(['layout' => 'main']) ?>

        <?php $this->endBody() ?>
        <script>

            $(document).ready(function () {
                $('.profile-btn').click(function () {
                    $('.panel-profile').toggle();
                });
                $('.select2-select').select2({

                })

            });
        </script>

    </body>
</html>
<?php $this->endPage() ?>
