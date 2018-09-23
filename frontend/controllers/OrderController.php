<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Order;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use frontend\models\TrackingForm;
use yii\mongodb\Query;
use common\models\Invoice;
class OrderController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['history'],
                'rules' => [
                    [
                        'actions' => ['history'],
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

    public function beforeAction($action) {
        Yii::$app->controller->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionHistory() {
        $this->layout = "profile";
        $dataProvider = new ActiveDataProvider([
            'query' => Invoice::find()->where(['actor' => \Yii::$app->user->id])->orderBy('created_at DESC'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->view->title = 'Quản lý đơn hàng';
        return $this->render('history', ['dataProvider' => $dataProvider]);
    }

    public function actionView($id) {
        $model = Order::findOne($id);
        return $this->render('view', ['model' => $model]);
    }

    public function actionTracking() {
        $this->view->title = 'Tra cứu đơn hàng';
        if ($model = Yii::$app->request->get()) {
            $dataProvider = new ActiveDataProvider([
                'query' => Invoice::find()->where(['email' => $model['key']])->orWhere(['phone' => $model['key']]),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
            return $this->render('tracking', ['dataProvider' => $dataProvider]);
        }
    }



}
