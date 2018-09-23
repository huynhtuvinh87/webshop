<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use common\models\Order;
use common\models\Invoice;
use common\components\Constant;
use yii\mongodb\Query;
use common\events\SendMailEvent;
use frontend\controllers\FrontendController;

class CartController extends FrontendController {

    public $_cookies;
    public $_cart;
    public $_province;

    public function init() {
        parent::init();
        $this->_cookies = Yii::$app->request->cookies;
        $this->_cart = \Yii::$app->cart;
        $this->_province = Yii::$app->province;
    }

    public function beforeAction($action) {
        Yii::$app->controller->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionAdd() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = $_POST['id'] . 'type' . $_POST['kind'];
        $item = $this->_cart->getItem($id);
        if (!empty($item)) {
            return $this->plus([
                        'id' => $_POST['id'],
                        'kind' => $_POST['kind'],
                        'quantity' => (int) $_POST['quantity'],
                        'type' => $_POST['type']
            ]);
        } else {
            return $this->add([
                        'id' => $_POST['id'],
                        'kind' => $_POST['kind'],
                        'quantity' => (int) $_POST['quantity'],
                        'type' => $_POST['type']
            ]);
        }
    }

    public function add($params) {
        $product = (new Query())->from('product')->where(['_id' => $params['id']])->one();
        $kind = (int) $params['kind'];
        $quantity = $params['quantity'];
        if ($product['price_type'] == 3) {
            if (!empty($product['classify'][$kind]['quantity_purchase']))
                $qtt_max = $product['classify'][$kind - 1]['quantity_stock'] - $product['classify'][$kind - 1]['quantity_purchase'];
            else
                $qtt_max = $product['classify'][$kind - 1]['quantity_stock'];
            if ($quantity < $product['classify'][$kind - 1]['quantity_min']) {
                return ['error' => 'Số lượng mua tối thiểu là ' . $product['classify'][$kind - 1]['quantity_min'] . ' ' . $product['unit']];
            }
            if ($quantity > $qtt_max) {
                return ['error' => 'Số lượng mua tối đa là ' . $qtt_max . ' ' . $product['unit']];
            }
        } else {
            if (!empty($product['quantity_purchase']))
                $qtt_max = $product['quantity_stock'] - $product['quantity_purchase'];
            else
                $qtt_max = $product['quantity_stock'];
            if ($quantity < $product['quantity_min']) {
                return ['error' => 'Số lượng mua tối thiểu là ' . $product['quantity_min'] . ' ' . $product['unit']];
            }
            if ($quantity > $qtt_max) {
                return ['error' => 'Số lượng mua tối đa là ' . $qtt_max . ' ' . $product['unit']];
            }
        }
        $this->_cart->add($product, $params['quantity'], $params['kind']);
        if ($params['type'] == 'buynow') {
            return $this->redirect(['checkout']);
        }
        return ['count' => $this->_cart->getTotalCount()];
    }

    public function plus($params) {
        $product = (new Query())->from('product')->where(['_id' => $params['id']])->one();
        $id = $params['id'] . 'type' . $params['kind'];
        $item = $this->_cart->getItem($id);
        $quantity = $item->getQuantity() + $params['quantity'];
        if ($product['price_type'] == 3) {
            if (!empty($product['classify'][$item->getType() - 1]['quantity_purchase']))
                $qtt_max = $product['classify'][$item->getType() - 1]['quantity_stock'] - $product['classify'][$item->getType() - 1]['quantity_purchase'];
            else
                $qtt_max = $product['classify'][$item->getType() - 1]['quantity_stock'];

            if ($quantity < $product['classify'][$item->getType() - 1]['quantity_min']) {
                return ['error' => 'Số lượng mua tối thiểu là ' . $product['classify'][$item->getType() - 1]['quantity_min'] . ' ' . $product['unit']];
            }
            if ($quantity > $qtt_max) {
                return ['error' => 'Số lượng mua tối đa là ' . $qtt_max . ' ' . $product['unit']];
            }
        } elseif ($product['price_type'] == 2) {
            if (!empty($product['quantity_purchase']))
                $qtt_max = $product['quantity_stock'] - $product['approx']['quantity_purchase'];
            else
                $qtt_max = $product['quantity_stock'];

            if ($quantity < $product['quantity_min']) {
                return ['error' => 'Số lượng mua tối thiểu là ' . $product['quantity_min'] . ' ' . $product['unit']];
            }
            if ($quantity > $qtt_max) {
                return ['error' => 'Số lượng mua tối đa là ' . $qtt_max . ' ' . $product['unit']];
            }
        } else {
            if (!empty($product['quantity_purchase']))
                $qtt_max = $product['quantity_stock'] - $product['quantity_purchase'];
            else
                $qtt_max = $product['quantity_stock'];
            if ($quantity < $product['quantity_min']) {
                return ['error' => 'Số lượng mua tối thiểu là ' . $product['quantity_min'] . ' ' . $product['unit']];
            }
            if ($quantity > $qtt_max) {
                return ['error' => 'Số lượng mua tối đa là ' . $qtt_max . ' ' . $product['unit']];
            }
        }
        $this->_cart->plus($id, $params['quantity']);
        if ($params['type'] == 'buynow') {
            return $this->redirect(['checkout']);
        }
        return ['count' => $this->_cart->getTotalCount()];
    }

