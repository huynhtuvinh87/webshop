<?php

use yii\widgets\ListView;
use common\components\Constant;

$this->title = "Thống kê bán hàng";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <?= $this->render('menuMobile', ['model' => $model]) ?>
    <div id="main-content" class="company-content">
        <div id="content" class="grid-main" style="min-height: 300px">
            <div class="main-wrap">
                <div class="top-company">
                    <h3 class="title">
                        <?= $this->title ?>

                    </h3>

                </div>
                <div class="company-section">
                    <div class="comp-content">
                        <?php
                        if ($static) {
                            $totalQtt = array_sum(array_column($static, 'totalQtt'));
                            ?>
                            <div id="seller-static">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr><th>Sản phẩm</th><th>Số lượng bán</th><th>Doanh thu</th><th></th></tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($static as $value) {
                                            ?>
                                            <tr>
                                                <td><?= $value['_id']['product']['title'] ?></td>
                                                <td><?= $value['totalQtt'] ?></td>
                                                <td><?= Constant::price($value['totalAmount']) ?></td>
                                                <td><a href="/seller/static/<?= $value['_id']['product']['id'] ?>" class="static_item" data-title="<?= $value['_id']['product']['title'] ?>">Chi tiết</a></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->render('sidebar', ['model' => $model]) ?>
    </div>
</div>
<?php ob_start(); ?>
<script type="text/javascript">
    $('.static_item').click(function () {
        var title = $(this).attr('data-title');
        $.get($(this).attr('href'), function (data) {
            $('#modal-static').modal('show').find('#modalContentStatic').html(data);
            $('#modalHeaderStatic span').html(title);
        });
        return false;
    });
</script>
<?php $this->registerJs(preg_replace('~^\s*<script.*>|</script>\s*$~ U', '', ob_get_clean())) ?>
<?php
yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeaderStatic'],
    'header' => '<span></span>',
    'id' => 'modal-static',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
?>
<div id='modalContentStatic'>

</div>
<?php
yii\bootstrap\Modal::end();
?>