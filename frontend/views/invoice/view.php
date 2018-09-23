<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use common\components\Constant;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = 'Đơn hàng #' . $invoice['code'];
$this->params['breadcrumbs'][] = ['label' => 'Đơn hàng của bạn', 'url' => ['/invoice/history']];
$this->params['breadcrumbs'][] = $this->title;
?>


<?php $form = ActiveForm::begin(['id' => 'orderStatus']); ?>
<h4 class="detail-order" style="margin-top: 0; font-weight: 400; text-transform: uppercase;">Chi tiết đơn hàng</h4>
<div class="detail-info">
    <div class="pull-left">
        <p class="order-number"><?= $this->title ?></p>
        <p class="light-gray">Đặt ngày <?= date('d-m-Y', $invoice['created_at']) ?></p>
    </div>
    <div class="detail-right-info pull-right"><span class="detail-info-total-title">Tổng cộng: </span><span class="detail-info-total-value"><?= Constant::price($invoice->total) ?> VNĐ</span></div>
</div>
<?php
foreach ($order as $k => $value) {
    ?>
    <div class="package" id="<?= $value['code'] ?>">

        <div class="dummy-wrapper">
            <div class="package-header" style="border-bottom: 1px solid #eee; overflow: hidden">
                <div class="infor-seller">
                    <p class="text package-header-text"> Gói hàng <?= $k + 1 ?></p>
                    <p class="text">Cung cấp bởi <span class="link"><a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/nha-vuon/' . $value['owner']['username']]) ?>"><?= $value['owner']['garden_name'] ?></a></span></p>

                </div>

                <div class="im-chat">
                    <?php
                    if ($value['status'] == Constant::STATUS_ORDER_PENDING) {
                        ?>
                        <p style="margin-top:10px" class="text-red">
                            <?=
                            Html::a('Huỷ', '/invoice/delete/' . $value['id'].'?k='.$k, [
                                'title' => 'delete',
                                'data-confirm' => 'Bạn có muốn huỷ đơn hàng này không?',
                                'data-method' => 'post',
                            ]);
                            ?>
                        </p>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div id="steps">
            <ul class="option">
                <?php
                if ($value['status'] == Constant::STATUS_ORDER_BLOCK) {
                    ?>
                    <li>
                        <div style="float: left;" class="step step4 active" data-desc="Đơn hàng đã huỷ">
                            <i style="font-size: 25px;" class="fa fa-remove"></i>
                        </div>
                        <div class="translate" style="top: 70px; left: 10px"></div>
                    </li>
                    <?php
                } else {
                    ?>
                    <li>
                        <div class="step step1 <?= $value['status'] >= Constant::STATUS_ORDER_PENDING ? "done" : "" ?>" data-desc="Đang xử lý" >
                            <i class="icon-valid"></i>
                        </div>
                        <?= $value['status'] == Constant::STATUS_ORDER_PENDING ? '<div class="translate" style="top: 70px; left: 10px"></div>' : "" ?>
                    </li>
                    <li>
                        <div class="step <?= $value['status'] >= Constant::STATUS_ORDER_SENDING ? "done" : "" ?> step2 next" data-desc="Đang giao hàng">
                            <i class="icon-valid"></i>
                        </div>
                        <?= $value['status'] == Constant::STATUS_ORDER_SENDING ? '<div class="translate" style="top: 70px; right: 10px"></div>' : "" ?>
                    </li>
                    <?php if ($value['status'] == Constant::STATUS_ORDER_UNSUCCESSFUL) { ?>
                        <li>
                            <div class="step step3 unsuccessful" data-desc="Giao hàng không thành công">
                                <i style="font-size: 20px;" class="glyphicon glyphicon-exclamation-sign"></i>
                            </div>
                            <?= $value['status'] == Constant::STATUS_ORDER_UNSUCCESSFUL ? '<div class="translate" style="top: 90px; right: 10px"></div>' : "" ?>
                        </li>
                    <?php } else { ?>
                        <li>
                            <div class="step step3 <?= $value['status'] == Constant::STATUS_ORDER_FINISH ? "done" : "" ?>" data-desc="Giao hàng thành công">
                                <i class="icon-valid"></i>
                            </div>
                            <?= $value['status'] == Constant::STATUS_ORDER_FINISH ? '<div class="translate" style="top: 70px; right: 10px"></div>' : "" ?>
                        </li>
                    <?php } ?>
                    <?php
                }
                ?>
            </ul>

            <?php
            switch ($value['status']) {
                case Constant::STATUS_ORDER_PENDING:
                    $date = $value['created_at'] + 86400;
                    ?>
                    <div class="desc">
                        <p>Sản phẩm của bạn đang đợi nhà cung cấp xử lý!</p>
                        <p>Đơn hàng sẽ tự động hủy nếu nhà cung cấp không xử lý trước <?= date('h:s', $date) ?> ngày <?= date('d/m/Y', $date) ?></p>
                    </div>
                    <?php
                    break;
                case Constant::STATUS_ORDER_SENDING:
                    ?>
                    <div class="desc">
                        <p>Đơn hàng của bạn đang được giao</p>
                        <p>Bắt đầu giao vào lúc: <?= date('h:i', $value['date_begin']) ?> ngày <?= date('d/m/Y', $value['date_begin']) ?></p>
                        <p>Thời gian dự kiến nhận:  <?= date('h:i', $value['date_end']) ?> ngày <?= date('d/m/Y', $value['date_end']) ?></p>
                        <?= $value['transport'] ? '<p>Thông tin người vận chuyển: ' . $value['transport'] . '</p>' : "" ?>
                    </div>
                    <?php
                    break;
                case Constant::STATUS_ORDER_UNSUCCESSFUL:
                    echo '<div style="margin-top: 60px;" class="desc"><p>Đơn hàng của bạn giao không thành công.<p>';
                    echo "<b>Lý do: </b>";
                    if (!empty($value->content)) {
                            foreach ($value->content as $reason) {
                                echo '<p>- ' . $reason . '</p>';
                            }
                    }
                    echo "</div>";
                    break;
                case Constant::STATUS_ORDER_FINISH:
                    echo '<div class="desc"><p>Đơn hàng của quý khách đã được giao thành công. Cám ơn quý khách đã mua sắm tại Vinagex.<p></div>';
                    break;
                case Constant::STATUS_ORDER_BLOCK:
                    echo '<div class="desc"><p>Đơn hàng của bạn đã huỷ.<p>';
                    echo "<b>Lý do hủy: </b>";
                    if (!empty($value->content)) {
                            foreach ($value->content as $reason) {
                                echo '<p>- ' . $reason . '</p>';
                            }
                    } else {
                        echo '<p>- Đơn hàng bị hủy tự động do trong vòng 24h nhà cung cấp không xử lý đơn hàng.</p>';
                    }
                    echo "</div>";
                    break;
            }
            ?>


        </div>
        <div style="padding: 15px">
            <div class="panel panel-default panel-order">
                <div class="panel-heading" style="padding-left:0">
                    Sản phẩm
                </div>
                <div>
                    <?php
                    foreach ($value->product as $k => $item) {
                        ?>
                        <div class="row order-item" style="margin-top: 15px">
                            <div class="col-sm-5 col-xs-12 ">
                                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/' . $item["slug"] . '-' . $item['id']]) ?>">
                                    <img src="<?= Yii::$app->setting->get('siteurl_cdn') ?>/image.php?src=<?= $item['image'] ?>&size=60x60" style="margin-bottom: 10px; border-radius: 4px; float: left; margin-right: 10px">
                                    <div>
                                        <?= $item['title'] ?>

                                        <?php
                                        if ($value['status'] == Constant::STATUS_ORDER_FINISH) {
                                            ?>
                                            <p style="margin-top:10px">
                                                <button type="button" class="btn btn-primary btn-sm modal-review" data-id="<?= $item['id'] ?>" data-title="<?= $item['title'] ?>">Viết nhận xét</button>
                                            </p>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-2  col-xs-12">
                                Số lượng: <?= $item['quantity'] ?> <?= $item['unit'] ?>
                            </div>
                            <div class="col-sm-2 col-xs-12">
                                <?= Constant::price($item['price']) ?> đ
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                Thành tiền: <?= Constant::price($item['price'] * $item['quantity']) ?> đ
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
<div class="row">
    <div class="info-order">
        <div class="col-md-6">

            <div class="delivery-wrapper">
                <h3 style="margin-top:0">Địa chỉ nhận hàng</h3>
                <p class="username"><strong>Họ tên: </strong><?= $invoice->name ?></p>
                <p class="address"><strong>Địa chỉ: </strong><?= $invoice->address ?>, <?= $invoice->ward ?>, <?= $invoice->district ?>, <?= $invoice->province ?></p>
                <p><strong>Số điện thoại: </strong><?= $invoice->phone ?></p>
            </div>

        </div>
        <div class="col-md-6">
            <div class="total-summary">
                <h3 style="margin-top:0">Tổng cộng</h3>
                <div class="rows"><p class="pull-left">Tạm tính: </p><p class="pull-right"><?= Constant::price($invoice->total) ?> đ</p></div>
                <div class="rows"><p class="pull-left">Phí vận chuyển: </p><p class="pull-right">Thoả thuận</p></div>
                <hr>
                <div class="rows"><p class="pull-left">Tổng cộng: </p><p class="pull-right total-price"><?= Constant::price($invoice->total) ?> đ</p></div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php ob_start(); ?>
<script type="text/javascript">
    $('.modal-review').click(function () {
        $('#modalHeader span').html('Đánh giá sản phẩm: ' + $(this).attr('data-title'))
        $.get('/review/index/' + $(this).attr('data-id'), function (data) {

            $('#modal-review').modal('show').find('#modalContent').html(data)
        });
        return false;
    });
</script>
<?php $this->registerJs(preg_replace('~^\s*<script.*>|</script>\s*$~ U', '', ob_get_clean())) ?>
<?php
yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'header' => '<span>Đánh giá sản phẩm</span>',
    'id' => 'modal-review',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
echo "<div id='modalContent'><div style=\"text-align:center\"><img src=\"/template/images/loading.gif\"></div></div>";
yii\bootstrap\Modal::end();
?>
