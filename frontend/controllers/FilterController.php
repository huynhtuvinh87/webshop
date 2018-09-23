<?php

namespace frontend\controllers;

use Yii;
use frontend\controllers\FrontendController;
use frontend\models\ProductFilter;

/**
 * Filter controller
 */
class FilterController extends FrontendController {

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

    public function actionIndex() {
        $filter = new ProductFilter();
        $dataProvider = $filter->fillter(Yii::$app->request->queryParams);
        $this->view->title = 'Tìm kiếm';
//        $selected = $this->selected(Yii::$app->request->queryParams);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    protected function selected($array) {
        $url = '';
        foreach ($array as $key => $value) {
            if ($value) {
                
            }
        }
    }

}
