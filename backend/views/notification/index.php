<?php

use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = 'Thông báo';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if ($dataProvider->totalCount > 0) { ?>
    <p><a id="check-read-all" href="/notification/checkall">Đánh dấu tất cả là đã xem</a></p>
<?php } ?>
<div class="content-notification">
    <?php Pjax::begin(['id' => 'pjax-grid-notify']); ?>    

    <?=
    ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'tag' => 'ul',
            'id' => 'list-notify',
            'class' => 'qna-list'
        ],
        'emptyText' => 'Chưa có thông báo nào.',
        'layout' => "{items}\n<div class='row'><div class='col-sm-12 pagination-page'>{pager}</div></div>",
        'itemView' => '/notification/_item',
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>

<?php ob_start(); ?>
<script>
    $('.read').click(function () {
        var id = $(this).data('id');
        var url = $(this).data('href');
        $.ajax({
            url: '<?= Yii::$app->urlManager->createUrl(["notification/status"]); ?>',
            type: 'POST',
            data: 'id=' + id,
            success: function (data) {
                $(location).attr('href', url);
            }
        });
    });

    $('.check-read').click(function () {
        var id = $(this).data('id');
        var count = parseInt($('.count_notify').text());

        $.ajax({
            url: '<?= Yii::$app->urlManager->createUrl(["notification/checkread"]); ?>',
            type: 'POST',
            data: 'id=' + id,
            success: function (data) {
                $('.count_notify').html(count - 1);
                $.pjax({container: '#pjax-grid-notify'});
                return false;
            }
        });
    });
    $('.remove').click(function () {
        var id = $(this).data('id');
        $.ajax({
            url: '<?= Yii::$app->urlManager->createUrl(["notification/remove"]); ?>',
            type: 'POST',
            data: 'id=' + id,
            success: function (data) {
                $.pjax({container: '#pjax-grid-notify'});
                return false;
            }
        });
    });
    $('#check-read-all').click(function () {
        return confirm('Bạn có muốn đánh dấu đã xem hết?');
    });

</script>
<?php $this->registerJs(preg_replace('~^\s*<script.*>|</script>\s*$~ U', '', ob_get_clean())) ?>