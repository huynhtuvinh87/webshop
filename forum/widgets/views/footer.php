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
        <div class="container">
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
                                            <li><a href="#"><?= $value->title ?></a></li>
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
                                <li><a href="#"><img src="<?=Yii::$app->setting->get('siteurl')?>/template/images/icon-appstore.png" alt=""></a></li>
                                <li><a href="#"><img src="<?=Yii::$app->setting->get('siteurl')?>/template/images/icon-googleplay.png" alt=""></a></li>
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
                <div class="col-sm-10 copyright">
                    <ul>
                        <li>Giấy phép kinh doanh: Đang xin giấy phép</li>
                        <li>ĐT: (012) 3456789 - Email: kinhdoanh@vinagex.com</li>
                    </ul>
                </div>
                <div class="col-sm-2 icon">
                   <!--  <a href="#"><img src="/template/images/logo-dangky.png" alt=""></a> -->
                </div>
            </div>
        </div>
    </div>
</footer>
