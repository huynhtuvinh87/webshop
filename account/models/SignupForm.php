<?php

namespace account\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model {

    public $fullname;
    public $email;
    public $phone;
    public $password;
    public $url;
    public $login = 0;

    public function init() {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['email', 'trim'],
            ['fullname', 'required', 'message' => 'Họ tên không được bỏ trống'],
            ['fullname', 'string', 'min' => 5, 'max' => 20, 'tooShort' => 'Số điện thoại phải từ 5 đến 20 ký tự', 'tooLong' => 'Số điện thoại phải từ 5 đến 20 ký tự!'],
            ['phone', 'required', 'message' => 'Điện thoại không được bỏ trống'],
            ['phone', 'string', 'min' => 9, 'max' => 12, 'tooShort' => 'Số điện thoại phải từ 9 đến 11 số', 'tooLong' => 'Số điện thoại phải từ 9  đến 11 số!'],
            ['email', 'email', 'message' => 'Email không hợp lệ'],
            ['email', 'string', 'max' => 255],
            ['phone', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Điện thoại này đã tồn tại trong hệ thống.'],
            ['password', 'required', 'message' => 'Mật khẩu không được bỏ trống'],
            ['password', 'string', 'min' => 6],
            [['url'], 'string'],
            [['login'], 'integer'],
        ];
    }

    public function attributeLabels() {
        return [
            'fullname' => 'Họ tên',
            'role' => 'Tôi là',
            'password' => 'Mật khẩu',
            'phone' => 'Điện thoại'
        ];
    }

    public function username($u) {
        $model = User::find()->where(['username' => $u])->one();
        if ($model) {
            $username = explode('-', $model->username);
            return count($username) > 1 ? $u . '-' . $username[1] + 1 : $u;
        }
        return $u;
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup() {
        if (!$this->validate()) {
            return null;
        }
        $username = explode('@', $this->email);
        $user = new User();
        $user->fullname = $this->fullname;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->username = $this->username($username[0]);
        $user->setPassword($this->password);
        $user->role = User::ROLE_MEMBER;
        $user->status = User::STATUS_ACTIVE;
        $user->public = User::PUBLIC_PENDING;
        $user->generateAuthKey();
        $user->auth_hash = \Yii::$app->security->generateRandomString();
        $user->display = [
            "email" => 0,
            "phone" => 0,
            "address" => 0,
        ];
        $user->active = [
            "garden_name" => 0,
            "address" => 0,
            "phone" => 0,
            "certificate" => 0,
            "trademark" => 0,
            "category" => 0,
            "output_provided" => 0,
            "acreage" => 0,
            "insurance_money" => 0
        ];
        $user->created_at = time();
        $user->updated_at = time();
        if ($user->save()) {
            Yii::$app->mongodb->getCollection('notification')->insert([
                'type' => 'admin',
                'content' => '<b>' . $user->fullname . '</b> vừa tạo tai khoản</b>',
                'url' => '/customer/view/' . $user->id,
                'status' => 0,
                'created_at' => time()
            ]);

            return $user;
        }
        return FALSE;
    }

}
