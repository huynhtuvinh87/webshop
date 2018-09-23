<?php

namespace transport\models;

use Yii;
use yii\base\Model;
use yii\mongodb\Query;

/**
 * 
 */
class BidForm extends Model {

    public $id;
    public $product_name;
    public $phone;
    public $number;
    public $price;
    public $commitment;

    public function init() {
        $transport = Yii::$app->transport;
        $item = $transport->getItem($this->id);
        if (!empty($item)) {
            $this->price = $item->getPrice();
            $this->commitment = $item->getCommitment();
        }
        $query = (new Query)->from('product_order')->where(['_id' => $this->id])->one();
        $this->product_name = $query['product']['title'];
        if (!empty($transport->getItems())) {
            $data = [];
            foreach ($transport->getItems() as $value) {
                $data[] = [
                    'phone' => $value->getPhone(),
                    'number' => $value->getNumber(),
                    'commitment' => $value->getCommitment()
                ];
            }
            $this->phone = $data[0]['phone'];
            $this->number = $data[0]['number'];
        }
    }

    public function rules() {
        return [
            [['phone', 'number', 'price', 'commitment'], 'required', 'message' => '{attribute} không được để trống'],
            [['price'], 'integer', 'message' => '{attribute} không hợp lý'],
            ['id', 'default']
        ];
    }

    public function attributeLabels() {
        return [
            'product_name' => 'Sản phẩm',
            'phone' => 'Nhập số điện thoại',
            'number' => 'Nhập biển số xe',
            'price' => 'Cước vận chuyển',
            'commitment' => 'Cam kết'
        ];
    }

}

?>