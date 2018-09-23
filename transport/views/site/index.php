<?php

use common\components\Constant;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = "Dịch vụ vận chuyển hoàn hoá nông nghiệp Việt Nam";
?>
<div class="banner-intro">
    <div class="container">
        <div class="text-banner">
            <h2>Dịch vụ vận chuyển hoàn hoá nông nghiệp Việt Nam.</h2>
            <p>Nhằm giúp đỡ cho nông dân về vận chuyển với giá hợp lý và chất lượng.</p>
        </div>
    </div>
</div>
<div class="container">
    <h2>Danh sách đơn hàng</h2>

    <div class="search">
        <div class="row">
            <?php $form = ActiveForm::begin(['id' => 'form-search', 'method' => 'get']); ?>  
            <div class="col-sm-3">
                <?=
                $form->field($search, 'quantity')->dropDownList([
                    1000 => '1 tấn',
                    2000 => '2 tấn',
                    3000 => '3 tấn',
                    4000 => '4 tấn',
                    5000 => 'Nhiều hơn 4 tấn'
                        ], ['prompt' => 'Chọn số lượng cần tìm ...', 'class' => 'form-control', 'id' => 'quantity'])->label(FALSE)
                ?>
            </div>
            <div class="col-sm-3">

                <?=
                $form->field($search, 'from')->dropDownList(Constant::province(), ['prompt' => 'Chọn điểm đi ...', 'class' => 'form-control select2-select', 'id' => 'from'])->label(FALSE)
                ?>
            </div>
            <div class="col-sm-3">
                <?=
                $form->field($search, 'to')->dropDownList(Constant::province(), ['prompt' => 'Chọn điểm đến ...', 'class' => 'form-control select2-select', 'id' => 'to'])->label(FALSE)
                ?>

            </div>
            <?= Html::submitButton('Tìm kiếm', ['class' => 'btn-search']) ?>
            <?php ActiveForm::end(); ?>
        </div>

    </div>
    <ul class="list-group list-group-header" style="margin-bottom: 0;">
        <li class="list-group-item" style=" border-bottom: 0">
            <div class="row">

                <div class="col-sm-2">
                    <h5>Điểm đi</h5>
                </div>
                <div class="col-sm-2">
                    <h5>Điểm đến</h5>
                </div>
                <div class="col-sm-2">
                    <h5>Loại xe</h5>
                </div>
                <div class="col-sm-2">
                    <h5>Thời gian</h5>
                </div>
                <div class="col-sm-2">
                    <h5>Mô tả</h5>
                </div>
                <div class="col-sm-2">
                    <h5>Báo giá vận chuyển</h5>
                </div>
            </div>

        </li>
    </ul>

    <?php Pjax::begin(['id' => 'pjax-gird', 'timeout' => 5000, 'enablePushState' => false]) ?>
    <?=
    ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'tag' => 'ul',
            'id' => 'list-wrapper',
            'class' => 'list-group list-group-body'
        ],
        'itemOptions' => ['tag' => 'li', 'class' => 'list-group-item'],
        'layout' => "{items}\n<div class='pagination-page text-center'>{pager}</div>",
        'itemView' => '_item',
    ]);
    ?>
    <?php Pjax::end(); ?>

</div>

<?php ob_start(); ?>
<script type="text/javascript">
    $('.select2-select').select2({});
    $("#quantity,#from,#to").on("change", function (event, state) {
        //var quantity = parseInt($("#select-quantity option:selected").val());
        $('#form-search').submit();
    });


    $('body').on('click', '.bid', function () {
        $("#modalHeader span").html('Gửi báo giá');
        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            success: function (data) {
                $('#modal-bid').modal('show').find('#modalContent').html(data)
            },
        });

        return false;
    });
    $('body').on('click', '.delete', function () {
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: {bid: $(this).attr('data-id')},
            success: function (data) {
                $.pjax({container: '#pjax-gird'});
            },
        });

        return false;
    });
    $('body').on('beforeSubmit', '#formBid', function () {
        $.ajax({
            type: "POST",
            url: '/ajax/bid',
            data: $('#formBid').serialize(),
            success: function (data) {
                $.pjax({container: '#pjax-gird'});
                $('#modal-bid').modal('hide');
            },
        });

        return false;
    });
</script>
<?php $this->registerJs(preg_replace('~^\s*<script.*>|</script>\s*$~ U', '', ob_get_clean())) ?>
<?php
yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'header' => '<span></span>',
    'id' => 'modal-bid',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
?>
<div id='modalContent'>

</div>


<?php
yii\bootstrap\Modal::end();
?>