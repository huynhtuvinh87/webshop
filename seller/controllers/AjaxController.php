<?php

namespace seller\controllers;

use Yii;
use yii\web\Controller;
use common\models\OrderStatus;
use common\models\SendMail;
use common\models\Setting;
use common\components\Constant;
use yii\mongodb\Query;
use seller\models\ProductOrderForm;
use common\models\Comment;

class AjaxController extends Controller {

    public $_setting;

    public function init() {
        parent::init();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $this->_setting = Setting::findOne(['key' => 'config']);
    }

    public function actionDeleteimage() {
        // $ids = explode(',', $_POST['id']);
        // $collection = Yii::$app->mongodb->getCollection('product');
        // $k = $ids[0] - 1;
        // $collection->update(['_id' => $ids[1]], ['$pop' => ['images' => $k]]);
        $filepath = \Yii::getAlias("@cdn/web/" . $_POST['path']);
        return unlink($filepath);
    }

    public function actionUpload() {
        if (!file_exists(\Yii::getAlias("@cdn/web/images/products/seller_" . \Yii::$app->user->id))) {
            mkdir(\Yii::getAlias("@cdn/web/images/products/seller_" . \Yii::$app->user->id), 0777, true);
        }
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['file']));
        $name = uniqid() . '.png';
        $filepath = \Yii::getAlias("@cdn/web/images/products/seller_" . \Yii::$app->user->id) . '/' . $name;
        file_put_contents($filepath, $data);
        list($width, $height, $type, $attr) = getimagesize($filepath);
        if ($width >= 450 && $height >= 450) {
            return [
                'src' => Yii::$app->setting->get('siteurl_cdn') . '/image.php?src=images/products/seller_' . \Yii::$app->user->id . '/' . $name . '&size=350x350',
                'img_300x250' => Yii::$app->setting->get('siteurl_cdn') . '/image.php?src=images/products/seller_' . \Yii::$app->user->id . '/' . $name . '&size=300x250',
                'path' => 'images/products/seller_' . \Yii::$app->user->id . '/' . $name,
            ];
        } else {
            return ['error' => 'Bạn tải hình ảnh không đúng kích thước theo quy định!'];
            unlink($filepath);
        }
    }

    public function actionSellerupload() {
        if (!file_exists(\Yii::getAlias("@cdn/web/images/sellers/seller_" . \Yii::$app->user->id))) {
            mkdir(\Yii::getAlias("@cdn/web/images/sellers/seller_" . \Yii::$app->user->id), 0777, true);
        }
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['file']));
        $name = uniqid() . '.png';
        $filepath = \Yii::getAlias("@cdn/web/images/sellers/seller_" . \Yii::$app->user->id) . '/' . $name;
        file_put_contents($filepath, $data);
        list($width, $height, $type, $attr) = getimagesize($filepath);
        if ($width >= 450 && $height >= 450) {
            return [
                'src' => Yii::$app->setting->get('siteurl_cdn') . '/image.php?src=images/sellers/seller_' . \Yii::$app->user->id . '/' . $name . '&size=350x300',
                'path' => 'images/sellers/seller_' . \Yii::$app->user->id . '/' . $name,
            ];
        } else {
            return ['error' => 'Bạn tải hình ảnh không đúng kích thước theo quy định!'];
            unlink($filepath);
        }
    }

    public function actionDistrict($id) {
        $model = Yii::$app->province->getDistricts($id);
        return $model;
    }

    public function actionWard($id) {
        $model = Yii::$app->province->getWards($id);
        return $model;
    }

    public function actionShipping($id) {
        $model = new ProductOrderForm();
        $model->id = $id;
        $order = (new Query)->from('order')->where(['code' => (int) $id])->one();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $date_begin = \Yii::$app->formatter->asDatetime(str_replace('/', '-', $model->date_begin), "php:Y-m-d");
                $data['date_begin'] = strtotime($date_begin . ' ' . $model->time_begin);
                if ($model->date_end) {
                    $date_begin = \Yii::$app->formatter->asDatetime(str_replace('/', '-', $model->date_end), "php:Y-m-d");
                    $data['date_end'] = strtotime($date_begin . ' ' . $model->time_end);
                }
                if (!empty($model->transport)) {
                    $data['transport'] = $model->transport;
                }
                //order status
                $data = array_merge($data, ['status' => Constant::STATUS_ORDER_SENDING]);
                Yii::$app->mongodb->getCollection('order')->update(['code' => (int) $id], $data);

                //mail
                Yii::$app->mongodb->getCollection('mail')->insert([
                    'title' => 'Đơn hàng #' . $id . ' của bạn đang trên đường vận chuyển',
                    'type' => 'order_sending',
                    'code' => (int) $id,
                    'layout' => 'order_sending',
                    'created_at' => time()
                ]);
                //notification
                $item_product = '';
                foreach ($order['product'] as $value) {
                    $item_product .= $value['title'] . ', ';
                }
                Yii::$app->mongodb->getCollection('notification')->insert([
                    'type' => 'buyer',
                    'owner' => $order['buyer']['id'],
                    'content' => 'Gói hàng: <b>' . substr($item_product, 0, -2) . '</b> đang được giao.Thời gian dự kiến nhận hàng <b>' . date('h:i - d/m/Y', $data['date_end']) . '</b>',
                    'url' => '/invoice/view/' . $order['invoice'] . '#' . $order['code'],
                    'status' => 0,
                    'created_at' => time()
                ]);


                foreach ($order['product'] as $value) {
                    $data = [];
                    if ($value['status'] == 1) {
                        $product = (new Query)->from('product')->where(['_id' => $value['id']])->one();
                        if ($product['price_type'] == 3) {
                            return $this->pendingClassify($id, $value, $product);
                        } else {
                            return $this->pending($id, $value, $product);
                        }
                    }
                }
            } else {
                echo 'Khoản cách thời gian không hợp lý';
                exit;
            }
        }
    }

    public function pendingClassify($id, $value, $product) {
        $key = array_search($value['type'], array_column($product['classify'], 'id'));
        $classify = $product['classify'][$key];
        $qtt = !empty($classify['quantity_purchase']) ? $classify['quantity_purchase'] : 0;
        $qtt_purchse_total = !empty($classify['quantity_purchase_total']) ? $classify['quantity_purchase_total'] : 0;
        $remain_quantity = $classify['quantity_stock'] - ($qtt + (int) $value['quantity']);
        $data['classify.' . $key . '.quantity_purchase'] = $qtt + (int) $value['quantity'];
        $data['classify.' . $key . '.quantity_purchase_total'] = $qtt_purchse_total + (int) $value['quantity'];
        if ($remain_quantity < $classify['quantity_min']) {
            $data['classify.' . $key . '.status'] = 0;
            foreach ($product['classify'] as $item) {
                $status[] = $item['status'];
            }
            $count = array_count_values($status);
            if (!empty($count[1]) && $count[1] == 1) {
                $data['status'] = Constant::STATUS_BLOCK;

                Yii::$app->mongodb->getCollection('notification')->insert([
                    'type' => 'seller',
                    'owner' => \Yii::$app->user->id,
                    'content' => 'Sản phẩm <b>' . $product['title'] . '</b> đã hết hàng.',
                    'url' => '/product/filter?keywords=' . $product['_id'],
                    'status' => 0,
                    'created_at' => time()
                ]);
            }
            $msg_danger = "<b>Sản phẩm: " . $product['title'] . (($value['type'] != 0) ? " Loại " . $value['type'] : "") . " đã hết hàng<br>";
            Yii::$app->session->setFlash('danger', $msg_danger);
        }

        $order_product = (new Query)->from('order')->where(['product.id' => $value['id'], 'status' => Constant::STATUS_ORDER_PENDING])->all();
        foreach ($order_product as $item_order) {
            foreach ($item_order['product'] as $item_product) {
                if (($item_product['id'] == $value['id'] && $item_product['quantity'] > $remain_quantity) && $item_product['status'] == 1 && $item_product['type'] == $value['type']) {
                    $k = array_search($item_product['id'], array_column($item_order['product'], 'id'));
                    Yii::$app->mongodb->getCollection('order')->update(['_id' => (string) $item_order['_id']], ['$set' => [
                            'product.' . $k . '.status' => 0,
                    ]]);
                }
            }
        }

        Yii::$app->mongodb->getCollection('product')->update(['_id' => $value['id']], ['$set' => $data]);
        Yii::$app->session->setFlash('success', "Đơn hàng: #<strong>" . $id . "</strong> đang được giao");
        return $this->redirect('/order/index');
    }

    public function pending($id, $value, $product) {
        $qtt = !empty($product['quantity_purchase']) ? $product['quantity_purchase'] : 0;
        $qtt_purchse_total = !empty($product['quantity_purchase_total']) ? $product['quantity_purchase_total'] : 0;
        $remain_quantity = $product['quantity_stock'] - ($qtt + (int) $value['quantity']);
        $data['quantity_purchase'] = $qtt + (int) $value['quantity'];
        $data['quantity_purchase_total'] = $qtt_purchse_total + (int) $value['quantity'];
        if ($remain_quantity < $product['quantity_min']) {
            $data['status'] = Constant::STATUS_BLOCK;

            Yii::$app->mongodb->getCollection('notification')->insert([
                'type' => 'seller',
                'owner' => \Yii::$app->user->id,
                'content' => 'Sản phẩm <b>' . $product['title'] . '</b> đã hết hàng.',
                'url' => '/product/filter?keywords=' . $product['_id'],
                'status' => 0,
                'created_at' => time()
            ]);

            $msg_danger = "<b>Sản phẩm: " . $product['title'] . (($value['type'] != 0) ? " Loại " . $value['type'] : "") . " đã hết hàng<br>";
            Yii::$app->session->setFlash('danger', $msg_danger);
        }

        $order_product = (new Query)->from('order')->where(['product.id' => $value['id'], 'status' => Constant::STATUS_ORDER_PENDING])->all();
        foreach ($order_product as $item_order) {
            foreach ($item_order['product'] as $item_product) {
                if (($item_product['id'] == $value['id'] && $item_product['quantity'] > $remain_quantity) && $item_product['status'] == 1 && $item_product['type'] == $value['type']) {
                    $k = array_search($item_product['id'], array_column($item_order['product'], 'id'));
                    Yii::$app->mongodb->getCollection('order')->update(['_id' => (string) $item_order['_id']], ['$set' => [
                            'product.' . $k . '.status' => 0,
                    ]]);
                }
            }
        }

        Yii::$app->mongodb->getCollection('product')->update(['_id' => $value['id']], ['$set' => $data]);
        Yii::$app->session->setFlash('success', "Đơn hàng: #<strong>" . $id . "</strong> đang được giao");
        return $this->redirect('/order/index');
    }

    public function actionComment() {
        $data = [
            'id' => (string) new \MongoDB\BSON\ObjectID(),
            'ip' => Yii::$app->getRequest()->getUserIP(),
            'sex' => "",
            'id_owner' => \Yii::$app->user->id,
            'name' => \Yii::$app->user->identity->garden_name,
            'email' => \Yii::$app->user->identity->email,
            'content' => $_POST['content'],
            'status' => Comment::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time()
        ];
        Yii::$app->mongodb->getCollection('comment')->update(['_id' => $_POST['comment_id']], ['$push' => ['answers' => $data]]);
        Yii::$app->mongodb->getCollection('comment')->update(['_id' => $_POST['comment_id']], ['$set' => [
                'count_answer' => (int) Comment::findOne($_POST['comment_id'])->count_answer + 1,
        ]]);
        return TRUE;
    }

    public function actionCommentstatus() {
        $model = Comment::findOne($_POST['comment_id']);

        Yii::$app->mongodb->getCollection('comment')->update(['_id' => $_POST['comment_id']], ['$set' => [
                'answers.' . $_POST['key'] . '.status' => 2
        ]]);

        $owner = $model->answers[$_POST['key']];
        Yii::$app->mongodb->getCollection('notification')->insert([
            'type' => 'buyer',
            'owner' => $owner['id_owner'],
            'content' => 'Bình luận sản phẩm <b>'.$model->product['title'].'</b> của bạn đã được duyệt.',
            'url' => '/'.$model->product['slug'].'-'.$model->product['id'].'#section-comment',
            'status' => 0,
            'created_at' => time()
        ]);

        return TRUE;
    }

}
