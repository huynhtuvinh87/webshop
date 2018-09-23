<?php 
use yii\widgets\ListView;

$this->params['breadcrumbs'][] = $this->title;
 ?>

<div class="container">

 	<div id="page-notification">
        <?php if ($dataProvider->totalCount > 0) { ?>
 		<a id="check-read-all" href="/notification/checkall">Đánh dấu tất cả là đã xem</a>
        <?php } ?>
		<div class="content-notification">
			<ul class="list-item">
				<?=
                ListView::widget([
                    'dataProvider' => $dataProvider,
                    'emptyText' => 'Chưa có thông báo nào.',
                    'layout' => "{items}\n<div class='row'><div class='col-sm-12 pagination-page'>{pager}</div></div>",
                    'itemView' => '/notification/_item',
                ]);
                ?>
			</ul>
		</div>
 	</div>

</div>

<?php ob_start(); ?>
<script>
        $('#page-notification .read').click(function(){
        	var id = $(this).data('id');
        	var url = $(this).data('href');
        	$.ajax({
	            url: '<?= Yii::$app->urlManager->createUrl(["notification/status"]); ?>',
	            type: 'POST',
	            data: 'id='+id,
	            success: function (data) {
	            	$(location).attr('href', url);
	         	}
	        });
        });

        $('#check-read-all').click(function() {
            return confirm('Bạn có muốn đánh dấu đã xem hết?');
        });

</script>
<?php $this->registerJs(preg_replace('~^\s*<script.*>|</script>\s*$~ U', '', ob_get_clean())) ?>