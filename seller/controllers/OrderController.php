<?php

namespace seller\controllers;

use Yii;
use yii\widgets\ActiveForm;
use common\models\Order;
use common\components\Constant;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\mongodb\Query;
use seller\models\ProductOrderForm;
use seller\models\OrderFilter;
use seller\models\OrderForm;

class OrderController extends ManagerController {

    public function init() {
        parent::init();
    }

    public function actionFilter() {
        $filter = new OrderFilter();
        $order = new Order();
        $dataProvider = $filter->fillter(Yii::$app->request->queryParams);
        $this->view->title = 'Đơn hàng của bạn';
        return $this->render('index', ['dataProvider' => $dataProvider, 'order' => $order]);
    }

    public function actionIndex() {
        $order = new Order();
        $dataProvider = new ActiveDataProvider([
            'query' => $order::find()->where(['owner.id' => \Yii::$app->user->id, 'status' => Constant::STATUS_ORDER_PENDING])->orderBy('created_at DESC'),
            'pagination' => [
                'defaultPageSize' => 20
            ],
        ]);
        $this->view->title = 'Đơn hàng đang xử lý';

        return $this->render('index', ['dataProvider' => $dataProvider, 'order' => $order]);
    }

    public function actionSending() {
        $order = new Order();

        $dataProvider = new ActiveDataProvider([
            'query' => $order::find()->where(['owner.id' => \Yii::$app->user->id, 'status' => Constant::STATUS_ORDER_SENDING])->orderBy('created_at DESC'),
            'pagination' => [
                'defaultPageSize' => 20
            ],
        ]);
        $this->view->title = 'Đơn hàng đang giao';

        return $this->render('index', ['dataProvider' => $dataProvider, 'order' => $order]);
    }

    public function actionUnsuccessful() {
        $order = new Order();

        $dataProvider = new ActiveDataProvider([
            'query' => $order::find()->where(['owner.id' => \Yii::$app->user->id, 'status' => Constant::STATUS_ORDER_UNSUCCESSFUL])->orderBy('created_at DESC'),
            'pagination' => [
                'defaultPageSize' => 20
            ],
        ]);
        $this->view->title = 'Đơn hàng không thành công';

        return $this->render('index', ['dataProvider' => $dataProvider, 'order' => $order]);
    }

    public function actionFinish() {
        $order = new Order();

        $dataProvider = new ActiveDataProvider([
            'query' => $order::find()->where(['owner.id' => \Yii::$app->user->id, 'status' => Constant::STATUS_ORDER_FINISH])->orderBy('created_at DESC'),
            'pagination' => [
                'defaultPageSize' => 20
            ],
        ]);
        $this->view->title = 'Đơn hàng đã hoàn thành';

        return $this->render('index', ['dataProvider' => $dataProvider, 'order' => $order]);
    }

    public function actionBlock() {
        $order = new Order();
        $dataProvider = new ActiveDataProvider([
            'query' => $order::find()->where(['owner.id' => \Yii::$app->user->id, 'status' => Constant::STATUS_ORDER_BLOCK])->orderBy('created_at DESC'),
            'pagination' => [
                'defaultPageSize' => 20
            ],
        ]);
        $this->view->title = 'Đơn hàng đã hủy';

        return $this->render('index', ['dataProvider' => $dataProvider, 'order' => $order]);
    }

