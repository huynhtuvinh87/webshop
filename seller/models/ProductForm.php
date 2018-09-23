<?php

namespace seller\models;

use Yii;
use yii\base\Model;
use common\models\Product;
use common\models\Category;
use common\components\Constant;
use yii\web\UploadedFile;
use common\models\Setting;
use yii\mongodb\Query;

/**
 * Product form
 */
class ProductForm extends Model {

    public $id;
    public $title;
    public $category_id;
    public $product_type;
    public $description;
    public $content;
    public $images;
    public $_images;
    public $time_begin;
    public $time_end;
    public $price_by_area;
    public $unit;
    public $status;
    public $prices;
    public $price;
    public $quantity;
    public $category;
    public $weight_min;
    public $weight_max;
    public $weight_text;
    public $certification;
    public $oscillation_unit;
    public $approx;
    public $classify;
    public $time_to_sell;
    public $quantity_min;
    public $quantity_stock;
    public $error_approx;
    public $error_classify;
    public $error_image;
    public $price_type;
    public $_setting;
    public $province;
    public $_product;
    public $owner;
    public $_userInfo;
    public $_model;

    public function init() {
        $this->_setting = Setting::findOne(['key' => 'config']);
        $this->_userInfo = \common\models\User::findOne(Yii::$app->user->id);
        $this->price_by_area = 1;
        $this->time_to_sell = Product::TIMETOSELL_1;
        $this->price_type = 1;
        if ($this->id) {
            $this->_model = Product::findOne($this->id);
            $this->attributes = $this->_model->attributes;
            $this->time_begin = \Yii::$app->formatter->asDatetime($this->_model->time_begin, "php:d/m/Y");
            $this->time_end = \Yii::$app->formatter->asDatetime($this->_model->time_end, "php:d/m/Y");
            $this->approx = $this->_model->approx;
            $this->classify = $this->_model->classify;
            $this->price_type = $this->_model->price_type;
            $this->_images = $this->_model->images;
            $this->category_id = $this->_model->category['id'];
            $this->product_type = $this->_model->product_type['id'];
            $this->unit = $this->_model->unit;
            $ids = [];
            foreach ($this->_model->province as $id) {
                $ids[] = $id['id'];
            }
            $this->province = $ids;
            if (empty($this->_model->classify) && empty($this->_model->approx)) {
                $this->price = (int) $this->_model->price['min'];
            } else {
                $this->price = "";
            }
            $this->category = Category::findOne($this->_model->category['id']);
            $this->_product = Yii::$app->mongodb->getCollection('product');
            $this->owner = Product::findOne($this->id)['owner'];
        }
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'category_id', 'content', 'price_by_area', 'unit', 'product_type', 'description'], 'required', 'message' => '{attribute} không được bỏ trống'],
            ['title', 'string', 'max' => 50, 'tooLong' => 'Tên sản phẩm quá dài'],
            ['description', 'string', 'min' => 50, 'max' => 1000, 'tooShort' => '{attribute} quá ngắn', 'tooLong' => '{attribute} quá dài'],
            ['content', 'string', 'min' => 50, 'max' => 1000, 'tooShort' => '{attribute} quá ngắn', 'tooLong' => '{attribute} quá dài'],
            [['images'], 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 5],
            [['status', 'quantity_stock'], 'integer'],
            [['certification', 'images', 'price', 'quantity_min', 'time_to_sell', 'time_begin', 'time_end', 'price_type'], 'default'],
            ['province', 'default'],
            ['title', 'validateTitle'],
            ['province', 'validateForm'],
            ['price', 'validateForm'],
            ['quantity_min', 'validateForm'],
            ['quantity_stock', 'validateForm'],
            ['error_approx', 'validateForm'],
            ['error_classify', 'validateForm'],
            ['images', 'validateForm'],
        ];
    }

    public function validateTitle($attribute, $params) {
        if (!$this->hasErrors()) {
            $model = Product::find()->where(['title' => $this->title, 'owner.id' => Yii::$app->user->id])->one();
            if (!empty($model) && ($model->id != $this->id)) {
                $this->addError($attribute, $this->title . ' đã tồn tại trong sản phẩm của bạn');
            }
        }
    }

    public function validateForm($attribute, $params) {
        if (!$this->hasErrors()) {
            if ($this->price_by_area == 2 && count($this->province) == 0) {
                $this->addError('province', 'Khu vực không được bỏ trống');
            }
            if (count($this->images) < 3) {
                $this->addError('error_image', 'Số lượng hình ảnh phải nhiều hơn 2 hình');
            }
            switch ($this->price_type) {
                case 1:
                    $this->errorDefault();
                    break;
                case 2:
                    $this->errorApprox();
                    break;
                case 3:
                    $this->errorClassify();
                    break;
            }
        }
    }

    public function errorDefault() {
        if ($this->price == "" or $this->quantity_min == "" or $this->quantity_stock == "") {
            $this->addError('price', ' Giá sản phẩm, số lượng không được rỗng và phải là kiểu số!');
        }
        if ($this->price == 0 or $this->quantity_min == 0 or $this->quantity_stock == 0) {
            $this->addError('price', ' Giá sản phẩm, số lượng phải lớn hơn 0!');
        }
        if (($this->quantity_stock <= $this->quantity_min)) {
            $this->addError('quantity_stock', 'Số lượng kho phải lớn hơn số lượng tối thiểu');
        }
    }

    public function errorApprox() {

        $error = [];
        $min = 0;
        $max = $_POST['approx_quantity_min'][0];
        $price = $_POST['approx_price'][0];
        foreach ($_POST['approx_quantity_min'] as $k => $value) {
            if (($_POST['approx_quantity_min'][$k] == "") or ( $_POST['approx_quantity_max'][$k] == "") or ( $_POST['approx_price'][$k] == "")) {
                $this->addError('error_approx', 'Giá hoặc số lượng không được rỗng và phải là kiểu số!');
            }
            if (($_POST['approx_quantity_min'][$k] == 0) or ( $_POST['approx_quantity_max'][$k] == 0) or ( $_POST['approx_price'][$k] == 0)) {
                $this->addError('error_approx', 'Giá hoặc số lượng phải lớn hơn 0.');
            }
            if ($_POST['approx_quantity_max'][$k] < $_POST['approx_quantity_min'][$k]) {
                $this->addError('error_approx', 'Số lượng tối đa không được nhỏ hơn số lượng tối thiểu.');
            }
            if ($_POST['approx_quantity_min'][$k] <= $min) {
                $this->addError('error_approx', 'Số lượng tối thiểu phải tăng dần.');
            }
            $min = $_POST['approx_quantity_min'][$k];

            if ($_POST['approx_quantity_max'][$k] <= $max) {
                $this->addError('error_approx', 'Số lượng tối đa phải tăng dần.');
            }
            $max = $_POST['approx_quantity_max'][$k];
            if ($_POST['approx_price'][$k] > $price) {
                $this->addError('error_approx', 'Giá sản phẩm phải giảm dần.');
            }
            $price = $_POST['approx_price'][$k];
        }
    }

    public function errorClassify() {
        $error = [];
        foreach ($_POST['classify_kind'] as $k => $value) {
            if ($_POST['classify_kind'][$k] == "") {
                $this->addError('error_classify', 'Tên loại sản phẩm không được rỗng.');
            }
            $frame = [];
            if (!empty($_POST['quantity_min'][$k])) {
                foreach ($_POST['quantity_min'][$k] as $fk => $f) {
                    if (($_POST['quantity_min'][$k][$fk] == "") or ( $_POST['quantity_max'][$k][$fk] == "") or ( $_POST['frame_price'][$k][$fk] == "")) {
                        $this->addError('error_classify', 'Giá hoặc số lượng không được rỗng và phải là kiểu số!');
                    }
                    if (($_POST['quantity_min'][$k][$fk] == 0) or ( $_POST['quantity_max'][$k][$fk] == 0) or ( $_POST['frame_price'][$k][$fk] == 0)) {
                        $this->addError('error_classify', 'Giá hoặc số lượng phải lớn hơn 0.');
                    }
                }
            } else {
                if ($_POST['classify_quantity_min'][$k] == "" or $_POST['classify_price'][$k] == "" or $_POST['classify_quantity_stock'] == "") {
                    $this->addError('error_classify', 'Số lượng tối thiểu hoặc số lượng kho không được rỗng.');
                }
                if ($_POST['classify_quantity_min'][$k] == 0 or $_POST['classify_price'][$k] == 0 or $_POST['classify_quantity_stock'] == 0) {
                    $this->addError('error_classify', 'Số lượng tối thiểu hoặc số lượng kho không được nhỏ hơn 0.');
                }
            }
        }
    }

    public function attributeLabels() {
        return [
            'title' => 'Tên sản phẩm',
            'description' => 'Mô tả sản phẩm',
            'content' => 'Giới thiệu sản phẩm',
            'images' => 'Hình ảnh sản phẩm',
            'time_begin' => 'Ngày bán đầu bán',
            'time_end' => 'Ngày kết thúc ',
            'unit_of_calculation' => 'Đơn vị tính',
            'price_by_area' => 'Khu vực bán',
            'price' => 'Giá sản phẩm',
            'quantity_min' => 'Số lượng mua tối thiểu',
            'category_id' => 'Danh mục sản phẩm',
            'certification' => 'Chứng nhận',
            'sale' => 'Hình thức bán',
            'form_of_transport' => 'Hình thức vận chuyển',
            'time_to_sell' => 'Thời gian bán',
            'status' => 'Trạng thái',
            'quantity_stock' => 'Số lượng kho',
            'quantity_stock_approx' => 'Số lượng kho',
            'created_at' => 'Ngày đăng'
        ];
    }

    public function time_to_sell() {
        return[
            Product::TIMETOSELL_1 => 'Tôi đã có hàng và sẵn sàng giao',
            Product::TIMETOSELL_2 => 'Tôi chưa có hàng và tôi muốn đặt lệnh bán trước',
        ];
    }

    public function category() {
        $ids = [];
        foreach ($this->_userInfo->category as $id) {
            $ids[] = $id['id'];
        }
        $category = Category::find()->all();
        $data = [];
        if (!empty($category)) {
            foreach ($category as $key => $value) {
                $data['parent'] = [];
                foreach ($value->parent as $val) {
                    if (in_array($val['id'], $ids)) {
                        $data['parent'][] = [
                            'id' => $val['id'],
                            'title' => $val['title']
                        ];
                    }
                }
                $data[] = [
                    'id' => $value->id,
                    'title' => $value['title'],
                    'unit' => $value['unit'],
                    'oscillation_unit' => $value['oscillation_unit'],
                    'parent' => $data['parent']
                ];
            }
        }
        return $data;
    }

    public static function province() {
        $query = new Query();
        $query->select(['name', '_id'])
                ->from('province')->where(['not in', 'name', 'Toàn quốc']);
        $rows = $query->all();
        $data = [];
        foreach ($rows as $value) {
            $data[(string) $value['_id']] = $value['name'];
        }
        return $data;
    }

    public function certification() {
        $query = new Query();
        $query->select(['name', '_id'])
                ->from('certification');
        $rows = $query->all();
        $data = [];
        foreach ($rows as $value) {
            $data[(string) $value['_id']] = $value['name'];
        }
        return $data;
    }

    public function provinceArray($id) {
        return (new Query())->select(['name', '_id'])
                        ->from('province')
                        ->where(['_id' => $id])->orWhere(['key' => $id])->one();
    }

    public function findCategory($id) {
        return Category::findOne($id);
    }

    /**
     * Save product.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function save() {
        if ($this->validate()) {
            $order = (new Query)->from('order')->orWhere(['status' => Constant::STATUS_ORDER_SENDING])->orWhere(['status' => Constant::STATUS_ORDER_PENDING])->andWhere(['product.id' => $this->id])->count();
            $collection = Yii::$app->mongodb->getCollection('product');
            $slug = Constant::slug($this->title);
            $files = UploadedFile::getInstances($this, 'images');
            $category = Category::findOne($this->category_id);
            $parent_key = array_search($this->product_type, array_column($category->parent, 'id'));
            $unset = [];
            $price = [];
            $quantity = [];
            $error = [];
            $quantity_stock = 0;
            $data = [
                'title' => $this->title,
                'slug' => $slug,
                'keyword' => str_replace('-', ' ', $slug),
                'owner' => [
                    'id' => Yii::$app->user->id,
                    'fullname' => Yii::$app->user->identity->fullname,
                    'username' => Yii::$app->user->identity->username,
                    'garden_name' => Yii::$app->user->identity->garden_name,
                    'email' => Yii::$app->user->identity->email,
                    'province' => Yii::$app->user->identity->province,
                    'district' => Yii::$app->user->identity->district,
                    'ward' => Yii::$app->user->identity->ward,
                    'address' => Yii::$app->user->identity->address
                ],
                'category' => [
                    'id' => $category->id,
                    'slug' => $category->slug,
                    'title' => $category->title,
                ],
                'product_type' => [
                    'id' => $category['parent'][$parent_key]['id'],
                    'slug' => $category['parent'][$parent_key]['slug'],
                    'title' => $category['parent'][$parent_key]['title'],
                ],
                'content' => $this->content,
                'description' => $this->description,
                'price_by_area' => $this->price_by_area,
                'unit' => $this->unit,
                'certification' => $this->_userInfo->certificate,
                'time_to_sell' => (int) $this->time_to_sell,
            ];
            if (!empty($this->images)) {
                $array_images = !empty($this->_model['images']) ? array_diff($this->_model['images'], $this->images) : [];
                if (!empty($array_images)) {
                    foreach ($array_images as $value) {
                        $filepath = \Yii::getAlias("@cdn/web/" . $value);
                        if (file_exists($filepath)) {
                            unlink($filepath);
                        }
                    }
                }
                $data = array_merge($data, ['images' => $this->images]);
            }

            if ($this->price_type == 2) {
                $approx = [];
                foreach ($_POST['approx_quantity_min'] as $k => $value) {
                    $approx[] = [
                        'quantity_min' => $_POST['approx_quantity_min'][$k],
                        'quantity_max' => $_POST['approx_quantity_max'][$k],
                        'price' => $_POST['approx_price'][$k]
                    ];
                    $price[] = $_POST['approx_price'][$k];
                    $quantity[] = $_POST['approx_quantity_min'][$k];
                    $quantity[] = $_POST['approx_quantity_max'][$k];
                }
                $unset = array_merge($unset, ['classify' => '']);
                $data = array_merge($data, ['approx' => $approx, 'quantity_min' => min($quantity), 'quantity_stock' => max($quantity)]);
            }
            if ($this->price_type == 3) {
                $unset = array_merge($unset, ['approx' => '']);
                $classify = [];
                $classify_qtt = [];
                foreach ($_POST['classify_kind'] as $k => $value) {
                    $frame = [];
                    if (!empty($_POST['quantity_min'][$k])) {
                        foreach ($_POST['quantity_min'][$k] as $fk => $f) {
                            $frame[] = [
                                'quantity_min' => $_POST['quantity_min'][$k][$fk],
                                'quantity_max' => $_POST['quantity_max'][$k][$fk],
                                'price' => $_POST['frame_price'][$k][$fk]
                            ];
                            $price[] = (int) $_POST['frame_price'][$k][$fk];
                            $classify_qtt[] = (int) $_POST['quantity_min'][$k][$fk];
                            $classify_qtt[] = (int) $_POST['quantity_max'][$k][$fk];
                        }
                        $price_max = $_POST['frame_price'][$k][0];
                        $price_min = $_POST['frame_price'][$k][count($_POST['frame_price'][$k]) - 1];
                        $quantity_min = $_POST['quantity_min'][$k][0];
                        $quantity_max = $_POST['quantity_max'][$k][count($_POST['quantity_max'][$k]) - 1];
                        $classify[] = [
                            'id' => (int) $k + 1,
                            'kind' => $_POST['classify_kind'][$k],
                            'quantity_min' => (int) $quantity_min,
                            'quantity_stock' => (int) $quantity_max,
                            'price_min' => (int) $price_min,
                            'price_max' => (int) $price_max,
                            'description' => $_POST['classify_description'][$k],
                            'status' => !empty($this->classify[$k]['status']) ? $this->classify[$k]['status'] : 1,
                            'quantity_purchase' => !empty($this->classify[$k]['quantity_purchase']) ? $this->classify[$k]['quantity_purchase'] : 0,
                            'quantity_purchase_total' => !empty($this->classify[$k]['quantity_purchase_total']) ? $this->classify[$k]['quantity_purchase_total'] : 0,
                            'frame' => $frame
                        ];
                    } else {
                        $price_max = $price_min = $_POST['classify_price'][$k];
                        $price[] = (int) $_POST['classify_price'][$k];
                        if ($_POST['classify_quantity_min'][$k] > 0) {
                            $classify_qtt[] = (int) $_POST['classify_quantity_min'][$k];
                        }
                        if ($_POST['classify_quantity_stock'][$k] > 0) {
                            $classify_qtt[] = (int) $_POST['classify_quantity_stock'][$k];
                        }
                        $classify[] = [
                            'id' => (int) $k + 1,
                            'kind' => $_POST['classify_kind'][$k],
                            'quantity_min' => (int) $_POST['classify_quantity_min'][$k],
                            'quantity_stock' => (int) $_POST['classify_quantity_stock'][$k],
                            'price_min' => (int) $_POST['classify_price'][$k],
                            'price_max' => (int) $_POST['classify_price'][$k],
                            'description' => $_POST['classify_description'][$k],
                            'status' => !empty($this->classify[$k]['status']) ? $this->classify[$k]['status'] : 1,
                            'quantity_purchase' => !empty($this->classify[$k]['quantity_purchase']) ? $this->classify[$k]['quantity_purchase'] : 0,
                            'quantity_purchase_total' => !empty($this->classify[$k]['quantity_purchase_total']) ? $this->classify[$k]['quantity_purchase_total'] : 0,
                        ];
                    }
                }
                $data = array_merge($data, ['classify' => $classify, 'quantity_min' => min($classify_qtt), 'quantity_stock' => max($classify_qtt)]);
            }
            if ($this->price_type == 1) {
                $unset = array_merge($unset, ['classify' => '', 'approx' => '']);
                if (!empty($this->price)) {
                    $price[] = (int) $this->price;
                }

                $data = array_merge($data, ['quantity_min' => (int) $this->quantity_min, 'quantity_stock' => (int) $this->quantity_stock]);
            }
            if ($this->price_by_area == 2) {
                $province = [];
                foreach ($this->province as $value) {
                    $p = $this->provinceArray($value);
                    $province[] = ['id' => $value, 'name' => $p['name']];
                }
                $data = array_merge($data, ['province' => $province]);
            } else {
                $p = $this->provinceArray('toan-quoc');
                $data = array_merge($data, ['province' => [['id' => (string) $p['_id'], 'name' => $p['name']]]]);
            }
            if ($this->time_to_sell == Product::TIMETOSELL_2) {
                $data = array_merge($data, ['time_begin' => Constant::convertTime($this->time_begin), 'time_end' => Constant::convertTime($this->time_end)]);
            } else {
                $unset = array_merge($unset, ['time_begin' => '', 'time_end' => '']);
            }
            if (count($price) > 0) {
                $price_array = [
                    'min' => min($price),
                    'max' => max($price)
                ];
            } else {
                $price_array = [
                    'min' => 0,
                    'max' => 0
                ];
            }

            if ($this->status == Constant::STATUS_CANCEL) {
                $data['status'] = Constant::STATUS_NOACTIVE;
            }

            $data = array_merge($data, ['price' => $price_array, 'price_type' => (int) $this->price_type]);
            if ($this->id) {
                if ($order > 0) {
                    \Yii::$app->getSession()->setFlash('danger', 'Sản phẩm này đang có đơn hàng ở trạng thái đang chờ xử lý hoặc đang giao hàng nên bạn không thể cập nhập. Xin vui lòng xử lý đơn hàng để có thể cập nhật sản phẩm.');
                } else {
                    if ($this->_model['title'] != $data['title'] || $this->_model['category']['id'] != $data['category']['id'] || $this->_model['content'] != $data['content'] || $this->_model['description'] != $data['description'] || $this->_model['images'] != $data['images'] || $this->_model['product_type']['id'] != $data['product_type']['id']) {
                        $data['status'] = Constant::STATUS_NOACTIVE;
                        Yii::$app->session->setFlash('success', 'Bạn đã cập nhật thành công. <br>Vui lòng lòng đợi! Sản phẩm của bạn được duyệt sớm nhất.');
                    } else {
                        Yii::$app->session->setFlash('success', 'Bạn đã cập nhật thành công!');
                    }

                    $data['updated_at'] = time();
                    $collection->update(['_id' => $this->id], ['$unset' => $unset]);
                    $collection->update(['_id' => $this->id], $data);
                    Yii::$app->mongodb->getCollection('notification')->insert([
                        'type' => 'admin',
                        'content' => '<b>' . $data['owner']['garden_name'] . '</b> vừa mới cập nhật sản phẩm.</b>',
                        'url' => Yii::$app->setting->get('siteurl') . '/product/view/' . $this->id,
                        'status' => 0,
                        'created_at' => time()
                    ]);
                }
                $id = $this->id;
            } else {
                $data['created_at'] = time();
                $data['updated_at'] = time();
                $data = array_merge($data, ['status' => Constant::STATUS_NOACTIVE]);
                $id = $collection->insert($data);
                Yii::$app->mongodb->getCollection('notification')->insert([
                        'type' => 'admin',
                        'content' => '<b>' . $data['owner']['garden_name'] . '</b> vừa mới tạo mới sản phẩm</b>',
                        'url' => Yii::$app->setting->get('siteurl') . '/product/preview/' . $this->id,
                        'status' => 0,
                        'created_at' => time()
                    ]);
            }
            return $id;
        }
    }

}
