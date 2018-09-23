<?php

use yii\widgets\ListView;

$this->title = $model->garden_name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container container-mobile">
    <?= $this->render('menuMobile', ['model' => $model]) ?>
    <div id="main-content" class="company-content">
        <div id="content" class="grid-main">
            <div class="main-wrap">
                <div class="top-company">
                    <h3 class="title" title="<?= $model->garden_name ?>">
                        <?= $model->garden_name ?>
                        <?php if ($model->active['garden_name'] == 1) { ?>
                            <span class="assesment-info">
                                <a href="#" target="_blank" class="assesment-icon"><i class="assesment-icon-i icon-sa"></i></a>
                            </span>
                        <?php } ?>
                    </h3>

                    <div class="option">
                        <!--<a href="#" class="chat-now">!</a>-->
                        <!--<a href="#" class="supplier-feedback"><i class="fa fa-envelope" aria-hidden="true"></i>Liên hệ nhà cung cấp</a>-->
                        <?= $model->active['insurance_money'] == 1 ? "<a href='#' class='ubmit-order'>Đã đóng bảo hiểm</a>" : "" ?>
                    </div>

                </div>
                <div class="company-detail">
                    <div class="row">
                        <div class="col-md-3 images-seller">
                            <?php
                            if (!empty($model->images)) {
                                ?>
                                <div class="slide-company">
                                    <div class="slider slider-for">

                                        <?php foreach ($model->images as $key => $value) { ?>
                                            <div class="item">
                                                <a href="<?= Yii::$app->setting->get('siteurl_cdn') . '/' . $value; ?>" rel="group" data-lightbox="roadtrip">
                                                    <img  class="lazyload <?= $key == 0 ? "" : "set-img" ?>" data-src="<?= Yii::$app->setting->get('siteurl_cdn') ?>/image.php?src=<?= $value ?>&size=400x400"  src="<?= Yii::$app->setting->get('siteurl_cdn') ?>/image.php?src=images/default.gif&size=350x350">
                                                </a>
                                            </div>
                                        <?php } ?>

                                    </div>
                                    <div class="slider slider-nav">
                                        <?php foreach ($model->images as $key => $value) { ?>
                                            <div class="item">
                                                <span><img  class="lazyload <?= $key == 0 ? "" : "set-img" ?>" data-src="<?= Yii::$app->setting->get('siteurl_cdn') ?>/image.php?src=<?= $value ?>&size=50x50"  src="<?= Yii::$app->setting->get('siteurl_cdn') ?>/image.php?src=images/default.gif&size=50x50" alt="img"></span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="col-md-9 company-info">
                            <div class="info-content">
                                <table class="content-table">
                                    <tbody>
                                        <tr>
                                            <td class="col-title">Tên chủ vườn:</td>
                                            <td class="col-value"><?= $model->fullname ?></td>
                                            <td class="check-verify"></td>
                                        </tr>
                                        <tr>
                                            <td class="col-title">Điện thoại:</td>
                                            <td class="col-value"><?= Yii::$app->user->isGuest ? substr($model->phone, 0, 3) . '********' : $model->phone; ?></td>
                                            <?php if ($model->active['phone'] == 1) { ?>
                                                <td class="check-verify"><i class="fa fa-check" aria-hidden="true"></i><span>Xác thực</span></td>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <td class="col-title">Địa chỉ:</td>
                                            <td class="col-value"><?= $model->address ?>, <?= $model->ward['name'] ?>, <?= $model->district['name'] ?>, <?= $model->province['name'] ?></td>
                                            <?php if ($model->active['address'] == 1) { ?>
                                                <td class="check-verify"><i class="fa fa-check" aria-hidden="true"></i><span>Xác thực</span></td>
                                            <?php } ?>
                                        </tr>
                                        <?php if (!empty($model->trademark)) { ?>
                                            <tr>
                                                <td class="col-title">Thương hiệu:</td>
                                                <td class="col-value"><?= $model->trademark ?></td>
                                                <?php if ($model->active['trademark'] == 1) { ?>
                                                    <td class="check-verify"><i class="fa fa-check" aria-hidden="true"></i><span>Xác thực</span></td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>

                                        <?php
                                        if (count($model->certificate) > 0) {
                                            $cer = [];
                                            foreach ($model->certificate as $value) {
                                                if ($value['active'] == 1) {
                                                    $cer[] = $value['name'];
                                                }
                                            }
                                            if (count($cer) > 0) {
                                                ?>
                                                <tr>
                                                    <td class="col-title">Chứng nhận:</td>
                                                    <td class="col-value">
                                                        <?php
                                                        echo implode(', ', $cer);
                                                        ?>
                                                    </td>
                                                    <td class="check-verify"><i class="fa fa-check"></i> <span>Xác thực</span></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>

                                        <tr>
                                            <td class="col-title">Sản phẩm cung cấp:</td>
                                            <td class="col-value"><?php
                                                $category = [];
                                                if ($model['category']) {
                                                    foreach ($model['category'] as $key => $value) {
                                                        $category[] = '<a href="/filter?category=' . $value['category_id'] . '&type%5B%5D=' . $value['id'] . '">' . $value['title'] . '</a>';
                                                    }
                                                    echo implode(',', $category);
                                                }
                                                ?>
                                            </td>
                                            <?php if ($model->active['category'] == 1) { ?>
                                                <td class="check-verify"><i class="fa fa-check" aria-hidden="true"></i> <span>Xác thực</span></td>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <td class="col-title">Số lượng cung cấp:</td>
                                            <td class="col-value"><?= $model->output_provided ?> tấn/năm</td>
                                            <?php if ($model->active['output_provided'] == 1) { ?>
                                                <td class="check-verify"><i class="fa fa-check" aria-hidden="true"></i> <span>Xác thực</span></td>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <td class="col-title">Diện tích:</td>
                                            <td class="col-value"><?= $model->acreage ?> ha</td>
                                            <?php if ($model->active['acreage'] == 1) { ?>
                                                <td class="check-verify"><i class="fa fa-check" aria-hidden="true"></i> <span>Xác thực</span></td>
                                            <?php } ?>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="company-section">
                        <h3 class="comp-title">Giới thiệu</h3>
                        <div class="comp-content">
                            <div class="comp-text">
                                <?= $model->about ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (count($model->certificate) > 0) {
                        foreach ($model->certificate as $key => $value) {
                            $active[$value['name']] = $value['active'];
                        }
                        if (in_array(1, $active)) {
                            ?>
                            <div class="company-section">
                                <h3 class="comp-title">Chứng nhận</h3>
                                <div class="comp-content">
                                    <table class="table table-bordered table-customize table-responsive">
                                        <thead>
                                            <tr>
                                                <th class="col-title">Hình ảnh</th>
                                                <th class="col-value">Tên chứng nhận</th>
                                                <th class="col-value">Nơi cấp</th>
                                                <th class="col-value">Ngày cấp</th>
                                                <th class="check-verify">Xác thực</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($model->certificate as $value) {
                                                if ($value['image'] && $value['date_begin'] && $value['date_end']) {
                                                    ?>
                                                    <tr>
                                                        <td data-title="Hình ảnh">
                                                            <?php if ($value['active'] == 1) { ?>
                                                                <a href="javascript:void(0)" class="image_zoom" data-src="<?= Yii::$app->setting->get('siteurl_cdn') . '/' . $value['image'] ?>" data-title="<?= $value['name'] ?>">
                                                                    <img src="<?= Yii::$app->setting->get('siteurl_cdn') . '/' . $value['image'] ?>" width="50">
                                                                </a>
                                                            <?php } ?>
                                                        </td>
                                                        <td data-title="Tên chứng nhận"><?= $value['name'] ?></td>
                                                        <td data-title="Ngày cấp"><?= $value['date_begin'] ?></td>
                                                        <td data-title="Ngày hết hạn"><?= $value['date_end'] ?></td>
                                                        <td data-title="Xác thực" >
                                                            <?php if ($value['active'] == 1) { ?>
                                                                <span style="color:#52af50"><i class="fa fa-check" aria-hidden="true"></i> Xác thực</span>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <span class="text-danger"><i class="fa fa-remove" aria-hidden="true"></i> Chưa xác thực</span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>                
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
<?= $this->render('sidebar', ['model' => $model]) ?>
    </div>
    <div class="more-pro">
        <h3>Sản phẩm</h3>
        <div class="wrap-product">
            <?=
            ListView::widget([
                'dataProvider' => $dataProviderProduct,
                'options' => [
                    'tag' => 'div',
                    'id' => 'list-wrapper',
                    'class' => "list-product row gird gird-5"
                ],
                'emptyText' => 'Không có sản phẩm nào!',
                'itemOptions' => ['class' => 'col-sm-3 col-lg-3 col'],
                'layout' => "{items}",
                'itemView' => '/product/_item',
            ]);
            ?>
        </div>
        <?php
        if ($dataProviderProduct->getTotalCount() > 0) {
            ?>
            <p class="text-center" style="margin-top: 15px;"><a href="/nha-vuon/<?= $model->username ?>?type=product" class="btn btn-success">Xem tất cả</a></p>
            <?php
        }
        ?>
    </div>
</div>
<?php ob_start(); ?>
<script type="text/javascript">
    setInterval(function () {
        $(".set-img").show();
    }, 1000);
    $('.image_zoom').click(function () {
        var image = $(this).data('src');
        $("#modalHeader span").html($(this).attr("data-title"));
        $('#modal-image').modal('show').find('#modalContent').html('<img src="' + image + '">')
        return false;
    });
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true
    })
</script>
<?php $this->registerJs(preg_replace('~^\s*<script.*>|</script>\s*$~ U', '', ob_get_clean())) ?>
<?php
yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'header' => '<span></span>',
    'id' => 'modal-image',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
?>
<div id='modalContent' style="text-align: center">

</div>
<?php
yii\bootstrap\Modal::end();
?>