<?php

namespace seller\models;

use common\models\Order;

/**
 * ProductOrderForm
 */
class ProductOrderForm extends \yii\base\Model {

    public $id;
    public $date_begin;
    public $date_end;
    public $time_begin;
    public $time_end;
    public $transport;

    public function init() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['date_end', 'transport', 'id', 'time_begin', 'time_end'], 'default'],
            [['date_begin', 'date_end'], 'required', 'message' => '{attribute} không đưọc bỏ trống'],
            ['date_begin', 'validateForm'],
        ];
    }

    public function attributeLabels() {
        return [
            'date_begin' => 'Thời gian giao hàng',
            'date_end' => 'Thời gian dự kiến giao hàng',
            'transport' => 'Thông tin người vận chuyển'
        ];
    }

    public function validateForm($attribute, $params) {
        if (!$this->hasErrors()) {
            $date_begin = \Yii::$app->formatter->asDatetime(str_replace('/', '-', $this->date_begin), "php:Y-m-d");
            $data['date_begin'] = strtotime($date_begin . ' ' . $this->time_begin);
            $date_begin = \Yii::$app->formatter->asDatetime(str_replace('/', '-', $this->date_end), "php:Y-m-d");
            $data['date_end'] = strtotime($date_begin . ' ' . $this->time_end);
            if (($data['date_end'] <= $data['date_begin']) or ($data['date_end'] <= ($data['date_begin'] + 1800))) {
                $this->addError('date_end', 'Thời gian dự kiến không hợp lý');
            }
        }
    }

    public function time() {
        $data = [];
        for ($i = 0; $i < 24; $i++) {
            $data[$i . ':00'] = $i . ':00';
            $data[$i . ':30'] = $i . ':30';
        }
        return $data;
    }

    public function getCode() {
        $code = rand(100000000, 999999999);
        $model = Order::find()->where(['code' => $code])->one();
        if ($model) {
            $this->getCode(rand(100000000, 999999999));
        }
        return $code;
    }

    /**
     * @inheritdoc
     */
}
