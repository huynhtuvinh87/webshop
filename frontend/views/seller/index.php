<?php

use yii\widgets\ListView;
use yii\bootstrap\Html;
?>
<form action="/seller" method="get" id="formSearch">
    <div class="banner-search">
        <div class="container">
            <h2>Đã có <?= number_format($count_seller); ?> nhà vườn tham gia với chúng tôi.</h2>
            <div class="search-form">
                <input type="text" name="keywords" placeholder="Tìm kiếm nhà vườn" autocomplete="off" value="<?= !empty($_GET['keywords']) ? $_GET['keywords'] : "" ?>">
                <button class="search-field__button" type="submit">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="container container-mobile">
        <div class="filterSeller">
            <div class="row">
                <div class="col-sm-1 col-xs-12">
                    <h5>Lọc theo</h5>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <div class="select">
                        <?= Html::dropDownList('certificate', !empty($_GET['certificate']) ? $_GET['certificate'] : "", $filter->certificate(), ['id' => 'certificate', 'class' => 'form-control', 'prompt' => 'Tiêu chuẩn']); ?>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-6">
                    <div class="select">
                        <?= Html::dropDownList('province', !empty($_GET['province']) ? $_GET['province'] : "", $filter->province(), ['id' => 'province', 'class' => 'form-control', 'prompt' => 'Tỉnh/thành']); ?>
                    </div>
                </div>
            </div>
        </div>
        <?=
        ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => [
                'tag' => 'div',
                'class' => 'row list-shop',
                'id' => 'list-wrapper',
            ],
            'layout' => "{items}\n<div class='col-sm-12 pagination-page'>{pager}</div>",
            'emptyText' => 'Chưa có nhà vườn nào.',
            'itemView' => '_item',
        ]);
        ?>	
    </div>
</form>
<?php
ob_start();
?>
<script type="text/javascript">


    $("body").on("change", "#certificate", function (event, state) {
        $("#formSearch").submit();
    });
    $("body").on("change", "#province", function (event, state) {
        $("#formSearch").submit();
    });

</script>
<?php $this->registerJs(preg_replace('~^\s*<script.*>|</script>\s*$~ U', '', ob_get_clean())) ?>



