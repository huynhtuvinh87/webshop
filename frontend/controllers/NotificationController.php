<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\mongodb\Query;
use common\models\Notification;

class NotificationController extends Controller {

    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Notification::find()->where(['type'=>'buyer','owner' => \Yii::$app->user->id]),
            'sort'=> ['defaultOrder' => ['created_at'=>SORT_DESC]],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $this->view->title = 'Thông báo của bạn';
        return $this->render('index',['dataProvider'=>$dataProvider]);
    }

    public function actionStatus(){
        $post = Yii::$app->request->post();
        return Yii::$app->mongodb->getCollection('notification')->update(['_id'=>$post['id']],['status'=>(int)1]);
    }


    public function actionCheckall(){
        Yii::$app->mongodb->getCollection('notification')->update(['owner'=>\Yii::$app->user->id,'type'=>'buyer'],['status'=>(int)1]);
        return $this->redirect('/notification');
    }
}
