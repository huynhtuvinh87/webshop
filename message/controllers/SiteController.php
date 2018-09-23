<?php

namespace message\controllers;

use Yii;
use yii\web\Controller;
use yii\mongodb\Query;
use common\models\Product;
use common\models\Message;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\components\Constant;

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
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['index', 'message'],
//                'rules' => [
//                    [
//                        'actions' => ['index', 'message'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            
//            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionUrl() {
        $this->redirect(Yii::$app->setting->get('siteurl_id') . '/login?url=' . Constant::redirect(Yii::$app->setting->get('siteurl_message')));
    }

    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Yii::$app->setting->get('siteurl_id') . '/login?url=' . Constant::redirect(Yii::$app->setting->get('siteurl_message')));
            ;
        }
        $message = (new Query())->from('message')->where(['actor.id' => \Yii::$app->user->id])->limit(1)->orderBy(['last_msg_time' => SORT_DESC])->all();
        if ($message) {
            return $this->redirect(['/message/' . (string) $message[0]['_id']]);
        } else {
            return $this->render('index');
        }
    }

    public function actionMessage($id) {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(Yii::$app->setting->get('siteurl_id') . '/login?url=' . Constant::redirect(Yii::$app->setting->get('siteurl_message') . '/message/' . $id));
        }

        $actor = (new Query())->from('message')->where(['actor.id' => \Yii::$app->user->id])->orderBy(['last_msg_time' => SORT_DESC])->all();
        $message = Message::findOne($id);
        $product = Product::findOne($message->product['id']);
        $conversation = (new Query())->from('conversation')
                ->where(['owner' => \Yii::$app->user->id, 'actor' => $message->owner['id'], 'product_id' => $product->id])
                ->orWhere(['actor' => \Yii::$app->user->id, 'owner' => $message->owner['id'], 'product_id' => $product->id])
                ->all();
        return $this->render('message', ['actor' => $actor, 'product' => $product, 'conversation' => $conversation, 'message' => $message]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionTest() {
        $this->layout = 'test';
        return $this->render('test');
    }

}
