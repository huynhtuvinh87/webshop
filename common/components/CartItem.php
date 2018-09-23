<?php

namespace common\components;

use Yii;
use common\models\Product;
use yii\mongodb\Query;

class CartItem {

    /**
     * @var object $product
     */
    private $product;

    /**
     * @var integer $quantity
     */
    private $quantity;

    /**
     * @var integer $kind
     */
    private $type;

    /**
     * @var array $params Custom configuration params
     */
    private $params;

    public function __construct($product, $quantity, $type, array $params) {
        $this->product = [
            'id' => (string) $product['_id'],
            'title' => $product['title'],
            'slug' => $product['slug'],
            'image' => $product['images'][0],
            'url' => Yii::$app->urlManager->createAbsoluteUrl([$product['slug'] . '-' . (string) $product['_id']]),
            'unit' => $product['unit'],
            'owner' => $product['owner'],
            'price_type' => $product['price_type']
        ];
        if ($product['price_type'] == 3) {
            $this->product['classify'] = $product['classify'];
        } else {
            $this->product['quantity_min'] = $product['quantity_min'];
            $this->product['quantity_stock'] = $product['quantity_stock'];
            $this->product['quantity_purchase'] = !empty($product['quantity_purchase']) ? $product['quantity_purchase'] : 0;
        }
        $this->quantity = $quantity;
        $this->type = $type;
        $this->params = $params;
    }

    /**
     * Returns the id of the item
     * @return integer
     */
    public function getId() {
        return $this->product['id'] . 'type' . $this->getType();
    }

    /**
     * Returns the price of the item
     * @return integer|float
     */
    public function getPrice() {
        $product = Product::findOne($this->product['id']);
        if ($this->getType() > 0) {
            if (!empty($product->classify[$this->getType() - 1]['frame']) && ($product->classify[$this->getType() - 1]['frame'] != "")) {
                foreach ($product->classify[$this->getType() - 1]['frame'] as $value) {
                    if ($this->getQuantity() >= (int) $value['quantity_min'] && $this->getQuantity() <= (int) $value['quantity_max']) {

                        return $value['price'];
                    }
                }
            } else {
                return $product->classify[$this->getType() - 1]['price_min'];
            }
        } else {
            if (!empty($product->approx)) {
                foreach ($product->approx as $value) {
                    if ($this->getQuantity() >= $value['quantity_min'] && $this->getQuantity() <= $value['quantity_max']) {
                        return $value['price'];
                    }
                }
            } else {
                return $product->price['min'];
            }
        }
    }

    /**
     * Returns the product, AR model
     * @return object
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * Returns the cost of the item
     * @return integer|float
     */
    public function getCost() {
        return ceil($this->getPrice() * $this->quantity);
    }

    /**
     * Returns the quantity of the item
     * @return integer
     */
    public function getQuantity() {

        return (int) $this->quantity;
    }

    /**
     * Returns the type of the item
     * @return integer
     */
    public function getType() {
        return (int) $this->type;
    }

    /**
     * Sets the quantity of the item
     * @param integer $quantity
     * @return void
     */
    public function setQuantity($quantity) {

        $this->quantity = $quantity;
    }

}
