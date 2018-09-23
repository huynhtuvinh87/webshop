<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;


        NavBar::begin([
            'brandLabel' => '<img src="' . Yii::$app->setting->get('siteurl_cdn') . '/images/logo_beta.png" width=125>',
            'brandUrl' => Yii::$app->setting->get('siteurl'),
            'innerContainerOptions' => ['class' => 'container-fluid'],
            'options' => [
                'id' => 'navbar-seller',
            ],
        ]);
        $menuItems = [
            ['label' => 'Trang chủ', 'url' => ['site/index']],
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Đăng ký', 'url' => ['/site/signup']];
            $menuItems[] = ['label' => 'Đăng nhập', 'url' => ['/site/login']];
        } else {
           
            $menuItems[] = ['label' => 'Chào ' . Yii::$app->user->identity->fullname, 'url' => ['/seller/index']];
            $menuItems[] = ['label' => '<i class="fab fa-facebook-messenger"></i><span id="msg" class="badge"></span>', 'url' => Yii::$app->setting->get('siteurl_message'), 'linkOptions' => ['target' => '_blank' ,'title'=>'Tin nhắn']];
            $menuItems[] = ['label' => '<i class="fas fa-bell"></i>'.(!empty($count_notification)?'<span>'.$count_notification.'</span>':'').'', 'url' => ['/notification'],'linkOptions' => ['id' => 'notification','title'=>'Thông báo']];
            $menuItems[] = '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                            'Thoát', ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>';
        }
        echo Nav::widget([
            'encodeLabels' => false,
            'options' => ['class' => 'navbar-nav navbar-right navbar-menu'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>