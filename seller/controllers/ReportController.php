<?php

namespace seller\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\mongodb\Query;

class ReportController extends ManagerController {

    public function init() {
        parent::init();
    }

    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => (new Query)->from('report')->where(['owner.id' => \Yii::$app->user->id, 'status' => 1])->orderBy('created_at DESC'),
            'pagination' => [
                'defaultPageSize' => 20
            ],
        ]);
        $this->view->title = 'Báo cáo vi phạm';

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

}
