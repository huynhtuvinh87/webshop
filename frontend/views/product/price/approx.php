<?php

use common\components\Constant;

$qtt = $array = [];
if (!empty($model['quantity_purchase']) && $model['quantity_purchase'] > 0) {
    $qtt_max = $model['quantity_stock'] - $model['quantity_purchase'];
} else {
    $qtt_max = $model['quantity_stock'];
}
?>
<div class="list-price" style="margin-top:10px; margin-bottom: 10px">

    <ul class="row">
        <?php
        foreach ($model->approx as $value) {
            $qtt[] = $value['quantity_min'];
            $qtt[] = $value['quantity_max'];
            $array[] = $value['price'];
            if (($value['quantity_max'] <= $qtt_max) or ( $value['quantity_min'] <= $qtt_max && $qtt_max <= $value['quantity_max'])) {
                ?>
                <li class="col-xs-3 col-sm-3">
                    <p class="text-red"><?= Constant::price($value['price']) ?> đ</p>
                    <?php
                    if ($value['quantity_max'] > $qtt_max && (($qtt_max - $value['quantity_min']) < 2)) {
                        $q = '> ' . $value['quantity_min'];
                    } elseif ($value['quantity_max'] > $qtt_max) {
                        $q = $value['quantity_min'] . ' - ' . $qtt_max;
                    } else {
                        $q = $value['quantity_min'] . ' - ' . $value['quantity_max'];
                    }
                    ?>
                    <?= $q ?> <span class="unit"><?= $model->unit ?></span>
                </li>
                <?php
            }
        }
        ?>
    </ul>

</div>


<div class="quantity">
    <?php
    if ($model['quantity_min'] > $qtt_max) {
        echo '<p>Tạm thời hết hàng</p>';
    } else {
        ?>
        <label>Số lượng</label> <i class="fa fa-minus" aria-hidden="true"></i>
        <input type="number" id="cart-quantity" name="Cart[quantity]" class="qty" value="<?= min($qtt) ?>" min="<?= min($qtt) ?>" max="<?= $qtt_max > 0 ? $qtt_max : max($qtt) ?>"> <i class="fa fa-plus" aria-hidden="true"></i>
        <input type="hidden" id="cart-kind" name="Cart[kind]" value="0">
        <span class="unit"><?= $model->unit ?></span>
        <p style="margin-top:10px; color: red; margin-bottom: -10px"><small id="quantity-error"></small> </p>
        <?php
    }
    ?>
</div>

<?php
if (min($array) == max($array)) {
    $price = Constant::price(min($array));
} else {
    $price = Constant::price(min($array)) . ' - ' . Constant::price(max($array));
}
?>
<?php ob_start(); ?>

<script type="text/javascript">
    $(".product-price").show();
    $(".product-price .orange").html("<?= $price ?>");
    $(".list-kind .btn").on("click", function (event, state) {
        var key = $(this).attr('data-key');
        var min = $(this).attr('data-min');
        var max = $(this).attr('data-max');
        $(".list-kind .btn").removeClass('active');
        $(this).addClass('active');
        $('.dropdown-classify a small').html($(this).text());
        $(".list-price").hide();
        $("#list-price-" + key).show();
        $("#cart-quantity").val(min);
        $("#cart-quantity").attr('min', min);
        $("#cart-quantity").attr('max', max);
    });

</script>
<?php $this->registerJs(preg_replace('~^\s*<script.*>|</script>\s*$~ U', '', ob_get_clean())) ?>
