<?php

namespace seller\controllers;

use Yii;
use seller\models\PasswordForm;
use seller\models\SellerProfileForm;
use seller\models\SellerDocumentForm;
use yii\mongodb\Query;
use common\models\Certification;
use common\components\Constant;
use yii\web\UploadedFile;
use common\models\User;

class SellerController extends ManagerController {

    public $_user;

    public function init() {
        parent::init();
        $this->_user = (new Query())->from('user')->where(['_id' => Yii::$app->user->id])->one();
    }

    public function actionIndex() {
        $model = User::findOne(Yii::$app->user->id);
        return $this->render('index', ['model' => $model]);
    }

    public function actionUpdate() {

        $model = new SellerProfileForm();
        $data['active'] = $model->active;
        $data['role'] = User::ROLE_SELLER;
        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post()['SellerProfileForm'];
            if ($post['field'] == "fullname") {
                $data['fullname'] = $post[$post['field']];
                return $this->change($data);
            }
            if ($post['field'] == "garden_name") {
                $data['garden_name'] = $post[$post['field']];

                $data['keyword'] = str_replace('-', ' ', Constant::slug($post['garden_name']));
                $data['active']['garden_name'] = 0;
                return $this->change($data);
            }
            if ($post['field'] == "phone") {
                $data['phone'] = $post[$post['field']];
                $data['active']['phone'] = 0;
                return $this->change($data);
            }
            if ($post['field'] == "email") {
                $data['email'] = $post[$post['field']];
                return $this->change($data);
            }
            if ($post['field'] == "about") {
                $data['about'] = $post[$post['field']];
                if (!empty($post['images'])) {
                    $array_images = !empty($this->_user['images']) ? array_diff($this->_user['images'], $post['images']) : [];

                    if (!empty($array_images)) {
                        foreach ($array_images as $value) {
                            $filepath = \Yii::getAlias("@cdn/web/" . $value);
                            if (file_exists($filepath)) {
                                unlink($filepath);
                            }
                        }
                    }

                    $data['images'] = $post['images'];
                }
                return $this->change($data);
            }
            if ($post['field'] == "certificate") {
                if (!empty($post['certificate']) && is_array($model->certificate)) {
                    if (!file_exists(\Yii::getAlias("@cdn/web/images/certificates/seller_" . \Yii::$app->user->id))) {
                        mkdir(\Yii::getAlias("@cdn/web/images/certificates/seller_" . \Yii::$app->user->id), 0777, true);
                    }
                    foreach ($model->certificate as $k => $value) {
                        $certificate = UploadedFile::getInstance($model, 'certificate_img[' . $value . ']');
                        if (!empty($certificate)) {
                            $name = md5($value) . '.' . $certificate->extension;
                            $certificate->saveAs(\Yii::getAlias("@cdn/web/images/certificates/seller_" . \Yii::$app->user->id . '/') . $name);
                            $img = 'images/certificates/seller_' . \Yii::$app->user->id . '/' . $name;
                            $active = 0;
                        } else {
                            $img = $_POST['certificate_img'][$value];
                            $active = $_POST['certificate_active'][$value];
                        }
                        $n = Certification::findOne($value);
                        $data['certificate'][] = [
                            'id' => $value,
                            'name' => $n->name,
                            'image' => $img,
                            'date_begin' => $_POST['certificate_date_begin'][$value],
                            'date_end' => $_POST['certificate_date_end'][$value],
                            'active' => $active
                        ];
                    }

                    foreach ($data['certificate'] as $value) {
                        if ($value['image'] == NULL || $value['date_begin'] == NULL || $value['date_end'] == NULL) {
                            Yii::$app->session->setFlash('danger', 'Bạn chưa nhập đầy đủ thông tin cho chứng nhận');
                            return $this->redirect(['index']);
                        }
                    }
                } else {
                    $data['certificate'] = NULL;
                }
                return $this->change($data);
            }
            if ($post['field'] == "address") {
                $p = $model->provinceArray($model->province_id);
                $district = Yii::$app->province->getDistrict($model->district);
                $ward = Yii::$app->province->getWard($model->ward);
                $data['province'] = [
                    'id' => (string) $p['_id'],
                    'name' => $p['name']
                ];
                $data['district'] = [
                    'id' => (string) $district['_id'],
                    'name' => $district['name']
                ];
                $data['ward'] = [
                    'id' => $ward['slug'],
                    'name' => $ward['name']
                ];
                $data['address'] = $model->address;
                $data['active']['address'] = 0;
                return $this->change($data);
            }
            if ($post['field'] == "trademark") {
                $data['trademark'] = $post[$post['field']];
                $data['active']['trademark'] = 0;
                return $this->change($data);
            }
            if ($post['field'] == "acreage") {
                $data['acreage'] = $post[$post['field']];
                $data['active']['acreage'] = 0;
                $data['acreage_unit'] = $model->acreage_unit;
                return $this->change($data);
            }
            if ($post['field'] == "output_provided") {
                $data['output_provided'] = $post[$post['field']];
                $data['active']['output_provided'] = 0;
                $data['output_provided_unit'] = $model->output_provided_unit;
                return $this->change($data);
            }

            if ($post['field'] == "category") {
                $category = [];
                foreach ($model->category as $value) {
                    $data['category'][] = [
                        'category_id' => (string) (new Query())->select(['_id', 'parent'])->where(['parent.id' => $value])->from('category')->one()['_id'],
                        'id' => $value,
                        'title' => $model->category($value)['title'],
                        'slug' => $model->category($value)['slug']
                    ];
                }
                $data['active']['category'] = 0;

                return $this->change($data);
            }
        } else {
            if (!empty($_POST['field'])) {
                return $this->renderAjax('update', ['model' => $model, 'field' => $_POST['field']]);
            } else {
                return $this->redirect(['index']);
            }
        }
    }

    public function change($data) {
        Yii::$app->mongodb->getCollection('user')->update(['_id' => Yii::$app->user->id], ['$set' => $data]);
        if ((Yii::$app->user->identity->role == "member")) {
            Yii::$app->mongodb->getCollection('notification')->insert([
                'type' => 'admin',
                'content' => '<b>Tài khoản ' . Yii::$app->user->identity->fullname . '</b> vừa cập nhật thông tin thành tài khoản bán hàng.</b>',
                'url' => '/seller/view/' . Yii::$app->user->id,
                'status' => 0,
                'created_at' => time()
            ]);
        } else {
            Yii::$app->mongodb->getCollection('notification')->insert([
                'type' => 'admin',
                'content' => '<b>Tài khoản ' . Yii::$app->user->identity->fullname . '</b> vừa cập nhật thông tin thành tài khoản.</b>',
                'url' => '/seller/view/' . Yii::$app->user->id,
                'status' => 0,
                'created_at' => time()
            ]);
        }
        Yii::$app->session->setFlash('success', 'Bạn đã cập nhật thành công.');
        return $this->redirect(['index']);
    }

    public function actionDocument() {
        $model = new SellerDocumentForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Bạn đã cập nhật thông tin tài khoản thành công.');
            return $this->redirect(['document']);
        }
        return $this->render('document', ['model' => $model]);
    }

    public function actionPassword() {
        $model = new PasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->user->logout();
            $this->redirect(['site/login']);
        }
        return $this->render('password', ['model' => $model]);
    }

}