    public function actionNumber() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $item = $this->_cart->getItem($_POST['id']);

        if ($_POST['type'] == 'abate') {
            $quantity = $item->getQuantity() - 1;
        } else {
            $quantity = $item->getQuantity() + 1;
        }
        $qtt = [];
        $product = (new Query())->from('product')->where(['_id' => $item->getProduct()['id']])->one();
        if ($product['price_type'] == 3) {
            if (!empty($product['classify'][$item->getType() - 1]['quantity_purchase']))
                $qtt_max = $product['classify'][$item->getType() - 1]['quantity_stock'] - $product['classify'][$item->getType() - 1]['quantity_purchase'];
            else
                $qtt_max = $product['classify'][$item->getType() - 1]['quantity_stock'];

            if ($quantity < $product['classify'][$item->getType() - 1]['quantity_min']) {
                return ['error' => 'Số lượng mua tối thiểu là ' . $product['classify'][$item->getType() - 1]['quantity_min'] . ' ' . $product['unit']];
            }
            if ($quantity > $qtt_max) {
                return ['error' => 'Số lượng mua tối đa là ' . $qtt_max . ' ' . $product['unit']];
            }
        } elseif ($product['price_type'] == 2) {
            if (!empty($product['approx']['quantity_purchase']))
                $qtt_max = $product['approx']['quantity_stock'] - $product['approx']['quantity_purchase'];
            else
                $qtt_max = $product['approx']['quantity_stock'];

            if ($quantity < $product['quantity_min']) {
                return ['error' => 'Số lượng mua tối thiểu là ' . $product['quantity_min'] . ' ' . $product['unit']];
            }
            if ($quantity > $qtt_max) {
                return ['error' => 'Số lượng mua tối đa là ' . $qtt_max . ' ' . $product['unit']];
            }
        } else {
            if (!empty($product['quantity_purchase']))
                $qtt_max = $product['quantity_stock'] - $product['quantity_purchase'];
            else
                $qtt_max = $product['quantity_stock'];
            if ($quantity < $product['quantity_min']) {
                return ['error' => 'Số lượng mua tối thiểu là ' . $product['quantity_min'] . ' ' . $product['unit']];
            }
            if ($quantity > $qtt_max) {
                return ['error' => 'Số lượng mua tối đa là ' . $qtt_max . ' ' . $product['unit']];
            }
        }
        $this->_cart->change($_POST['id'], $quantity);
        $price = 0;
        foreach ($this->_cart->getItems() as $value) {
            $price += $value->getQuantity() * $value->getPrice();
        }

