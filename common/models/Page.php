<?php

namespace common\models;

use yii\mongodb\ActiveRecord;
use common\components\Constant;


class Page extends ActiveRecord {

    

    const STATUS_PUBLIC = 0; // hien thi
    const STATUS_PRIVATE = 1; // ko hien thi

    const WIDGET_INFO = 1; // GIOI THIEU
    const WIDGET_COOPERATE = 2; // HOP TAC VA TUYEN DUNG
    const WIDGET_SUPPORT = 3; // HO TRO
    const WIDGET_ADDRESS = 4; //ADDRESS CONG TY


    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'page';
    }

    public function init() {
        parent::init();
        // if ($this->image) {
        //     $this->link_url = explode(',', $this->image)[1];
        // }
    }

    /**
     * @inheritdoc
     */
    public function attributes(){
        return [
            '_id',
            'title',
            'slug',
            'content',
            'image',
            'widget',
            'status',
            'created_at',
            'updated_at',
        ];
    }

    public function widget(){
        return [
            self::WIDGET_INFO => 'Về gía tại vườn',
            self::WIDGET_COOPERATE => 'Hợp tác & Tuyển dụng',
            self::WIDGET_SUPPORT => 'Hỗ trợ khách hàng',
            self::WIDGET_ADDRESS => 'Địa chỉ công ty',
        ];
    }

    

}
