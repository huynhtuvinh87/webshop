<div class="menu-seller">
	<a href="javascript:void(0)" class="btn btn-default btn-menu">Danh mục</a>
	<ul class="list-group list-menu">
	  	<li class="list-group-item">Tổng quan</li>
        <li class="list-group-item <?= empty($_GET['type']) ? "list-group-item-success" : "" ?>"><a href="/nha-vuon/<?= $model->username ?>"><span>Thông tin cơ bản</span></a></li>
        <li class="list-group-item <?= (!empty($_GET['type']) && ($_GET['type']=="certification")) ? "list-group-item-success" : "" ?>"><a href="/nha-vuon/<?= $model->username ?>?type=certification"><span>Giấy phép, chứng nhận</span></a></li>
        <li class="list-group-item <?= (!empty($_GET['type']) && ($_GET['type']=="review")) ? "list-group-item-success" : "" ?>"><a href="/nha-vuon/<?= $model->username ?>?type=review"><span>Đánh giá và nhận xét</span></a></li>
        <li class="list-group-item <?= (!empty($_GET['type']) && ($_GET['type']=="product")) ? "list-group-item-success" : "" ?>"><a href="/nha-vuon/<?= $model->username ?>?type=product"><span>Sản phẩm</span></a></li>
        <li class="list-group-item <?= (!empty($_GET['type']) && ($_GET['type']=="history")) ? "list-group-item-success" : "" ?>"><a href="/nha-vuon/<?= $model->username ?>?type=history"><span>Lịch sử giao dịch</span></a></li>
        <li class="list-group-item <?= (!empty($_GET['type']) && ($_GET['type']=="static")) ? "list-group-item-success" : "" ?>"><a href="/nha-vuon/<?= $model->username ?>?type=static"><span>Thống kê</span></a></li>
	</ul>
</div>