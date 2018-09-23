<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use common\components\Constant;

if ($static) {
    ?>
    <table class="table table-striped table-bordered table-responsive">
        <thead>
            <tr><th>Tỉnh thành</th><th>Số lượng bán</th><th>Doanh thu</th><th>Tỉ lệ %</th></tr>
        </thead>
        <tbody>
            <?php
            $totalQtt = array_sum(array_column($static, 'totalQtt'));
            foreach ($static as $value) {
                ?>
                <tr>
                    <td><?= $value['_id']['province']['name'] ?></td>
                    <td><?= $value['totalQtt'] ?></td>
                    <td><?= Constant::price($value['totalAmount']) ?></td>
                    <td><?= round($value['totalQtt']/$totalQtt*100,2)?> %</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
}
?>