    public function actionView($id) {
        $dataProvider = new ActiveDataProvider([
            'query' => (new Query)->from('product_order')->where(['owner.id' => \Yii::$app->user->id, 'order.code' => (int) $id])->orderBy('created_at DESC'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $this->view->title = 'Mã đơn hàng #' . $id;
        return $this->render('index', ['dataProvider' => $dataProvider, 'model' => $model]);
    }

    public function actionShipping($id) {
        $order = (new Query)->from('order')->where(['code' => (int) $id])->one();
        if (!$order) {
            throw new NotFoundHttpException('Trang này không tồn tại trong hệ thống.');
        }
        $status = [];
        foreach ($order['product'] as $value) {
            $status[] = $value['status'];
        }

        if (!in_array(1, $status)) {
            return "Tất cả sản phẩm trong đơn hàng của bạn đã hết hàng.";
        }

        $model = new ProductOrderForm();
        $model->id = $id;
        return $this->renderAjax('shipping', ['model' => $model]);
    }

    public function actionUnsuccessfulform($id) {
        $model = new OrderForm();
        $order = (new Query)->from('order')->where(['code' => (int) $id])->one();
        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            foreach ($model['reason'] as $value) {
                if ($value != $model::OTHER_BLOCK) {
                    $data[] = $model->block()[$value];
                }
            }

            if (!empty($model['description'])) {
                $data[] = $model['description'];
            }

            if (!empty($data)) {
                //status
                Yii::$app->mongodb->getCollection('order')->update(['code' => (int) $id], [
                    'status' => Constant::STATUS_ORDER_UNSUCCESSFUL,
                    'content' => $data,
                ]);
                //mail
                Yii::$app->mongodb->getCollection('mail')->insert([
                    'title' => 'Kiện hàng #' . $id . ' của bạn được giao không thành công',
                    'type' => 'order_failed',
                    'code' => (int) $id,
                    'layout' => 'order_failed',
                    'created_at' => time()
                ]);

                $this->unsuccessful($order);


                Yii::$app->session->setFlash('success', "Xử lý đơn hàng thành công");
                return $this->redirect(['sending']);
            } else {
                Yii::$app->session->setFlash('danger', "Xử lý đơn hàng thất bại. Vui lòng nhập lý do đơn hàng không thành công.");
                return $this->redirect(['sending']);
            }
        }
        return $this->renderAjax('/order/_unsuccessful', ['model' => $model]);
    }

    public function actionBlockform($id) {
        $model = new OrderForm(['code' => $id]);
        $order = (new Query)->from('order')->where(['code' => (int) $id])->one();
        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            foreach ($model['reason'] as $value) {
                if ($value != $model::OTHER_BLOCK) {
                    $data[] = $model->block()[$value];
                }
            }

            if (!empty($model['description'])) {
                $data[] = $model['description'];
            }

            if (!empty($data)) {
                //status
                Yii::$app->mongodb->getCollection('order')->update(['code' => (int) $id], [
                    'status' => Constant::STATUS_ORDER_BLOCK,
                    'content' => $data,
                ]);
             
                Yii::$app->mongodb->getCollection('notification')->insert([
                    'type' => 'buyer',
                    'owner' => $order['buyer']['id'],
                    'content' => 'Kiện hàng: <b>#' . $id . '</b> đã bị hủy.',
                    'url' => '/invoice/view/' . $order['invoice'] . '#' . $order['code'],
                    'status' => 0,
                    'created_at' => time()
                ]);

                //mail
                Yii::$app->mongodb->getCollection('mail')->insert([
                    'title' => 'Đơn hàng #' . $id . ' của bạn đã được hủy trên hệ thống',
                    'type' => 'order_cancel',
                    'code' => (int) $id,
                    'layout' => 'order_cancel',
                    'created_at' => time()
                ]);

                Yii::$app->session->setFlash('success', "Hủy đơn hàng thành công");
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', "Hủy đơn hàng thất bại. Vui lòng nhập lý do hủy đơn hàng.");
                return $this->redirect(['index']);
            }
        }
        return $this->renderAjax('/order/_block', ['model' => $model]);
    }

    public function actionFinishform($id) {
        $model = new OrderForm(['code' => $id]);
        $order = (new Query)->from('order')->where(['code' => (int) $id])->one();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if (!empty($model['level_satisfaction'])) {
                $data['level_satisfaction'] = (int) $model['level_satisfaction'];
            } else {
                Yii::$app->session->setFlash('danger', "Bạn chưa chọn mức độ hài lòng của bạn đối với khách hàng.Vui lòng chọn mức độ hài lòng của bạn");
                return $this->redirect(['sending']);
            }

            if (!empty($model['description'])) {
                $data['description'] = $model['description'];
            }

            $data['owner'] = [
                'id' => Yii::$app->user->id,
                'fullname' => Yii::$app->user->identity->fullname,
                'username' => Yii::$app->user->identity->username,
                'garden_name' => Yii::$app->user->identity->garden_name,
                'province' => Yii::$app->user->identity->province,
                'district' => Yii::$app->user->identity->district,
                'ward' => Yii::$app->user->identity->ward,
                'address' => Yii::$app->user->identity->address
            ];

            $data['buyer'] = $order['buyer'];
            $data['order_id'] = (string) $order['_id'];
            $data['product'] = $order['product'];

            if (time() >= $order['date_end']) {

                foreach ($order['product'] as $value) {
                    Yii::$app->mongodb->getCollection('static')->insert([
                        'owner' => $order['owner']['id'],
                        'product' => [
                            'id' => $value['id'],
                            'slug' => $value['slug'],
                            'title' => $value['title']
                        ],
                        'province' => [
                            'id' => $order['province_id'],
                            'name' => $order['buyer']['province']
                        ],
                        'price' => (int) $value['price'],
                        'quantity' => (int) $value['quantity'],
                        'created_at' => time(),
                        'updated_at' => time(),
                    ]);
                }

                Yii::$app->mongodb->getCollection('order')->update(['_id' => (string) $order['_id']], ['status' => (int) Constant::STATUS_ORDER_FINISH]);

                //mail
                Yii::$app->mongodb->getCollection('mail')->insert([
                    'title' => 'Kiện hàng #' . $id . ' của bạn đã được giao thành công',
                    'type' => 'order_complete',
                    'code' => (int) $id,
                    'layout' => 'order_complete',
                    'created_at' => time()
                ]);
             
                Yii::$app->mongodb->getCollection('notification')->insert([
                    'type' => 'buyer',
                    'owner' => $order['buyer']['id'],
                    'content' => 'Kiện hàng: <b>#' . $id . '</b> đã được giao thành công.',
                    'url' => '/invoice/view/' . $order['invoice'] . '#' . $order['code'],
                    'status' => 0,
                    'created_at' => time()
                ]);

                if (!empty($data)) {
                    Yii::$app->mongodb->getCollection('review_buyer')->insert($data);
                    Yii::$app->session->setFlash('success', "Xử lý đơn hàng thành công");
                    return $this->redirect(['sending']);
                }
            } else {
                Yii::$app->session->setFlash('warning', "Sản phẩm của bạn hình như chưa đến tay khách hàng! Bạn không thể hoàn thành đơn hàng trước thời gian dự kiến (" . date('d/m/Y - H:i:s', $order['date_end']) . ") mà khách hàng có thể nhận hàng!");
                return $this->redirect(['sending']);
            }
        }

        return $this->renderAjax('/order/finish', ['model' => $model, 'order' => $order]);
    }

