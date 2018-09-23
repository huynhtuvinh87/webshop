<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Quotations;
use yii\web\NotFoundHttpException;
use common\components\Constant;
use common\models\Setting;
use common\models\Wishlist;
use yii\data\ActiveDataProvider;

/**
 * Site controller
 */
class CustomerController extends Controller {

    public $_setting;

    public function init() {
        parent::init();
        $this->_setting = Setting::findOne(['key' => 'config']);
        $this->layout = 'profile';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['wishlist', 'quotation'],
                'rules' => [
                    [
                        'actions' => ['wishlist', 'quotation'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionWishlist() {
        $dataProvider = new ActiveDataProvider([
            'query' => Wishlist::find()->where(['user_id' => Yii::$app->user->id]),
            'pagination' => [
                'defaultPageSize' => 20
            ],
        ]);
        $this->view->title = 'Sản phẩm đã lưu';
        return $this->render('wishlist', [
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionQuotation() {
        $dataProvider = new ActiveDataProvider([
            'query' => Quotations::find()->where(['user_id' => Yii::$app->user->id]),
            'pagination' => [
                'defaultPageSize' => 20
            ],
        ]);
        $this->view->title = 'Sản phẩm nhận báo giá';
        return $this->render('quotation', [
                    'dataProvider' => $dataProvider
        ]);
    }

}
