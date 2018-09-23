<?php

namespace backend\controllers;

use Yii;
use common\models\Notification;
use yii\data\ActiveDataProvider;
use yii\mongodb\Query;

class NotificationController extends \backend\components\BackendController {

    public function init() {
        parent::init();
    }

    public function actionIndex() {
        $query = Notification::find()->where(['type' => 'admin']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'defaultPageSize' => 20
            ],
        ]);
        $this->view->title = 'Thông báo';
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionStatus() {
        $post = Yii::$app->request->post();
        return Yii::$app->mongodb->getCollection('notification')->update(['_id' => $post['id']], ['status' => (int) 1]);
    }

    public function actionCheckread() {
        $post = Yii::$app->request->post();
        Yii::$app->mongodb->getCollection('notification')->update(['_id' => $post['id']], ['status' => 1]);
        return $status;
    }

    public function actionRemove() {
        $post = Yii::$app->request->post();
        Yii::$app->mongodb->getCollection('notification')->remove(['_id' => $post['id']]);
        return 1;
    }

    public function actionCheckall() {
        Yii::$app->mongodb->getCollection('notification')->update(['owner' => \Yii::$app->user->id, 'type' => 'seller'], ['status' => (int) 1]);
        return $this->redirect('/notification');
    }

}
