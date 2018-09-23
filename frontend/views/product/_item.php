<?php

use common\components\Constant;

if ($model->price['min'] == $model->price['max']) {
    $price = Constant::price($model->price['min']);
} else {
    $price = Constant::price($model->price['min']) . ' - ' . Constant::price($model->price['max']);
}
?>
<div class="item">
    <a class="thumb" href="<?= $model->url ?>"> <img class="lazyload" data-src="<?= Yii::$app->setting->get('siteurl_cdn') ?>/image.php?src=<?= $model->images[0] ?>&size=370x300" src="<?= Yii::$app->setting->get('siteurl_cdn') ?>/image.php?src=images/default.gif&size=300x250" alt="<?= $model->title ?>"> </a>
    <div class="desc">

        <h4 class="title"><a href="<?= $model->url ?>" title="<?= $model->title ?>"><?= Constant::excerpt($model->title, 30) ?></a></h4>
        <div class="price">
            <?= $price ?> đ
            <span>/ <?= $model->unit ?></span>
        </div>
        <div class="sale-place"><i class="fa fa-map-marker" aria-hidden="true"></i> <a href="/nha-vuon/<?= $model->owner['username'] ?>"><?= $model->owner['garden_name'] ?></a> </div>
        <div class="bottom">
            <div class="pull-left minimum">
                Tối thiểu: <?= !empty($model->quantity_min) ? $model->quantity_min : 0 ?> <?= $model->unit ?>
            </div>
            <?php
            if ($model->countReview > 0) {
                ?>
                <div class="rating pull-right">

                    <div class="star">
                        <div class="empty-stars"></div>
                        <div class="full-stars" style="width:<?= $model->getTotalReview() * 20 ?>%"> </div>
                    </div>
                    (<?= $model->countReview ?>)

                </div>
                <?php
            }
            ?>
        </div>

        <?php
        if ($model->countdown) {
            ob_start();
            $uni = uniqid();
            ?>
            <script type="text/javascript">
                $("#countdown-<?= $uni ?>").countdown("<?= $model->time_end ?> 23:59:59", function (event) {
                    $(this).html(event.strftime('<i class="fa fa-clock-o" aria-hidden="true"></i>Còn %D ngày %H:%M:%S'));
                });
            </script>
            <?php $this->registerJs(preg_replace('~^\s*<script.*>|</script>\s*$~ U', '', ob_get_clean())) ?>
            <div class="countdown" id="countdown-<?= $uni ?>"></div>
            <?php
        }
        ?>
    </div>
</div>
