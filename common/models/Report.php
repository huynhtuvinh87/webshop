<?php

namespace common\models;

use yii\mongodb\ActiveRecord;

class Report extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'report';
    }

    public function init() {
        parent::init();
        if (!\Yii::$app->user->isGuest) {
            $this->email = \Yii::$app->user->identity->email;
            $this->phone = \Yii::$app->user->identity->phone;
        }
    }

    public function attributes() {
        return [
            '_id',
            'product',
            'reason',
            'description',
            'email',
            'phone',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['reason'], 'required'],
            [['description'], 'string', 'length' => [0, 1000],'tooLong' => 'Không được nhiều hơn 1000 ký tự'],
            ['product', 'default'],
            ['email', 'default'],
            ['phone', 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'description' => 'Mô tả thêm',
            'phone' => 'Số điện thoại'
        ];
    }

    public function getId() {
        return (string) $this->_id;
    }

    public function status() {
        return[
            1 => 'Vi phạm bản quyền hình ảnh',
            2 => 'Người bán lừa đảo',
            3 => 'Lý do khác'
        ];
    }

}
