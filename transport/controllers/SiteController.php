<?php

namespace transport\controllers;

use Yii;
use yii\web\Controller;
use transport\models\OrderFilter;
use transport\models\BidForm;

/**
 * Site controller
 */
class SiteController extends Controller {

    public function init() {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
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
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex() {
//        \Yii::$app->transport->clear();
        $search = new OrderFilter();
        $dataProvider = $search->search(Yii::$app->request->getQueryParams());
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'search' => $search
        ]);
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionBid($id) {
        $model = new BidForm(['id' => $id]);
        return $this->renderAjax('bid', [
                    'model' => $model
        ]);
    }

}
