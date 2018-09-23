<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use common\components\Constant;
use common\widgets\Alert;

$this->title = 'Giá bán cho sản phẩm ' . $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Alert::widget() ?>
<?php $form = ActiveForm::begin(); ?>
<?php
$price_deal = !empty($model->price_deal) ? $model->price_deal[array_search($province, array_column($model->price_deal, 'province_id'))] : FALSE;
$price_retail = !empty($model->price_retail) ? $model->price_retail[array_search($province, array_column($model->price_retail, 'province_id'))] : FALSE;
$price_whilesale = !empty($model->price_whilesale) ? $model->price_whilesale[array_search($province, array_column($model->price_whilesale, 'province_id'))] : FALSE;
$getProvince = array_search($province, array_column($model->province, 'id'));
?>
<div class="row form-group">
    <div class="col-sm-2">
        <label style="margin-top: 25px"><?= $model->province[$getProvince]['name'] ?></label>
    </div>
    <div class="col-sm-10">
        <?php
        if ($model->checkDeal()) {
            ?>
            <div class="panel panel-primary">
                <div class="panel-heading">Giá deal</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <label>Số lượng tối thiểu</label>
                            <?= Html::textInput('deal_quantity_min', !$price_deal ? 0 : $price_deal['quantity_min'], ['id' => 'deal_quantity_min', 'type' => 'number', 'class' => 'form-control', 'required' => TRUE, 'placeholder' => 10]) ?>
                        </div>
                        <div class="col-sm-2">
                            <label>Số lượng tối da</label>
                            <?= Html::textInput('deal_quantity_max', !$price_deal ? 0 : $price_deal['quantity_max'], ['id' => 'deal_quantity_max', 'type' => 'number', 'class' => 'form-control', 'required' => TRUE, 'placeholder' => 10]) ?>
                        </div>
                        <div class="col-sm-2">
                            <label>Giá bán</label>
                            <?= Html::textInput('deal_price', !$price_deal ? 0 : Constant::price($price_deal['price']), ['type' => 'number', 'class' => 'form-control price', 'required' => TRUE, 'placeholder' => 10.000]) ?>
                        </div>
                        <div class="col-sm-3">
                            <label>Ngày bắt đầu bán</label>
                            <?= Html::textInput('deal_time_begin', !$price_deal ? "" : \Yii::$app->formatter->asDatetime($price_deal['time_begin'], "php:d/m/Y"), ['id' => 'deal_time_begin', 'class' => 'form-control time_begin', 'required' => TRUE]) ?>
                        </div>
                        <div class="col-sm-3">
                            <label>Ngày kết thúc bán</label>
                            <?= Html::textInput('deal_time_end', !$price_deal ? "" : \Yii::$app->formatter->asDatetime($price_deal['time_end'], "php:d/m/Y"), ['id' => 'deal_time_end', 'class' => 'form-control time_end', 'required' => TRUE]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }
        if ($model->checkRetail()) {
            ?>
            <div class="panel panel-success">
                <div class="panel-heading">Giá bán lẻ</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <label>Số lượng tối thiểu</label>
                            <?= Html::textInput('retail_quantity_min', !$price_retail ? 0 : $price_retail['quantity_min'], ['id' => 'retail_quantity_min', 'type' => 'number', 'class' => 'form-control', 'required' => TRUE, 'placeholder' => 10]) ?>
                        </div>
                        <div class="col-sm-2">
                            <label>Số lượng tối da</label>
                            <?= Html::textInput('retail_quantity_max', !$price_retail ? 0 : $price_retail['quantity_max'], ['id' => 'retail_quantity_max', 'type' => 'number', 'class' => 'form-control', 'required' => TRUE, 'placeholder' => 10]) ?>
                        </div>
                        <div class="col-sm-2">
                            <label>Giá bán</label>
                            <?= Html::textInput('retail_price', !$price_retail ? "" : Constant::price($price_retail['price']), ['type' => 'number ', 'class' => 'form-control price', 'required' => TRUE, 'placeholder' => 10]) ?>
                        </div>

                    </div>
                </div>
            </div>
            <?php
        }
        if ($model->checkWhilesale()) {
            ?>
            <div class="panel panel-info">
                <div class="panel-heading">Giá bán sỉ</div>
                <div id="whilesale" class="panel-body whilesale">
                    <input type="hidden" id="whilesale_min_default" value="<?= !empty($price_whilesale['info']) ? $price_whilesale['info'][count($price_whilesale['info']) - 1]['max'] : 5 ?>">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Ngày bắt đầu bán</label>
                            <?= Html::textInput('whilesale_time_begin', !$price_whilesale ? "" : \Yii::$app->formatter->asDatetime($price_whilesale['time_begin'], "php:d/m/Y"), ['id' => 'whilesale_time_begin', 'class' => 'form-control time_begin', 'required' => TRUE]) ?>
                        </div>
                        <div class="col-sm-3">
                            <label>Ngày kết thúc bán</label>
                            <?= Html::textInput('whilesale_time_end', !$price_whilesale ? "" : \Yii::$app->formatter->asDatetime($price_whilesale['time_end'], "php:d/m/Y"), ['id' => 'whilesale_time_end', 'class' => 'form-control time_end', 'required' => TRUE]) ?>
                        </div>
                    </div>

                    <?php
                    if (!empty($price_whilesale['info'])) {
                        foreach ($price_whilesale['info'] as $k => $info) {
                            $count = count($price_whilesale['info']);
                            ?>
                            <div class="row current" id ="<?= 'row-' . ($k + 1) ?>">
                                <div class="col-sm-2">
                                    <label>Số lượng từ</label>
                                    <?= Html::textInput('whilesale_quantity_min[]', $info['min'], ['type' => 'number', 'class' => 'form-control whilesale_quantity_min', 'required' => TRUE, 'placeholder' => 10, 'readonly' => (($k + 1) == $count) ? FALSE : TRUE]) ?>
                                </div>
                                <div class="col-sm-2 whilesale_quantity_max">
                                    <label>đến</label>
                                    <?= Html::textInput('whilesale_quantity_max[]', $info['max'], ['type' => 'number', 'class' => 'form-control whilesale_quantity_max', 'required' => TRUE, 'placeholder' => 20, 'readonly' => (($k + 1) == $count) ? FALSE : TRUE]) ?>
                                </div>
                                <div class="col-sm-2">
                                    <label>giá bán</label>
                                    <?= Html::textInput('whilesale_price[]', Constant::price($info['price']), ['class' => 'form-control price whilesale_price', 'required' => TRUE, 'placeholder' => 10.000, 'readonly' => (($k + 1) == $count) ? FALSE : TRUE]) ?>
                                </div>
                                <div class="col-sm-2 action" style="display: <?= ($count == ($k + 1)) ? "block" : "none" ?>">
                                    <?php
                                    if ($k == 0) {
                                        ?>
                                        <a href="javascript:void(0)" class="btn btn-primary whilesale-add" style="margin-top: 25px; " data-num="<?= $k + 1 ?>" data-id="<?= $province ?>">Thêm</a>
                                        <?php
                                    } else {
                                        ?>
                                        <a href="javascript:void(0)" class="btn btn-danger whilesale-delete" style="margin-top: 25px; float: left" data-num="<?= $k + 1 ?>" data-id="<?= $province ?>">Xoá</a>
                                        <a href="javascript:void(0)" class="btn btn-primary whilesale-add" style="margin-top: 25px" data-num="<?= $k + 1 ?>" data-id="<?= $province ?>">Thêm</a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="row row-default current" id ="row-1">
                            <div class="col-sm-2">
                                <label>Số lượng từ</label>
                                <?= Html::textInput('whilesale_quantity_min[]', 0, ['type' => 'number', 'class' => 'form-control whilesale_quantity_min', 'required' => TRUE, 'placeholder' => 10]) ?>
                            </div>
                            <div class="col-sm-2 whilesale_quantity_max">
                                <label>đến</label>
                                <?= Html::textInput('whilesale_quantity_max[]', 0, ['type' => 'number', 'class' => 'form-control whilesale_quantity_max', 'required' => TRUE, 'placeholder' => 20]) ?>
                            </div>
                            <div class="col-sm-2">
                                <label>giá bán</label>
                                <?= Html::textInput('whilesale_price[]', 0, ['class' => 'form-control price whilesale_price', 'required' => TRUE, 'placeholder' => 10.000]) ?>
                            </div>
                            <div class="col-sm-2 action">
                                <a href="javascript:void(0)" class="btn btn-primary whilesale-add" style="margin-top: 25px" data-num="1" data-id="<?= $province ?>">Thêm</a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<div class="row form-group">
    <div class="col-sm-12">
        <?php
        if (!empty($model->province[$getProvince - 1]['id'])) {
            ?>
            <a href="/product/price/<?= $model->id ?>?province=<?= $model->province[$getProvince - 1]['id'] ?>" class="btn btn-default pull-left">Quay lại</a>
            <?php
        } else {
            ?>
            <a href="/product/view/<?= $model->id ?>" class="btn btn-default pull-left">Quay lại</a>
            <?php
        }
        ?>
        <?php
        if (!empty($model->province[$getProvince + 1]['id'])) {
            ?>
            <input type="hidden" name="province" value="<?= $model->province[$getProvince + 1]['id'] ?>">

            <button  class="btn btn-success pull-right">Tiếp theo</button>
            <?php
        } else {
            ?>
            <button  class="btn btn-success pull-right">Hoàn thành</button>
            <?php
        }
        ?>

    </div>												
</div><!-- preferences-settings -->
<input type="hidden" id="whilesale-row-num" value="<?= $price_whilesale ? count($price_whilesale['info']) : 1 ?>">
<?php ActiveForm::end(); ?>	
<?php
$time = \Yii::$app->formatter->asDatetime(time(), "php:Y-m-d");
$dte = !empty($price_deal) ? $price_deal['time_end'] : $time;
$dtb = !empty($price_deal) ? $price_deal['time_begin'] : $time;
$wte = !empty($price_whilesale) ? $price_whilesale['time_end'] : $time;
$wtb = !empty($price_whilesale) ? $price_whilesale['time_begin'] : $time;
?>
<?= $this->registerJs('
$("body").on("click", ".whilesale-add", function(event, state) {
    var current = parseInt($(this).attr("data-num"));
    var num = parseInt(current+1);
    var price = (parseInt(($("#row-"+current+" input.whilesale_price").val())-1)).toString().replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
    var qmax = parseInt($("#row-"+current+" input.whilesale_quantity_max").val());
    if(qmax > 0) {
    $("#whilesale-row-num").val(num);
        $("#whilesale .row").removeClass("prev");
        if(num < 6){
            $("#row-"+current+" .action").hide();
            $("#row-"+current).addClass("prev");
            $("#row-"+current+" input").attr("readonly", true);
            var html ="<div class=\'row\' id =\'row-"+num+"\'>";
            html += $("#row-"+current).html();
            html += "</div>";
            $("#whilesale").append(html);
            $("#row-"+num+" input.whilesale_quantity_min").val(parseInt(qmax+1));
            $("#row-"+num+" input.whilesale_quantity_max").val(parseInt(qmax+5));
            $("#row-"+num+" input.whilesale_price").val(price);
            $("#row-"+num+" input").attr("readonly", false);
            $("#whilesale_min_default").val(parseInt(qmax+1));
            $("#row-"+num+" .action").show();
            $("#row-"+num+" .action").html("<a href=\"javascript:void(0)\" class=\"btn btn-danger whilesale-delete\" style=\"margin-top: 25px\" data-num="+num+">Xoá</a><a href=\"javascript:void(0)\" class=\"btn btn-primary whilesale-add\" style=\"margin-top: 25px\" data-num="+num+">Thêm</a>");
        }
    }    
});
$("body").on("blur", "#deal_quantity_min", function(event) {
   var min = parseInt($(this).val());
   $("#deal_quantity_max").val(min+5);
});
$("body").on("blur", "#deal_quantity_max", function(event) {
   var min = parseInt($("#deal_quantity_min").val());
   var max = parseInt($(this).val());
   if (max <= min){
        $("#deal_quantity_max").val(min+5);
   } 
});

$("body").on("blur", "#retail_quantity_min", function(event) {
   var min = parseInt($(this).val());
   $("#retail_quantity_max").val(min+5);
});
$("body").on("blur", "#retail_quantity_max", function(event) {
   var min = parseInt($("#retail_quantity_max").val());
   var max = parseInt($(this).val());
   if (max <= min){
        $("#retail_quantity_max").val(min+5);
   } 
});

$("body").on("blur", ".whilesale_quantity_min", function(event) {
   var current_max = parseInt($("#whilesale .prev input.whilesale_quantity_max").val());
   var whilesale_row_num = $("#whilesale-row-num").val();
   var min = parseInt($(this).val());
   if(min <= current_max){
        min = current_max + 1;
   }
   $(this).val(min);
   $("#row-"+whilesale_row_num+" input.whilesale_quantity_max").val(min+5);
   $("#row-"+whilesale_row_num+" input.whilesale_price").val("10.000");
   $("#whilesale_min_default").val(min);
});
$("body").on("blur", ".whilesale_quantity_max", function(event) {
   var max = parseInt($(this).val());
   var min = parseInt($("#whilesale_min_default").val());
   if(max <= min){
       max = min +1;
   } else {
       max = max;
   }
   $(this).val(max);
});
') ?>
<?= $this->registerJs('
$("body").on("click", ".whilesale-delete", function(event, state) {
    var num = parseInt($(this).attr("data-num"));
    var nm_default = parseInt(num - 1);
    $("#row-"+nm_default+" .action").show();
    $("#row-"+nm_default+" input").attr("readonly", false);
    $("#row-"+num).remove();
});
$("body").on("keyup", ".price", function(event) {
    if(event.which >= 37 && event.which <= 40){
    event.preventDefault();
  }
  $(this).val(function(index, value) {
    return value
      .replace(/\D/g, "")
      .replace(/([0-9])([0-9]{3})$/, "$1.$2")  
      .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".")
    ;
  });
});

') ?>
<?=
$this->registerJs("
$('#deal_time_begin').datepicker({
    dateFormat: 'dd/mm/yy',
    autoclose: true,
    startDate: new Date(),
    todayHighlight: true,
    changeMonth: true,
    changeYear: true,
    minDate: 0,
    onSelect: function (date) {
        var deal_time_end = $('#deal_time_end');
        var minDate = $(this).datepicker('getDate');
        var maxDate = new Date('" . $dte . "');
            console.log((maxDate.getTime() - minDate.getTime()));
        if((maxDate.getTime() - minDate.getTime()) <= 25200000 ){
            deal_time_end.datepicker('setDate', minDate);
            minDate.setDate(minDate.getDate() + 1);
            deal_time_end.datepicker('option', 'minDate', minDate);
        } 
    }
});
$('#deal_time_end').datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true,
    todayHighlight: true,
    changeMonth: true,
    changeYear: true,
    minDate: new Date('" . \Yii::$app->formatter->asDatetime((strtotime($dtb) + 24 * 3600), "php:Y-m-d") . "'),
});
$('#whilesale_time_begin').datepicker({
    dateFormat: 'dd/mm/yy',
    autoclose: true,
    startDate: new Date(),
    todayHighlight: true,
    changeMonth: true,
    changeYear: true,
    minDate: 0,
    onSelect: function (date) {
        var whilesale_time_end = $('#whilesale_time_end');
        var minDate = $(this).datepicker('getDate');
        var maxDate = new Date('" . $wte . "');
        if((maxDate.getTime() - minDate.getTime()) <= 25200000 ){
            whilesale_time_end.datepicker('setDate', minDate);
            minDate.setDate(minDate.getDate() + 1);
            whilesale_time_end.datepicker('option', 'minDate', minDate);
        } else {
        }
    }
});
$('#whilesale_time_end').datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true,
    minDate: new Date('" . \Yii::$app->formatter->asDatetime((strtotime($wtb) + 24 * 3600), "php:Y-m-d") . "'),
});
                   
        ");
