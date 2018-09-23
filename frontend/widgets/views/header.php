<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use common\components\Constant;

$cart = Yii::$app->cart;
?>
<header>
    <div class="top-bar">
        <div class="container container-mobile">
            <div class="left">
                <div class="provinces-box provinces-header">
                    <b>Xem bán, giao hàng tại:</b>
                    <span>Toàn quốc</span>
                    <div class="fake"></div>
                    <div class="balloon">
                        <div class="point"></div>
                        <span class="close">╳</span>
                        <span>Chọn tỉnh thành để xem đúng giá &amp; khuyến mãi:</span>
                        <input type="text" class="search-province" placeholder="Tìm kiếm tỉnh thành...">
                        <ul>
                            <?php
                            foreach ($province as $value) {
                                if (Yii::$app->province->getId() == $value->id) {
                                    echo '<li class="active"><a href="/province/' . $value->id . '?url=' . $_SERVER['REQUEST_URI'] . '">' . $value->name . '</a></li>';
                                } else {
                                    echo '<li><a href="/province/' . $value->id . '?url=' . $_SERVER['REQUEST_URI'] . '">' . $value->name . '</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="right">
                <ul id="user_support" class="navbar-right">
                    <?php if(!Yii::$app->user->isGuest){ ?>
                    <li class="notification"><a id="notification" href="javascript:void(0)"><i class="fas fa-bell"></i> Thông báo<?= !empty($count_buyer)?'<span>'.$count_buyer.'</span>':'' ?></a>
                        <div class="list-notification">
                            <div class="header-notification">
                                <b>Thông báo</b>
                            </div>
                            <div class="translate-notification"></div>
                            <div class="content-notification">
                                <div class="item-notification">
                                <?php if(!empty($notification)){ ?>
                                    <?php foreach($notification as $value){ ?>
                                    <div id="notification-" class="item <?= $value->status==0?'noactive':''?>">
                                        <a data-id="<?= $value->id ?>" href="javascript:void(0)" class="read" data-href="<?= $value->url ?>">
                                        <div class="pull-left">
                                            <p><?= $value->content ?></p> 
                                            <p class="time"><i class="fas fa-clock"></i> <?= Constant::time($value->created_at) ?>
                                        </div>
                                        </a>
                                    <!--     <div class="pull-right">
                                            <a class="check-read" data-id="" title="Đánh dấu chưa đọc" href="javascript:void(0)"><i class="fa fa-eye"></i></a>
                                        </div> -->
                                    </div>
                                    <?php } ?>
                                <?php }else{ ?>
                                    <p style="padding: 10px;">Chưa có thông báo nào !</p>
                                <?php } ?>
                                </div>
                            </div>
                            <div class="all-notification">
                                <a href="/notification/index"><b>Xem tất cả</b></a>
                            </div>
                        </div>
                    </li>
                    <?php } ?>
                    <li><a href="<?= Yii::$app->setting->get('siteurl_forum') ?>">Diễn đàn trao đổi</a></li>
                    <li>
                    <li><a href="<?= Yii::$app->user->isGuest ? Yii::$app->setting->get('siteurl_id') . '/login?url=' . Constant::redirect(Yii::$app->urlManager->createAbsoluteUrl(['/invoice/history'])) : '/invoice/history' ?>">Quản lý đơn hàng</a></li>
                    <!--                        <div class="dropdown">
                                                <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Tra cứu đơn hàng
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-tracking">
                                                    <form id="formTracking" method="GET" action="/tra-cuu-don-hang?">
                                                        <div class="input-group">
                                                            <input type="text" name="key" class="form-control" placeholder="Nhập email hoặc số điện thoại">
                                                            <span class="input-group-btn">
                                                                <button class="btn btn-success btn-tracking">Tra cứu</button>
                                                            </span>
                                                        </div> /input-group 
                                                    </form>
                                                </ul>
                                            </div>-->
                    </li>

                    <li><a href="/seller/about">Bán hàng cùng Vinagex</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="container container-mobile">
            <div class="row">
                <div class="col-xs-3" id="bars">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <div id="logo">
                        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl('/') ?>">
                            <img src="<?= Yii::$app->setting->get('siteurl_cdn') ?>/images/logo_beta.png" alt="logo">
                        </a>
                    </div>
                </div>
                <div class="col-sm-5 col-xs-12" id="filter-header">
                    <div class="form-search">
                        <form id="search_form" method="get" role="search" action="/filter">
                            <div class="input-group search-wrap">
                                <div class="input-group-btn list-search">
                                    <input type="hidden" id="search_category" name="category" value="0">
                                    <button type="button" class="btn btn-default dropdown-toggle dropdown-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <span>Tất cả danh mục</span>
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </button>
                                    <ul class="dropdown-menu ">
                                        <li data-category="0">Tất cả danh mục</li>
                                        <?php
                                        foreach ($category as $value) {
                                            echo '<li data-category="' . $value->id . '">- ' . $value->title . '</li>';
                                        }
                                        ?>

                                    </ul>
                                </div>
                                <!-- /btn-group -->
                                <input type="search" id="search-key" name="keywords" id="s" autocomplete="off" value="<?= !empty($_GET['keywords']) ? $_GET['keywords'] : '' ?>" placeholder="Tìm kiếm sản phẩm">
                                <button class="btn search-header" type="submit">
                                    <i class="hd hd-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-3 header-right">
                    <i class="fa fa-search icon-search"></i>
                    <div id="user_info_header">
                        <?php
                        if (Yii::$app->user->isGuest) {
                            ?>
                            <a href="<?= Yii::$app->setting->get('siteurl_id') ?>/login">
                                <i class="hd hd-user"></i> Đăng nhập <br> Đăng ký
                            </a>
                            <?php
                        } else {
                            ?>
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="hd hd-user"></i> <h5 class="name-user">Chào <?= !empty(Yii::$app->user->identity->fullname) ? Yii::$app->user->identity->fullname : Yii::$app->user->identity->username ?></h5><small>Tài khoản</small>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/invoice/history">Đơn hàng của tôi</a></li>
                                <li><a href="/user/profile">Tài khoản của tôi</a></li>
                                <li><a target="_blank" href="<?= Yii::$app->setting->get('siteurl_message') ?>">Hộp thư của bạn <span class="badge countMsg"></span></a></li>
                                <li>
                                    <?=
                                    Html::beginForm(['/user/logout'], 'post')
                                    . Html::submitButton(
                                            'Thoát tài khoản', ['class' => 'btn btn-link logout']
                                    )
                                    . Html::endForm()
                                    ?>
                                </li>
                            </ul>
                            <?php
                        }
                        ?>

                    </div>
                    <div class="header-cart">
                        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/cart/checkout']) ?>">
                            <i class="hd hd-cart"></i>
                            <span class="circle"><?= $cart->getTotalCount() ?></span>
                            <p class="hidden-xs hidden-sm text-cart">Giỏ hàng</p>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-nav">
        <div class="container container-mobile">
            <nav class="main-nav-wrap active">
                <a href="#" class="main-nav-toggle"><span>Danh mục </span> <i class="fa fa-angle-down"></i></a>

                <ul class="list-cat" style="display: <?= (Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index') ? "block" : "none" ?>">
                    <?php
                    foreach ($category as $value) {
                        ?>
                        <li><a href="/filter?category=<?= $value->id ?>"><img src="/template/icon/<?= $value->icon ?>" width="20"> <?= $value->title ?></a>
                            <?php
                        }
                        ?>
                </ul>

            </nav>
            <a href="/filter?sell=1">Sản phẩm có sẵn</a>
            <a href="/filter?sell=2">Sản phẩm đặt trước</a>
            <a href="/seller">Danh sách nhà cung cấp</a>
        </div>
    </div>
</header>
<?php ob_start(); ?>
<script type="text/javascript">
    $(".provinces-header>span").html($(".provinces-header ul li.active").text());
    $(".provinces-footer>span").html($(".provinces-footer ul li.active").text());

    $('#user_support .read').click(function(){
        var id = $(this).data('id');
        var url = $(this).data('href');        
        $.ajax({
            url: '<?= Yii::$app->urlManager->createUrl(["notification/status"]); ?>',
            type: 'POST',
            data: 'id='+id,
            success: function (data) {
                $(location).attr('href', url);
            }
        });
    });

    $('#notification').click(function(event){
        $('.list-notification').toggle();
          event.stopPropagation();
    });

    $(document).click(function(event) {
        if (!$(event.target).is('.list-notification, .list-notification *')) {
            $(".list-notification").hide();
        }
    });

</script>
<?php $this->registerJs(preg_replace('~^\s*<script.*>|</script>\s*$~ U', '', ob_get_clean())) ?>