<?php

namespace transport\models;

use Yii;

class TransportItem {

    /**
     * @var integer $product
     */
    private $phone;

    /**
     * @var integer $quantity
     */
    private $number;

    /**
     * @var integer $price
     */
    private $price;

    /**
     * @var integer $commitment
     */
    private $commitment;

    /**
     * @var object $product
     */
    private $product_order;

    /**
     * @var array $params Custom configuration params
     */
    private $params;

    public function __construct($product_order, $phone, $number, $price, $commitment, $params = []) {
        $this->product_order = $product_order;
        $this->phone = $phone;
        $this->number = $number;
        $this->price = $price;
        $this->commitment = $commitment;
        $this->params = $params;
    }

    /**
     * Returns the id of the item
     * @return integer
     */
    public function getId() {
        return $this->product_order['id'];
    }

    /**
     * Returns the product, AR model
     * @return object
     */
    public function getProductOrder() {
        return $this->product_order;
    }

    /**
     * Returns the quantity of the item
     * @return integer
     */
    public function getPhone() {
        return (int) $this->phone;
    }

    /**
     * Returns the type of the item
     * @return integer
     */
    public function getNumber() {
        return (int) $this->number;
    }

    /**
     * Returns the type of the item
     * @return integer
     */
    public function getPrice() {
        return (int) $this->price;
    }

    /**
     * Returns the price of the item
     * @return integer|float
     */
    public function getCommitment() {
        return $this->commitment;
    }

    /**
     * Sets the quantity of the item
     * @param integer $price
     * @return void
     */
    public function setPrice($price) {
        $this->price = $price;
    }

}
