<?php

use common\components\Constant;

if ($model->price['min'] == $model->price['max']) {
    $price = Constant::price($model->price['min']);
} else {
    $price = Constant::price($model->price['min']) . ' - ' . Constant::price($model->price['max']);
}
?>
<div class="product" id="product-<?= $model->id ?>">
    <div class="product-img">
        <a class="remove" title="Xóa sản phẩm" href="/product/delete/<?= $model->id?>"><i class="fa fa-remove"></i></a>
        <img  class="lazyload" data-src="<?= Yii::$app->setting->get('siteurl_cdn') ?>/image.php?src=<?= $model->images[0] ?>&size=250&250"  src="<?= Yii::$app->setting->get('siteurl_cdn') ?>/image.php?src=images/default.gif&size=250x250">
    </div>
    <div class="product-info">
        <h4 class="product-title">
            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["product/update/" . $model->id]) ?>"><?= Constant::excerpt($model->title,30) ?></a>
        </h4>
        <div class="desc">
            <p class="price" style="display: flex">
                <?= $price ?> đ
            </p>
            <div class="bottom">
                <div class=" minimum">
                    Tối thiểu: <?= !empty($model->quantity_min) ? $model->quantity_min : 0 ?> <?= $model->unit ?>
                </div>
       
            </div>

            <div class="product-act" style="margin-top:10px">

                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["product/update/" . $model->id]) ?>" class="btn btn-primary btn-sm pull-left">Chi tiết</a>

                <?php if ($model->status == Constant::STATUS_BLOCK) { ?>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["product/unblock/" . $model->id]) ?>" class="btn btn-danger btn-sm pull-right enblock">Đã có hàng</a>
                <?php } ?>

                <?php if ($model->status == Constant::STATUS_ACTIVE) { ?>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["product/enblock/" . $model->id]) ?>" class="btn btn-danger btn-sm pull-right block">Đã hết hàng</a>
                <?php } ?>

                <?php if ($model->status == Constant::STATUS_CANCEL) { ?>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["product/cancel/" . $model->id]) ?>" class="btn btn-danger btn-sm pull-right cancel">Lý do bị từ chối</a>
                <?php } ?>


            </div>
        </div>
    </div>
</div><!-- item-info -->
