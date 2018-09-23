<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\bootstrap\ActiveForm;
?>

<?php
$form = ActiveForm::begin([
            'layout' => 'horizontal',
            'id' => 'formBid',
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'label' => 'col-sm-5',
                    'offset' => 'col-sm-offset-5',
                    'wrapper' => 'col-sm-7',
                ],
            ],
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
        ]);
?>
<input type="hidden" name="BidForm[id]" value="<?= $model->id ?>">
<?= $form->field($model, 'product_name')->textInput() ?> 
<?= $form->field($model, 'phone')->textInput() ?> 
<?= $form->field($model, 'number')->textInput() ?> 
<?= $form->field($model, 'price')->textInput() ?> 
<?= $form->field($model, 'commitment')->textarea() ?> 

<div class="form-group" style="margin-top: 30px">
    <div class="col-sm-7 col-sm-offset-5">
        <button type="submit" class="btn btn-success">Đồng ý</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Đóng</button>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php ob_start(); ?>
<?php
$time_begin = \Yii::$app->formatter->asDatetime(time(), "php:Y-m-d");
$time_end = \Yii::$app->formatter->asDatetime(time() + 3600 * 24, "php:Y-m-d h:i:s");
?>
<script type="text/javascript">
    $('#bidform-time_begin').datepicker({
        dateFormat: 'dd/mm/yy',
        autoclose: true,
        startDate: new Date(),
        todayHighlight: true,
        changeMonth: true,
        changeYear: true,
        minDate: 0,
        onSelect: function (datetext) {
            var d = new Date(); // for now
            var h = d.getHours();
            h = (h < 10) ? ("0" + h) : h;
            var m = d.getMinutes();
            m = (m < 10) ? ("0" + m) : m;
            var s = d.getSeconds();
            s = (s < 10) ? ("0" + s) : s;
            datetext = datetext + " " + h + ":" + m + ":" + s;
            $('#bidform-time_begin').val(datetext);
            var time_end = $('#bidform-time_end');
            var minDate = $(this).datepicker('getDate');
            var maxDate = new Date('<?= $time_begin ?>');
            if ((maxDate.getTime() - minDate.getTime()) <= 25200000) {
                time_end.datepicker('setDate', minDate);
                minDate.setDate(minDate.getDate() + 1);
                time_end.datepicker('option', 'minDate', minDate);
            }
        },
    });
    $('#bidform-time_end').datepicker({
        dateFormat: 'dd/mm/yy 23:59:59',
        changeMonth: true,
        changeYear: true,
        todayHighlight: true,
        changeMonth: true,
        changeYear: true,
        minDate: new Date('<?= \Yii::$app->formatter->asDatetime((strtotime($time_end)), "php:Y-m-d") ?>'),
        onSelect: function (datetext) {
            var d = new Date(); // for now
            var h = d.getHours();
            h = (h < 10) ? ("0" + h) : h;
            var m = d.getMinutes();
            m = (m < 10) ? ("0" + m) : m;
            var s = d.getSeconds();
            s = (s < 10) ? ("0" + s) : s;
            datetext = datetext + " " + h + ":" + m + ":" + s;
            $('#bidform-time_end').val(datetext);

        },
    });
</script>
<?php $this->registerJs(preg_replace('~^\s*<script.*>|</script>\s*$~ U', '', ob_get_clean())) ?>
