<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\mongodb\Query;
use common\models\Province;
use common\models\District;

class ProfileForm extends Model {

    public $fullname;
    public $email;
    public $phone;
    public $address;
    public $province;
    public $district;
    public $ward;
    public $image_verification;
    public $facebook;
    public $_user;
    public $active;
    public $display;
    public $avatar;

    public function init() {
        parent::init();
        $this->_user = (object) (new Query())->from('user')->where(['_id' => Yii::$app->user->id])->one();
        $this->fullname = \Yii::$app->user->identity->fullname;
        $this->email = \Yii::$app->user->identity->email;
        $this->phone = \Yii::$app->user->identity->phone;
        $this->avatar = \Yii::$app->user->identity->avatar;
        if (!empty($this->_user->active)) {
            $this->active = $this->_user->active;
        }
        if (!empty($this->_user->display)) {
            $this->display = $this->_user->display;
        }
        if (!empty($this->_user->address)) {
            $this->address = $this->_user->address;
        }
        if (!empty($this->_user->facebook)) {
            $this->facebook = $this->_user->facebook;
        }
        if (!empty($this->_user->image_verification)) {
            $this->image_verification = $this->_user->image_verification;
        }
        if (!empty($this->_user->province)) {
            $this->province = $this->_user->province['id'];
            $this->district = $this->_user->district['id'];
            $this->ward = $this->_user->ward['id'];
        }
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['display'], 'default'],
            ['fullname', 'required', 'message' => 'Họ tên không được bỏ trống'],
            ['address', 'required', 'message' => 'Địa chỉ không được bỏ trống'],
            ['email', 'required', 'message' => 'Địa chỉ email không được bỏ trống'],
            ['email', 'email', 'message' => 'Địa chỉ email không đúng'],
            ['phone', 'required', 'message' => 'Điện thoại không được bỏ trống'],
            ['province', 'required', 'message' => 'Tỉnh thành không được bỏ trống'],
            ['district', 'required', 'message' => 'Quận/huyện không được bỏ trống'],
            ['ward', 'required', 'message' => 'Quận/huyện không được bỏ trống'],
            ['phone', 'string', 'min' => 9, 'max' => 12, 'tooShort' => 'Số điện thoại phải từ 9 đến 11 số', 'tooLong' => 'Số điện thoại phải từ 9  đến 11 số!'],
            [['facebook'], 'string'],
            ['image_verification', 'default']
        ];
    }

    public function attributeLabels() {
        return [
            'fullname' => 'Họ tên',
            'phone' => 'Điện thoại',
            'province' => 'Tỉnh thành',
            'district' => 'Quận/huyện',
            'district' => 'Quận/huyện',
            'ward' => 'Phường/xã',
            'address' => 'Số nhà',
            'image_verification' => 'Xác minh địa chỉ',
            'display' => 'Công khai'
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function save() {
        if (!$this->validate()) {
            return null;
        }
        $province = Province::findOne($this->province);
        $district = District::findOne($this->district);
        $key = array_search($this->ward, array_column($district->ward, 'slug'));
        $ward = $district->ward[$key];
        $data = [
            'fullname' => $this->fullname,
            'email' => $this->email,
            'phone' => $this->phone,
            'facebook' => $this->facebook,
            'display' => $this->display,
            'province' => [
                'id' => (string) $province->id,
                'name' => $province->name
            ],
            'district' => [
                'id' => (string) $district->id,
                'name' => $district->name
            ],
            'ward' => [
                'id' => $ward['slug'],
                'name' => $ward['name']
            ],
            'address' => $this->address,
        ];

        if (isset(Yii::$app->request->post('ProfileForm')['image_verification']) || !empty($this->active) && $this->active['address'] == 1) {
            $data['image_verification'] = $this->image_verification;
        } else {
            $data['image_verification'] = [];
        }
        // \Yii::$app->db->createCommand()->update('user', $data, 'id=' . \Yii::$app->user->id)->execute();

        return Yii::$app->mongodb->getCollection('user')->update(['_id' => Yii::$app->user->id], ['$set' => $data]);
    }

}
