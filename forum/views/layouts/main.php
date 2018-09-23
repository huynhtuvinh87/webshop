<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use forum\assets\ForumAsset;
use common\components\Constant;
ForumAsset::register($this);
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
    </head>
    <body>
        <?php $this->beginBody() ?>
        <?php
        NavBar::begin([
            'brandLabel' => '<img src="' . Yii::$app->setting->get('siteurl_cdn') . '/images/logo_beta.png">',
            'brandUrl' => Yii::$app->setting->get('siteurl'),
            'innerContainerOptions' => ['class' => 'container'],
            'options' => [
            ],
        ]);

        $menuItems = [
                // ['label' => 'Xin chào ' . $user['name'], 'url' => ['#']],
        ];

        $menuItems[] = '<div class="tabs">';
        $menuItems[] = '<a id="tabs-home" href="/site/index">Trang chủ</a>';
        $menuItems[] = '<a class="'.(!empty($_GET['vote'])?"active":"").'" id="tabs-vote" data="vote" href="javascript:void(0)">Top bình chọn</a>';
        $menuItems[] = '<a class="'.(!empty($_GET['news'])?"active":"").'" id="tabs-news" data="news" href="javascript:void(0)">Mới nhất</a>';
        $menuItems[] = '<a class="'.(!empty($_GET['answers'])?"active":"").'" id="tabs-answers" data="answers" href="javascript:void(0)">Trả lời nhiều nhất</a>';
        if (!empty(Yii::$app->user->id)) {
            $menuItems[] = '<div id="tabs-user" class="dropdown show">
                                <a class="btn-sm btn-secondary dropdown-toggle login" href="javascript:void(0)" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Xin chào: ' . Yii::$app->user->identity->fullname . '<span class="caret"></span></a>
                                <ul class="dropdown-menu dropdown">
                                <li><a  class="dropdown-item item-user" href="/site/myquestion">Danh sách câu hỏi của bạn</a></li>
                                <li>' . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                            'Thoát', ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>
                                </ul>
                            </div>';
        } else {
            $menuItems[] = "<a class='login' target='_blank' href=" . Yii::$app->setting->get('siteurl_id') . '/login?url='.Constant::redirect(Yii::$app->setting->get('siteurl_forum')) . ">Đăng nhập</a>";
        }
        $menuItems[] = '</div>';

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right top'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>
        <div class="wrapper">
            <?= $content ?>
        </div>
        <?= forum\widgets\FooterWidget::widget() ?>
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
?>
                        

