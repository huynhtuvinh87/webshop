<?php
$this->title = "Giấy phép, chứng nhận";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <?= $this->render('menuMobile', ['model' => $model]) ?>
    <div id="main-content" class="company-content">
        <div id="content" class="grid-main">
            <div class="main-wrap">
                <div class="top-company">
                    <h3 class="title" title="<?= $model->garden_name ?>">
                        <?= $this->title ?>

                    </h3>

                    <div class="option">
                        <!--<a href="#" class="chat-now">!</a>-->
                        <!--<a href="#" class="supplier-feedback"><i class="fa fa-envelope" aria-hidden="true"></i>Liên hệ nhà cung cấp</a>-->
                        <?= $model->active['insurance_money'] == 1 ? "<a href='#' class='ubmit-order'>Đã đóng bảo hiểm</a>" : "" ?>
                    </div>

                </div>
                <div class="company-section">
                    <div class="comp-content">
                        <?php if (count($model->certificate) > 0) { ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-title">Hình ảnh</th>
                                        <th class="col-value">Tên chứng nhận</th>
                                        <th class="col-value">Ngày cấp</th>
                                        <th class="col-value">Ngày hết hạn</th>
                                        <th class="check-verify">Xác thực</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($model->certificate as $value) {
                                        if ($value['image'] && $value['date_begin'] && $value['date_end']) {
                                            ?>
                                            <tr>
                                                <td class="col-title col-img">

                                                    <?php if ($value['active'] == 1) { ?>
                                                        <a href="javascript:void(0)" class="image_zoom" data-title="<?= $value['name'] ?>"><img src="<?= Yii::$app->setting->get('siteurl_cdn') . '/' . $value['image']; ?>" alt="ISO9001"></a>
                                                    <?php } ?>

                                                </td>
                                                <td class="col-value"><?= $value['name'] ?></td>
                                                <td class="col-value"><?= $value['date_begin'] ?></td>
                                                <td class="col-value"><?= $value['date_end'] ?></td>
                                                <td>
                                                    <?php if ($value['active'] == 1) { ?>
                                                        <span class="text-success"><i class="fa fa-check" aria-hidden="true"></i>Xác thực</span>
                                                    <?php } else {
                                                        ?>
                                                        <span class="text-danger"><i class="fa fa-remove" aria-hidden="true"></i>Chưa xác thực</span>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>                
                                            </tr>
                                            <?php
                                        }
                                    }
                                } else {
                                    echo "<small>Chưa có chứng nhận nào !</small>";
                                }
                                ?>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->render('sidebar', ['model' => $model]) ?>
    </div>
</div>
<?php ob_start(); ?>
<script type="text/javascript">
    $('.image_zoom').click(function () {
        var image = $(this).html();
        $("#modalHeader span").html($(this).attr("data-title"));
        $('#modal-image').modal('show').find('#modalContent').html(image)
        return false;
    });
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