    public function actionRemove($id) {
        $order = (new Query)->from('order')->where(['code' => (int) $id])->one();
        if (!$order) {
            throw new NotFoundHttpException('Trang này không tồn tại trong hệ thống.');
        }

        Yii::$app->mongodb->getCollection('order')->remove(['code' => (int) $id]);
        return $this->redirect(['index']);
    }

    public function unsuccessful($order) {

        foreach ($order['product'] as $value) {
            $product = (new Query)->from('product')->where(['_id' => $value['id']])->one();
            if ($value['status'] == 1) {
                $data = [];
                if (!empty($product['classify'])) {
                    $key = array_search($value['type'], array_column($product['classify'], 'id'));
                    $classify = $product['classify'][$key];
                    $qtt = $classify['quantity_purchase'];
                    $qtt_purchase_total = $classify['quantity_purchase_total'];
                    $remain_quantity = $classify['quantity_stock'] - ($qtt - (int) $value['quantity']);
                    $data['classify.' . $key . '.quantity_purchase_total'] = $qtt_purchase_total - (int) $value['quantity'];
                    $data['classify.' . $key . '.quantity_purchase'] = $qtt - (int) $value['quantity'];
                    $data['classify.' . $key . '.status'] = 1;
                } else {
                    $qtt = $product['quantity_purchase'];
                    $qtt_purchase_total = $product['quantity_purchase_total'];
                    $remain_quantity = $product['quantity_stock'] - ($qtt - (int) $value['quantity']);
                    $data['quantity_purchase'] = $qtt - (int) $value['quantity'];
                    $data['quantity_purchase_total'] = $qtt_purchase_total - (int) $value['quantity'];
                }
                $data['status'] = Constant::STATUS_ACTIVE;

                $order_product = (new Query)->from('order')->where(['product.id' => (string) $value['id'], 'status' => Constant::STATUS_ORDER_PENDING])->all();

                foreach ($order_product as $item_order) {
                    foreach ($item_order['product'] as $item_product) {
                        if ($item_product['id'] == $value['id'] && $item_product['quantity'] <= $remain_quantity) {
                            $k = array_search($item_product['id'], array_column($item_order['product'], 'id'));
                            Yii::$app->mongodb->getCollection('order')->update(['_id' => (string) $item_order['_id']], ['$set' => [
                                    'product.' . $k . '.status' => 1,
                            ]]);
                        }
                    }
                }
                //notification
                Yii::$app->mongodb->getCollection('notification')->insert([
                    'type' => 'seller',
                    'owner' => \Yii::$app->user->id,
                    'content' => 'Kiện hàng #<b>' . $order['code'] . '</b> giao không thành công.',
                    'url' => '/product/filter?keywords=' . $product['_id'],
                    'status' => 0,
                    'created_at' => time()
                ]);

                Yii::$app->mongodb->getCollection('product')->update(['_id' => $value['id']], $data);
            } else if ($value['status'] == 0) {
                if (!empty($product['classify'])) {
                    $k = array_search($value['type'], array_column($product['classify'], 'id'));
                    $classify = $product['classify'][$k];
                    $remain_quantity = $classify['quantity_stock'] - $classify['quantity_purchase'];
                } else {
                    $remain_quantity = $product['quantity_stock'] - $product['quantity_purchase'];
                }

                if ($value['quantity'] <= $remain_quantity) {
                    $k = array_search($value['id'], array_column($order['product'], 'id'));
                    Yii::$app->mongodb->getCollection('order')->update(['_id' => (string) $order['_id']], ['$set' => [
                            'product.' . $k . '.status' => 1,
                    ]]);
                }
            }
        }
    }

}
