<?php

namespace frontend\models;

use Yii;
use common\models\ProductProvince;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Category;
use common\components\Constant;

class Search extends Model {

    public $keywords;
    public $category;
    public $level;
    public $sort_price;

    const PRICE_ASC = 'asc';
    const PRICE_DESC = 'desc';

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['keywords', 'category', 'sort_price', 'price_min', 'price_max'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $cookies = Yii::$app->request->cookies;
        $query = ProductProvince::find();
        $query->where(['province_id' => $cookies->getValue('province_id')]);
        if (!empty($params['keywords'])) {
            $keyword = strtolower($params['keywords']);
            $query->andWhere(['like', 'product_name', $keyword]);
        }
        
        if (!empty($params['category'])) {

            $parent = Category::find()->select(['id'])->where(['parent_id' => $params['category']])->asArray()->all();
            $arr = [];
            if ($parent) {
                foreach ($parent as $value) {
                    $arr[] = $value['id'];
                }
            }
            $query->andWhere(['in','category_id', $arr]);
        }
        // find by trainer code

        if (!empty($this->sort_price)) {
            if ($this->sort_price == self::PRICE_ASC) {
                $query->orderBy('price ASC');
            } else {
                $query->orderBy('price DESC');
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 20
            ],
        ]);
  


        return $dataProvider;
    }

    public function getSort() {
        return [
            self::PRICE_ASC => 'Giá tăng dần',
            self::PRICE_DESC => 'Giá giảm dần'
        ];
    }

}
