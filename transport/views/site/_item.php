<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use common\components\Constant;

$item = Yii::$app->transport->getItem((string) $model['_id']);
?>

<div class="row">

    <div class="col-sm-2">
        <p><strong>Điểm đi: </strong><?= $model['owner']['address'] ?>, <?= $model['owner']['ward']['name'] ?>, <?= $model['owner']['district']['name'] ?>, <?= $model['owner']['province']['name'] ?></p>
    </div>
    <div class="col-sm-2">
        <p><strong>Điểm đến: </strong><?= $model['owner']['address'] ?>, <?= $model['order']['ward'] ?>, <?= $model['order']['district'] ?>, <?= $model['order']['province'] ?></p>
    </div>
    <div class="col-sm-2">
        <strong>Loại xe: </strong>
        <?php
        if (!empty($model['transport'])) {
            switch ($model['transport']['type']) {
                case 1:
                    echo 'Xe tải, ' . $model['transport']['weight'] . ' tấn' . '<br>Thể tích xe: ' . $model['transport']['capacity'] . ' m<sup>3</sup>' . '<br><small> (Khối lượng sản phẩm là: ' . $model['product']['quantity'] . ' kg</small>)';
                    break;
                case 2:
                    echo 'Xe container, ' . $model['transport']['weight'] . ' tấn<br><small> (Khối lượng sản phẩm là: ' . $model['product']['quantity'] . ' kg</small>)';
                    break;
                default:
                    echo 'Xe đông lạnh, ' . $model['transport']['weight'] . ' tấn<br><small> (Khối lượng sản phẩm là: ' . $model['product']['quantity'] . ' kg</small>)';
            }
        } else {
            echo 'Xe vận chuyển đang cập nhật';
        }
        ?>
    </div>
    <div class="col-sm-2">
        <p><strong>Thời gian </strong></p>
        <p>Ngày nhận hàng: <?= $model['transport']['time_end'] ?></p>
        <p>Ngày giao hàng: <?= $model['transport']['time_begin'] ?></p>
    </div>
    <div class="col-sm-2">
        <p><strong>Mô tả: </strong></p>
        <p><?= $model['transport']['description'] ?></p>
    </div>

    <div class="col-sm-2">
        <?php
        if (!empty($item)) {
            ?>
            <a id="bid-<?= (string) $model['_id'] ?>" href="/site/bid/<?= (string) $model['_id'] ?>" class="btn btn-success bid btn-sm">Đã gửi báo giá</a>
            <a id="bid-<?= (string) $model['_id'] ?>" href="/ajax/remove/<?= (string) $model['_id'] ?>" data-id="<?= $item->getProductOrder()['bid'] ?>" class="btn btn-danger delete btn-sm">Huỷ báo giá</a>
            <?php
        } else {
            ?>
            <a id="bid-<?= (string) $model['_id'] ?>" href="/site/bid/<?= (string) $model['_id'] ?>" class="btn btn-primary bid btn-sm">Gửi báo giá</a>
            <?php
        }
        ?>
    </div>
</div>
