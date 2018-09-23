<?php

namespace frontend\controllers;

use Yii;
use common\models\UserInfo;
use common\models\Product;
use common\models\Review;
use common\models\PaymentHistory;
use common\models\Statics;
use common\models\StaticItem;
use yii\data\ActiveDataProvider;
use common\components\Constant;
use yii\web\NotFoundHttpException;
use common\models\User;
use frontend\models\SellerFilter;
use yii\mongodb\Query;

class HelpController extends \yii\web\Controller {

    public function init() {
        $this->layout = "help";
    }

    public function actionJoin() {

        return $this->render('join');
    }

    public function actionProduct(){
    	return $this->render('product');
    }

    public function actionOrder(){
    	return $this->render('order');
    }

}
