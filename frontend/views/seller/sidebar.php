<div id="sidebar" class="grid-sub">
    <div class="sidebar__inner">
        <div class="header-mobi">
            <a href="#" class="btn back"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Quay lại</a>
            Danh mục
        </div>
        <ul class="nav nav-list">
            <li class="nav-header nav-header-first">Tổng quan</li>
            <li class="<?= empty($_GET['type']) ? "active" : "" ?>"><a href="/nha-vuon/<?= $model->username ?>"><span>Thông tin cơ bản</span></a></li>
            <li class="<?= (!empty($_GET['type']) && ($_GET['type']=="certification")) ? "active" : "" ?>"><a href="/nha-vuon/<?= $model->username ?>?type=certification"><span>Giấy phép, chứng nhận</span></a></li>
            <li class="<?= (!empty($_GET['type']) && ($_GET['type']=="review")) ? "active" : "" ?>"><a href="/nha-vuon/<?= $model->username ?>?type=review"><span>Đánh giá và nhận xét</span></a></li>
            <li class="<?= (!empty($_GET['type']) && ($_GET['type']=="product")) ? "active" : "" ?>"><a href="/nha-vuon/<?= $model->username ?>?type=product"><span>Sản phẩm</span></a></li>
            <li class="<?= (!empty($_GET['type']) && ($_GET['type']=="history")) ? "active" : "" ?>"><a href="/nha-vuon/<?= $model->username ?>?type=history"><span>Lịch sử giao dịch</span></a></li>
            <li class="<?= (!empty($_GET['type']) && ($_GET['type']=="static")) ? "active" : "" ?>"><a href="/nha-vuon/<?= $model->username ?>?type=static"><span>Thống kê</span></a></li>
        </ul>
    </div>
</div>