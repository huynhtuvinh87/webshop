<?php

namespace transport\models;

use yii\base\BaseObject;
use yii\base\InvalidConfigException;

class Transport extends BaseObject {

    /**
     * @var string $storageClass
     */
    public $storageClass = 'commom\storage\SessionStorage';

    /**
     * @var string $calculatorClass
     */
    public $calculatorClass = 'common\components\Calculator';

    /**
     * @var array $params Custom configuration params
     */
    public $params = [];

    /**
     * @var array $defaultParams
     */
    private $defaultParams = [
        'key' => 'transport',
        'expire' => 604800
    ];

    /**
     * @var CartItem[]
     */
    private $items;

    /**
     * @var \devanych\cart\storage\StorageInterface
     */
    private $storage;

    /**
     * @var \devanych\cart\calculators\CalculatorInterface
     */
    private $calculator;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->params = array_merge($this->defaultParams, $this->params);
        if (!class_exists($this->storageClass)) {
            throw new InvalidConfigException('storageClass `' . $this->storageClass . '` not found');
        }
        if (!class_exists($this->calculatorClass)) {
            throw new InvalidConfigException('calculatorClass `' . $this->calculatorClass . '` not found');
        }
        $this->storage = new $this->storageClass($this->params);
        $this->calculator = new $this->calculatorClass();
    }

    /**
     * Add an item to the cart
     * @param object $product
     * @param integer $quantity
     * @return void
     */
    public function add($product_order, $phone, $number, $price, $commitment) {
        $this->loadItems();
        $this->items[$product_order['id']] = new TransportItem($product_order, $phone, $number, $price, $commitment, $this->params);
        ksort($this->items, SORT_NUMERIC);
        $this->saveItems();
    }

    /**
     * Change item quantity in the cart
     * @param integer $id
     * @param integer $price
     * @return void
     */
    public function change($id, $price) {
        $this->loadItems();
        if (isset($this->items[$id])) {
            $this->items[$id]->setPrice($price);
        }
  
        $this->saveItems();
    }

    /**
     * Removes an items from the cart
     * @param integer $id
     * @return void
     */
    public function remove($id) {
        $this->loadItems();
        if (array_key_exists($id, $this->items)) {
            unset($this->items[$id]);
        }
        $this->saveItems();
    }

    /**
     * Removes all items from the cart
     * @return void
     */
    public function clear() {
        $this->items = [];
        $this->saveItems();
    }

    /**
     * Returns all items from the cart
     * @return CartItem[]
     */
    public function getItems() {
        $this->loadItems();
        return $this->items;
    }

    /**
     * Returns an item from the cart
     * @param integer $id
     * @return CartItem
     */
    public function getItem($id) {
        $this->loadItems();
        return isset($this->items[$id]) ? $this->items[$id] : null;
    }

    /**
     * Returns ids array all items from the cart
     * @return array
     */
    public function getItemIds() {
        $this->loadItems();
        $items = [];
        foreach ($this->items as $item) {
            $items[] = $item->getId();
        }
        return $items;
    }

    /**
     * Returns total cost all items from the cart
     * @return integer
     */
    public function getTotalCost() {
        $this->loadItems();
        return $this->calculator->getCost($this->items);
    }

    /**
     * Returns total count all items from the cart
     * @return integer
     */
    public function getTotalCount() {
        $this->loadItems();
        return $this->calculator->getCount($this->items);
    }

    /**
     * Load all items from the cart
     * @return void
     */
    private function loadItems() {
        if ($this->items === null) {
            $this->items = $this->storage->load();
        }
    }

    /**
     * Save all items to the cart
     * @return void
     */
    private function saveItems() {
        $this->storage->save($this->items);
    }

}
