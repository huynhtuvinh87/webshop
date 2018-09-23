<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Biểu đồ thống kê';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">
    <div class="col-sm-5">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Thống kê sản phẩm theo khu vực</h3></div>
            <div class="panel-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr><th>Sản phẩm</th><th>Số lượng</th><th>Tỉ lệ</th><th></th></tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($static['data'] as $value) {
                            ?>
                            <tr>
                                <td><?= $value->product_type['title'] ?></td>
                                <td>
                                    <?php
                                    if ($value->unit = 'Kg' && $value->quantity >= 100) {
                                        echo round($value->quantity / 100, 2) . ' tạ';
                                    }if ($value->unit = 'Kg' && $value->quantity >= 1000) {
                                        echo round($value->quantity / 100, 2) . ' tấn';
                                    } elseif ($value->unit = 'Kg') {
                                        echo $value->quantity . ' kg';
                                    } else {
                                        echo $value->quantity . ' ' . $value->unit;
                                    }
                                    ?>
                                </td>
                                <td>

                                    <?= round(($value->quantity / $static['sum']) * 100, 2) ?> %
                                </td>
                                <td>
                                    <?= Html::a('Chi tiết', ['index', 'id' => $value->id], ['class' => 'btn btn-primary btn-sm']) ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-7">
        <?php
        if (!empty($staticItem['static_item'])) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Thống kê <?= $staticItem['static']['product_type']['title'] ?> theo khu vực</h3></div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr><th>Tỉnh thành</th><th>Số lượng</th><th>Tỉ lệ</th></tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($staticItem['static_item'] as $value) {
                                ?>
                                <tr>
                                    <td><?= $value->province['name'] ?></td>
                                    <td>
                                        <?php
                                        if ($value->unit = 'Kg' && $value->quantity >= 100) {
                                            echo round($value->quantity / 100, 2) . ' tạ';
                                        } elseif ($value->unit = 'Kg' && $value->quantity >= 1000) {
                                            echo round($value->quantity / 100, 2) . ' tấn';
                                        } elseif ($value->unit = 'Kg' && $value->quantity < 100) {
                                            echo $value->quantity . ' kg';
                                        } else {
                                            echo $value->quantity . ' ' . $value->unit;
                                        }
                                        ?></td>
                                    <td>
                                        <?= round(($value->quantity / $staticItem['sum']) * 100, 2) ?> %
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>