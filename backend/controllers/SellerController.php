<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use common\models\User;
use yii\web\NotFoundHttpException;

class SellerController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex() {
        $query = User::find()->where(['public' => User::PUBLIC_NOACTIVE, 'role' => User::ROLE_SELLER]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->view->title = 'Nhà vườn chưa duyệt';
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionActive() {
        $query = User::find()->where(['public' => User::PUBLIC_ACTIVE, 'role' => User::ROLE_SELLER]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->view->title = 'Nhà vườn đã duyệt';
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->delete();
        \Yii::$app->getSession()->setFlash('success', 'Xóa thành công');
        return $this->redirect([User::PUBLIC_NOACTIVE == $model->public ? 'index' : 'active']);
    }

    public function actionView($id) {
        $model = User::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->mongodb->getCollection('user')->update(['_id' => $model->id], ['$set' => [
                    'active' => $model->active,
                    'insurance_money' => $model->insurance_money,
                    'status' => (int) $model->status
            ]]);
            \Yii::$app->getSession()->setFlash('success', 'Cập nhật thành công');
            $reason = [];
            if ($model->public == User::PUBLIC_ACTIVE) {
                Yii::$app->mongodb->getCollection('notification')->insert([
                    'type' => 'seller',
                    'owner' => $id,
                    'content' => '<b>Tài khoản của bạn mới được duyệt</b>',
                    'url' => '/seller/index',
                    'status' => 0,
                    'created_at' => time()
                ]);
                Yii::$app->mongodb->getCollection('mail')->insert([
                    'title' => 'Tài khoản của bạn mới được duyệt',
                    'type' => 'seller',
                    'actor' => $id,
                    'layout' => 'seller_active',
                    'created_at' => time()
                ]);
            } else {
                Yii::$app->mongodb->getCollection('notification')->insert([
                    'type' => 'seller',
                    'owner' => $id,
                    'content' => '<b>Tài khoản của bạn không đủ yêu cầu để chúng tôi duyệt.</b>',
                    'url' => '/seller/index',
                    'status' => 0,
                    'created_at' => time()
                ]);
                Yii::$app->mongodb->getCollection('mail')->insert([
                    'title' => 'Tài khoản của bạn không đủ yêu cầu để chúng tôi duyệt',
                    'type' => 'seller',
                    'actor' => $id,
                    'layout' => 'seller_noactive',
                    'created_at' => time()
                ]);
                foreach ($model->reason as $value) {
                    $reason[] = $this->reason()[$value];
                }
            }
            Yii::$app->mongodb->getCollection('user')->update(['_id' => $id], ['public' => (int) $model->public, 'reason' => $reason]);
            return $this->redirect(['view', 'id' => $id]);
        }
        $this->view->title = 'Thông tin của ' . $model->garden_name;
        return $this->render('view', ['model' => $model, 'reason' => $this->reason()]);
    }

    public function reason() {
        return [
            1 => 'Tên nhà vườn không chính xác',
            2 => 'Số điện thoại không chính xác',
            3 => 'Địa chỉ không chính xác'
        ];
    }

    public function actionCertificate() {
        $id = Yii::$app->request->post('id');
        $idUser = Yii::$app->request->post('idUser');
        $value = Yii::$app->request->post('value');
        $user = User::findOne($idUser);
        if (!empty($user)) {
            Yii::$app->mongodb->getCollection('user')->update(['_id' => $idUser, 'certificate.id' => $id], ['$set' => [
                    'certificate.$.active' => $value,
            ]]);
        }
        return TRUE;
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
