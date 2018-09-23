<?php

namespace transport\controllers;

use Yii;
use yii\web\Controller;
use yii\mongodb\Query;
use transport\models\BidForm;


class AjaxController extends Controller {

    public $_transport;

    public function init() {
        parent::init();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $this->_transport = \Yii::$app->transport;
    }

    public function actionBid() {
        $model = new BidForm;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $item = $this->_transport->getItem($model->id);
            $product_order = (new Query)->from('product_order')->where(['_id' => $model->id])->one();
            if ($item) {
                $this->_transport->change($model->id, $model->price);
                Yii::$app->mongodb->getCollection('product_order')->update(['_id' => $model->id, 'bid.id' => $item->getProductOrder()['bid']], ['$set' => [
                        "bid.$.price" => (int) $model->price
                ]]);
            } else {
                $bid = (string) new \MongoDB\BSON\ObjectID();
                $data = [
                    'id' => $model->id,
                    'bid' => $bid,
                    'title' => $product_order['product']['title'],
                    'order' => $product_order['order']
                ];
                $this->_transport->add($data, $model->phone, $model->number, $model->price, $model->commitment);
                Yii::$app->mongodb->getCollection('product_order')->update(['_id' => $model->id], ['$push' => ['bid' => [
                            'id' => $bid,
                            'phone' => $model->phone,
                            'number' => $model->number,
                            'price' => $model->price,
                            'commitment' => $model->commitment
                ]]]);
            }
            return $model->id;
        }
    }

    public function actionRemove($id) {
        $item = $this->_transport->getItem($id);
        if ($item) {
            $product_order = (new Query)->from('product_order')->where(['_id' => $id])->one();
            $key = array_search($_POST['bid'], array_column($product_order['bid'], 'id'));
            Yii::$app->mongodb->getCollection('product_order')->update(['_id' => $id], ['$pull' => [
                    'bid' => $product_order['bid'][$key],
            ]]);
            $this->_transport->remove($id);
            return $id;
        }
    }

}
