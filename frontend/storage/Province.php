<?php

namespace frontend\storage;

use yii\base\BaseObject;
use yii\base\InvalidConfigException;

class Province extends BaseObject {

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
        'key' => 'province ',
        'expire' => 604800
    ];

    /**
     * @var ProvinceItem[]
     */
    private $items;

    /**
     * @var \common\storage\StorageInterface
     */
    private $storage;

    /**
     * @var \common\calculators\CalculatorInterface
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
     * Set an item to the province
     * @param object $id
     * @param integer $name
     * @return void
     */
    public function set($id, $name) {
        $this->loadItems();
        $this->items = new ProvinceItem($id, $name, $this->params);
        ksort($this->items, SORT_NUMERIC);
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
     * Returns an item from the cart
     * @param integer $id
     * @return CartItem
     */
    public function getItem($id) {
        $this->loadItems();
        return isset($this->items) ? $this->items : null;
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
