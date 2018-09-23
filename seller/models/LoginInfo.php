<?php

namespace seller\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\SendMail;
use common\models\Setting;

/**
 * Login form
 */
class LoginInfo extends Model {

    public $password;
    public $password_rep;
    public $email;
    public $phone;
    public $auth_key;
    public $_user;
    public $_setting;

    public function init() {
        parent::init();
        $this->_setting = Setting::findOne(['key' => 'config']);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['password'], 'required', 'message' => '{attribute} không được trống'],
            ['password_rep', 'compare', 'compareAttribute' => 'password', 'message' => 'Xác nhận mật khẩu không đúng!'],
            ['auth_key', 'string']
        ];
    }

    public function attributeLabels() {
        return [
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'password' => 'Mật khẩu',
            'password_rep' => 'Nhập lại mật khẩu',
        ];
    }

    public function save() {
        if (!$this->validate()) {
            return null;
        }
        $user = User::findOne(['auth_key' => $this->auth_key]);
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if ($user->save()) {
//            SendMail::send('signup', ['password' => $this->password, 'user' => $this->_user], [$this->_setting->email => $this->_setting->name], 'Đăng ký tài khoản', $user->email);
            return TRUE;
        }
        return FALSE;
    }

}
