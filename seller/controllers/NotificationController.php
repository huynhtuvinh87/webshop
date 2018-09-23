<?php

namespace seller\controllers;

use Yii;
use common\components\Constant;
use common\models\Notification;
use yii\data\ActiveDataProvider;
use yii\mongodb\Query;


class NotificationController extends ManagerController {

    public function init() {
        parent::init();
    }

    public function actionIndex() {
        $query = Notification::find()->where(['type'=>'seller','owner'=>Yii::$app->user->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at'=>SORT_DESC]],
            'pagination' => [
                'defaultPageSize' => 20
            ],
        ]);
        $this->view->title = 'Thông báo của bạn';
        return $this->render('index',['dataProvider'=>$dataProvider]);
    }

    public function actionStatus(){
        $post = Yii::$app->request->post();
        return Yii::$app->mongodb->getCollection('notification')->update(['_id'=>$post['id']],['status'=>(int)1]);
    }

    public function actionCheckread(){
        $post = Yii::$app->request->post();
        $model = (new Query)->from('notification')->where(['_id'=>$post['id']])->one();
        $status = $model['status'] == 0?1:0;
        Yii::$app->mongodb->getCollection('notification')->update(['_id'=>$post['id']],['status'=>(int)$status]);
        return $status;
        
    }

    public function actionCheckall(){
        Yii::$app->mongodb->getCollection('notification')->update(['owner'=>\Yii::$app->user->id,'type'=>'seller'],['status'=>(int)1]);
        return $this->redirect('/notification');
    }

}