        return ['quantity' => $quantity, 'total' => number_format($price, 0, '', '.'), 'count' => $this->_cart->getTotalCount(), 'price' => Constant::price($item->getPrice()), 'error' => ''];
    }

    public function actionChangequantity() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $item = $this->_cart->getItem($_POST['id']);
        $quantity = $_POST['quantity'];
        $qtt = [];
        $product = (new Query())->from('product')->where(['_id' => $item->getProduct()['id']])->one();
        if ($item->getType() > 0) {
            if (!empty($product['classify'][$item->getType() - 1]['frame'])) {
                foreach ($product['classify'][$item->getType() - 1]['frame'] as $k => $val) {
                    $qtt[] = $val['quantity_min'];
                    $qtt[] = $val['quantity_max'];
                }
                $error = 'Số lượng chỉ được mua từ ' . min($qtt) . ' đến ' . max($qtt);
            } else {
                $qtt[] = $product['classify'][$item->getType() - 1]['quantity'];
                $error = 'Số lượng mua không nhỏ hơn ' . min($qtt);
            }
            if ($quantity < min($qtt) or $quantity > max($qtt)) {
                return ['error' => $error];
            }
        } else {
            if (!empty($product['approx'])) {
                foreach ($product['approx'] as $k => $val) {
                    $qtt[] = $val['quantity_min'];
                    $qtt[] = $val['quantity_max'];
                }
                $error = 'Số lượng chỉ được mua từ ' . min($qtt) . ' đến ' . max($qtt);
                if ($quantity < min($qtt) or $quantity > max($qtt)) {
                    return ['error' => $error];
                }
            } else {
                $qtt_max = $product['quantity_stock'] - $product['quantity_purchase'];
                if ($quantity < $product['quantity_min']) {
                    return ['error' => 'Số lượng mua không nhỏ hơn ' . $quantity];
                }
                if ($quantity > $qtt_max) {
                    return ['error' => 'Số lượng mua không được lớn hơn ' . $qtt_max];
                }
            }
        }
        $this->_cart->change($_POST['id'], $quantity);
        $price = 0;
        foreach ($this->_cart->getItems() as $value) {
            $price += $value->getQuantity() * $value->getPrice();
        }

        return ['quantity' => $quantity, 'total' => number_format($price, 0, '', '.'), 'count' => $this->_cart->getTotalCount(), 'error' => ''];
    }

    public function actionCheckout() {
        $cart = \Yii::$app->cart;
        return $this->render('checkout', ['cart' => $cart]);
    }

    public function actionShipping() {
        $cart = \Yii::$app->cart;
        $model = new Invoice();
        $model->code = $this->code(rand(100000000, 999999999));
        $model->actor = \Yii::$app->user->id;
        $model->created_at = time();
        $price = 0;
        $seller = [];
        $array = [];
        foreach ($cart->getItems() as $value) {
            if (!empty($value->getPrice())) {
                $price += $value->getQuantity() * $value->getPrice();
            } else {
                $price += $value->getQuantity();
            }
            $array[] = [
                'id' => $value->getProduct()['id'],
                'title' => $value->getProduct()['title'],
                'slug' => $value->getProduct()['slug'],
                'type' => $value->getType() > 0 ? $value->getType() : 0,
                'image' => $value->getProduct()['image'],
                'url' => $value->getProduct()['url'],
                'price' => $value->getPrice(),
                'quantity' => $value->getQuantity(),
                'unit' => $value->getProduct()['unit'],
                'status' => 1,
                'seller_id' => $value->getProduct()['owner']['id'],
                'seller_username' => $value->getProduct()['owner']['username'],
                'seller_name' => $value->getProduct()['owner']['garden_name']
            ];
            if (\Yii::$app->user->id != $value->getProduct()['owner']['id']) {
                $seller[] = $value->getProduct()['owner']['id'];
            }
        }
        $model->product = $array;
        $model->total = $price;
        if ($model->load(Yii::$app->request->post())) {
            if (!\Yii::$app->user->identity->province)
                Yii::$app->mongodb->getCollection('user')->update(['_id' => Yii::$app->user->id], ['$set' => [
                        'fullname' => $model->name,
                        'province' => [
                            'id' => $model->province,
                            'name' => $this->_province->getProvince($model->province)['name']
                        ],
                        'district' => [
                            'id' => $model->district,
                            'name' => $this->_province->getProvince($model->district)['name']
                        ],
                        'ward' => [
                            'id' => $model->ward,
                            'name' => $this->_province->getProvince($model->ward)['name']
                        ],
                        'address' => $model->address
                    ]
                ]);
            $model->province = $this->_province->getProvince($model->province)['name'];
            $model->district = $this->_province->getDistrict($model->district)['name'];
            $model->ward = $this->_province->getWard($model->ward)['name'];
            if ($model->save()) {
                $notification_product_buyer = '';
                foreach (array_unique($seller) as $key => $value) {
                    $code = $this->code(rand(100000000, 999999999));
                    $data = [
                        'code' => $code,
                        'invoice' => $model->id,
                        'status' => Constant::STATUS_ORDER_PENDING,
                        'province_id' => $this->_province->getProvince($model->province)['id'],
                        'buyer' => [
                            'id' => Yii::$app->user->id,
                            'name' => $model->name,
                            'phone' => $model->phone,
                            'email' => $model->email,
                            'address' => $model->address,
                            'province' => $model->province,
                            'district' => $model->district,
                            'ward' => $model->ward,
                        ],
                        'created_at' => time(),
                        'updated_at' => time(),
                    ];
                    $notification_product = '';
                    foreach ($cart->getItems() as $item) {
                        if ($value == $item->getProduct()['owner']['id']) {
                            $data['product'][] = [
                                'id' => $item->getProduct()['id'],
                                'title' => $item->getProduct()['title'],
                                'slug' => $item->getProduct()['slug'],
                                'type' => $item->getType() > 0 ? $item->getType() : 0,
                                'image' => $item->getProduct()['image'],
                                'url' => $item->getProduct()['url'],
                                'price' => $item->getPrice(),
                                'quantity' => $item->getQuantity(),
                                'unit' => $item->getProduct()['unit'],
                                'status' => 1
                            ];
                            $data['owner'] = $item->getProduct()['owner'];
                            $notification_product .= $item->getProduct()['title'] . ', ';
                            $notification_product_buyer .= $item->getProduct()['title'] . ', ';
                        }
                    }
                    Yii::$app->mongodb->getCollection('order')->insert($data);

                    Yii::$app->mongodb->getCollection('notification')->insert([
                        'type' => 'seller',
                        'owner' => $data['owner']['id'],
                        'content' => '<b>' . $model->name . '</b> đã đặt mua sản phẩm: <b>' . substr($notification_product, 0, -2) . '</b>',
                        'url' => '/order/filter?keywords=' . $code,
                        'status' => 0,
                        'created_at' => time()
                    ]);

                    Yii::$app->mongodb->getCollection('notification')->insert([
                        'type' => 'admin',
                        'owner' => $value,
                        'content' => '<b>' . $model->name . '</b> đã đặt hàng của <b>' . $data['owner']['garden_name'] . '</b>',
                        'url' => '/order/index?OrderSearch[keywords]=' . $code,
                        'status' => 0,
                        'created_at' => time()
                    ]);

                    Yii::$app->mongodb->getCollection('mail')->insert([
                        'title' => 'Bạn vừa có một kiện hàng mới tại sàn giao dịch nông sản Vinagex',
                        'type' => 'order',
                        'code' => $code,
                        'layout' => 'order',
                        'created_at' => time()
                    ]);
                }
                $product_array = [];
                foreach ($model->product as $key => $value) {
                    $product_array[$value['seller_id']]['seller_name'] = $value['seller_name'];
                    $product_array[$value['seller_id']]['seller_username'] = $value['seller_username'];
                    $product_array[$value['seller_id']]['product'][] = $value;
                }
//                $invoice = new Invoice();
//                $invoice->trigger(Invoice::EVENT_SEND_MAIL, new SendMailEvent(['fromName' => $invoice['name'], 'fromAddress' => $invoice['email'], 'result' => ['invoice' => $model]]));
//                Yii::$app->session->setFlash('order_success', 'Đặt hàng thành công.');
                $cart->clear();

                Yii::$app->mongodb->getCollection('notification')->insert([
                    'type' => 'buyer',
                    'owner' => Yii::$app->user->id,
                    'content' => 'Bạn đã đặt mua thành công sản phẩm: <b>' . substr($notification_product_buyer, 0, -2) . '</b>',
                    'url' => '/invoice/view/' . $model->id,
                    'status' => 0,
                    'created_at' => time()
                ]);

                Yii::$app->mongodb->getCollection('mail')->insert([
                    'title' => 'Đơn hàng #' . $model->code . ' của bạn tại Vinagex',
                    'type' => 'invoice',
                    'code' => $model->code,
                    'layout' => 'invoice',
                    'created_at' => time()
                ]);
                return $this->redirect(['success', "id" => $model->code]);
            }
        }
        return $this->renderAjax('shipping', ['model' => $model]);
    }

    public function actionSuccess($id) {
        $invoice = (new Query)->from('invoice')->where(['code' => (int) $id])->one();
        return $this->render('success', ["id" => (string) $invoice['_id'], 'code' => $id]);
    }

    public function actionRemove($id) {
        $item = $this->_cart->getItem($id);
        if ($item) {
            $this->_cart->remove($id);
        } else {
            throw new NotFoundHttpException('Sản phẩm này không tồn tại.');
        }
        return $this->redirect(['checkout']);
    }

    public function actionClear() {
        $this->_cart->clear();
        return $this->redirect(['checkout']);
    }

    public function code($code) {
        $model = Order::find()->where(['code' => $code])->one();
        if ($model) {
            $this->code(rand(100000000, 999999999));
        }
        return $code;
    }

}
