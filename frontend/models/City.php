<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class City extends Model {

    public $province_id;
    public $url;
    public $_province;

    public function init() {
        parent::init();
        $this->url = $_SERVER['REQUEST_URI'];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['province_id'], 'integer'],
            [['url'], 'string'],
        ];
    }

    public function getProvince() {
        $province  = (new \yii\mongodb\Query)->select(['name', '_id'])->from('province')->all();
        $data = [];
        foreach ($province as $value) {
            $data[(string)$value['_id']] = $value['name'];
        }
        return $data;
    }

    public function save() {
        
    }

}
