<?php

namespace transport\models;

use common\models\Order;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\mongodb\Query;
use common\components\Constant;

/**
 * PageSearch represents the model behind the search form about `common\modules\Post\models\Post`.
 */
class OrderFilter extends Order {

    public $quantity;
    public $from;
    public $to;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['quantity', 'from', 'to'], 'safe'],
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
        $query = (new Query)->from('product_order');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 20
            ],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        if (!empty($this->from) && !empty($this->to)) {
            $query->andFilterWhere(['location.from' => $this->from, 'location.to' => $this->to]);
        }
        if (!empty($this->quantity) && $this->quantity > 5000) {
            $query->andFilterWhere(['>=', 'product.quantity', $this->quantity]);
        } else if (!empty($params['quantity'])) {
            $query->andFilterWhere(['<=', 'product.quantity', $this->quantity]);
        }
        $query->andFilterWhere(['status' => Constant::STATUS_ORDER_DGH]);
        $query->andFilterWhere(['public' => 1]);


        $query->orderBy('id DESC');

        return $dataProvider;
    }

}
