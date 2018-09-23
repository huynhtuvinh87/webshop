<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\bootstrap\Html;
use common\components\Constant;
?>

<footer>
    <div class="footer-top">
        <div class="container container-mobile">
            <div class="row">
                <div class="col-sm-8 footer-menu">
                    <div class="row">
                        <div class="col-sm-4 col-b-left">
                            <section class="widget about-us-wg">
                                <h4 class="widget-title">Về Vinagex</h4>
                                <div class="textwidget">
                                    <ul class="menu">

                                        <?php foreach ($info as $value) { ?>
                                            <li><a href="#"><?= $value->title ?></a></li>
                                        <?php } ?>

                                    </ul>
                                </div>
                            </section>           

                        </div>
                        <div class="col-sm-4 col-b-left">
                            <section class="widget cooperate-wg">
                                <h4 class="widget-title">Hợp tác & Tuyển dụng</h4>
                                <div class="textwidget">
                                    <ul class="menu">
                                        <?php foreach ($cooperate as $value) { ?>
                                            <li><a href="#"><?= $value->title ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </section>

                        </div>
                        <div class="col-sm-4 col-b-left">
                            <section class="widget help-wg">
                                <h4 class="widget-title">Hỗ trợ khách hàng</h4>
                                <div class="textwidget">
                                    <ul class="menu">
                                        <?php foreach ($support as $value) { ?>
                                            <li><a target="_blank" href="/<?= $value->slug ?>"><?= $value->title ?></a></li>
                                        <?php } ?>
                                        <li><a href="<?= Yii::$app->user->isGuest ? Yii::$app->setting->get('siteurl_id') . '/login?url=' . Constant::redirect(Yii::$app->setting->get('siteurl_forum')) : Yii::$app->setting->get('siteurl_forum') ?>">Diễn đàn trao đổi</a></li>
                                        <li><a href="<?= Yii::$app->setting->get('siteurl_transport') ?>">Dịch vụ vận chuyển</a></li>
                                        <li><a href="/seller/about">Bán hàng cùng Vinagex</a></li>
                                    </ul>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 location">
                    <section class="widget location-wg">
                        <h4 class="widget-title">công ty cổ phần vinagex</h4>
                        <div class="textwidget">
                            <ul>
                                <li>
                                    <ul>
                                        <li>Số nhà 1000, Đường gì đây, Tp. Đà Nẵng</li>
                                        <li>Điện thoại: 0123456789</li>
                                        <li>Email: info@vinagex.com</li>
                                    </ul>
                                </li>

                            </ul>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <div class="communication">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 news-letter">
                    <section class="widget news-letter-wg">
                        <div class="textwidget">
                            <form action="" method="post" data-toggle="validator" novalidate="novalidate" class="bv-form bv-form-bootstrap">
                                <label>Đăng ký nhận bản tin khuyến mãi</label>
                                <div class="newsletter form-inline">
                                    <div class="form-group has-feedback">
                                        <div class="input-group">
                                            <input type="text" class="form-control newsletter-input" name="email_newsletter" placeholder="Nhập email của bạn" value="" data-bv-notempty="true" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$"  data-bv-field="email_newsletter">
                                            <input type="hidden" name="stateId" value="437">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-success newsletter__button" disabled="">Đăng ký
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <label for="agree2" class="control-label register-new-letter-comfirm-footer">
                                    <input type="checkbox" name="agree" id="agree2" value="1" data-bv-notempty="true" data-bv-notempty-message="Bạn cần phải đồng ý với chính sách bảo mật thông tin" data-bv-field="agree">
                                    Đồng ý với <a target="_blank" href="#">chính sách bảo mật thông tin</a>.
                                </label>
                            </form>
                        </div>
                    </section>
                </div>
                <div class="col-sm-4 app-store">
                    <section class="widget app-wg">
                        <h4 class="widget-title">VINAGEX APP</h4>
                        <div class="textwidget">
                            <ul>
                                <li><a href="#"><img src="/template/images/icon-appstore.png" alt=""></a></li>
                                <li><a href="#"><img src="/template/images/icon-googleplay.png" alt=""></a></li>
                            </ul>
                        </div>
                    </section>
                </div>
                <div class="col-sm-4 social">
                    <section class="widget follow-us-wg">
                        <h4 class="widget-title">KẾT NỐI VỚI CHÚNG TÔI</h4>
                        <div class="textwidget">
                            <ul class="social-items">
                                <li class="facebook"><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li class="twitter"><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li class="google-plus"><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                <li class="youtube"><a href="#"><i class="fa fa-youtube"></i></a></li>
                            </ul>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <!--
    <div class="footer-middle">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 redit-card">
                    <section class="widget redit-card-wg">
                        <h4 class="widget-title">Title</h4>
                        <div class="textwidget">
                            <ul class="social-items">
                                <li><img src="" alt=""></li>
                            </ul>
                        </div>
                    </section>
                </div>
                <div class="col-sm-5 logo-shiper">
                    <section class="widget logo-shiper-wg">
                        <h4 class="widget-title">Ship</h4>
                        <div class="textwidget">
                            <ul class="social-items">
                                <li><img src="" alt=""></li>
                            </ul>
                        </div>
                    </section>
                </div>
                <div class="col-sm-3 logo-shiper">
                    <section class="widget web-wg">
                        <h4 class="widget-title">Title</h4>
                        <div class="textwidget">
                            <ul class="social-items">
                                <li><a href="#"><img src="" alt=""></a></li>
                            </ul>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div> -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 copyright">
                   <div class="pull-left">
                        <ul>
                            <li><a class="text-success" href="/help/join">Trung tâm hỗ trợ</a></li>
                            <li>Điện thoại hỗ trợ: 0843.286.386</li>
                            <li>Email: <a href="javascript:void(0)" class="text-success">hotro@vinagex.com</a></li>
                        </ul>
                   </div>
                   <div style="line-height: 50px" class="pull-right">
                        Vinagex 2018. All rights reserved
                   </div>
                </div>
                <div class="col-sm-2 icon">
                   <!--  <a href="#"><img src="/template/images/logo-dangky.png" alt=""></a> -->
                </div>
            </div>
        </div>
    </div>
</footer>
<?php
if ($layout == "main") {
    ?>
    <div class="menu-mobi main" id="menu-mobile">
        <a class="close-menu" href="#"><i class="remove"><img src="/template/images/close.png" alt="icon"></i></a>
        <div class="menu-mobi-wrapper">
            <div class="top-menu">
                <div class="dropdown">
                    <div class="provinces-box provinces-footer">
                        <b>Xem sản phẩm tại:</b>
                        <span>Gia Lai</span>
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
                <div class="user">
                    <img class="img-circle" src="/template/images/no-avatar.gif" alt=""/>
                    <ul class="user-login">
                        <?php
                        if (Yii::$app->user->isGuest) {
                            ?>
                            <li class="signup"><a href="<?= Yii::$app->setting->get('siteurl_id') ?>/register">Đăng ký</a></li>
                            <li class="login"><a href="<?= Yii::$app->setting->get('siteurl_id') ?>/login">Đăng nhập</a></li>
                            <?php
                        } else {
                            ?>
                            <li class="signup">Chào <?= Yii::$app->user->identity->fullname ?><br> <small>Tài khoản</small></li>
                            <?php
                        }
                        ?>

                    </ul>
                </div>
            </div>
            <div class="list-menu">

                <?php
                if (!Yii::$app->user->isGuest) {
                    ?>
                    <nav class="side-nav">
                        <ul class="nav-menu nav-menu1">
                            <?php
                            if (Yii::$app->user->identity->role == Constant::ROLE_MEMBER) {
                                ?>
                                <li><a href="/order/history">Đơn hàng của tôi</a></li>
                                <li><a href="/user/profile">Tài khoản của tôi</a></li>
                                <li><a href="/san-pham-da-luu">Sản phẩm đã lưu</a></li>
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
                                <?php
                            } else {
                                ?>
                                <li><a target="_blank" href="/user/profile">Tài khoản của tôi</a></li>
                                <li><a href="<?= Yii::$app->user->isGuest ? Yii::$app->setting->get('siteurl_id') . '/login?url=' . Constant::redirect(Yii::$app->urlManager->createAbsoluteUrl(['/invoice/history'])) : '/invoice/history' ?>">Quản lý đơn hàng</a></li>
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
                                <?php
                            }
                            ?>
                        </ul>
                    </nav>
                    <?php
                }
                ?>
                <nav class="side-nav">
                    <ul class="nav-menu nav-menu1">
                        <li><a href="<?= Yii::$app->user->isGuest ? Yii::$app->setting->get('siteurl_id') . '/login?url=' . Constant::redirect(Yii::$app->setting->get('siteurl_forum')) : Yii::$app->setting->get('siteurl_forum') ?>">Diễn đàn trao đổi</a></li>
                        <li><a href="/seller/about">Bán hàng cùng Vinagex</a></li>
                    </ul>
                </nav> 
                <nav class="side-nav">
                    <ul class="nav-menu nav-menu1">
                        <li><a href="/filter?sell=1">Sản phẩm có sẵn</a></li>
                        <li><a href="/filter?sell=2">Sản phẩm đặt trước</a></li>
                        <li><a href="/seller">Danh sách nhà cung cấp</a></li>
                    </ul>
                </nav>
                <nav class="side-nav side-nav-branding">
                    <ul class="branding_menu">
                        <?php
                        foreach ($category as $value) {
                            ?>
                            <li><a href="/filter?category=<?= $value->id ?>"><img src="/template/icon/<?= $value->icon ?>" width="20"> <?= $value->title ?></a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </nav>
            </div>
            <div class="hotline">
                <span class="hotline_text">HOTLINE</span>
                <span class="hotline_number">0843.286.386</span>
            </div>
        </div>
    </div>
    <?php
}
if ($layout == "seller_about") {
    ?>
    <div class="menu-mobi intro">
        <a class="close-menu" href="#"><i class="remove"><img src="images/close.png" alt="icon"></i></a>
        <nav class="side-nav side-nav-branding">
            <ul class="branding_menu">
                <li class="branding-restaurant"><a href="#">Giới thiệu</a></li>
                <li class="branding-beauty"><a href="#">Tìm hiểu chính sách</a></li>
                <li class="branding-entertainment"><a href="/ho-chi-minh/giai-tri-va-dao-tao/">Chào nhà bán mới</a></li>
            </ul>
        </nav>
    </div>
    <?php
}
?>