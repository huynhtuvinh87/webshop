<?php

namespace backend\controllers;

use Yii;
use common\models\Category;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use backend\components\BackendController;
use common\components\Constant;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends BackendController {

    public function behaviors() {
        return parent::behaviors();
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex() {

        $dataProvider = new ActiveDataProvider([
            'query' => Category::find(),
            'pagination' => [
                'defaultPageSize' => 20
            ],
        ]);
        $model = new Category();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['index']);
        }

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'model' => $model
        ]);
    }

    public function actionCreate() {
        $model = new Category();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('success', \Yii::t('app', 'Add new success'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        foreach ($model->parent as $value) {
            $model->type[] = $value['id'];
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            foreach ($model->parent as $val) {
                if ($model->parent[array_search($val, array_column($model->parent, 'id'))]['id'] == $val) {
                    Yii::$app->mongodb->getCollection('category')->update(['_id' => $id, 'parent.id' => $val], ['$set' => [
                            'parent.$.title' => $val,
                            "parent.$.slug" => Constant::slug($val),
                    ]]);
                } else {
                    Yii::$app->mongodb->getCollection('category')->update(['_id' => $id], ['$push' => ['parent' => [
                                'title' => $val,
                                'slug' => Constant::slug($val),
                    ]]]);
                }
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionView($id) {
        $data = Category::findOne(['_id' => $id]);
        $array = [];
        foreach ($data->parent as $value) {
            $array[$value['id']] = ['id' => $id, 'parent_id' => $value['id'], 'title' => $value['title'], 'slug' => $value['slug']];
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $array,
            'pagination' => [
                'defaultPageSize' => 20
            ],
        ]);
        $this->view->title = $data->title;
        $model = new Category();
        if ($model->load(Yii::$app->request->post())) {
            $check = Category::find()->where(['_id' => $id, 'parent.title' => $model->title])->one();
            if(!$check){
                Yii::$app->mongodb->getCollection('category')->update(['_id'=>$id],['$push'=>['parent' =>[
                        'id' => (string) new  \MongoDB\BSON\ObjectID(),
                        'description' => '',
                        'parent_id' => $id,
                        'slug' => Constant::slug($model->title),
                        'title' => $model->title,
                ]]]);
                 Yii::$app->session->setFlash('success', 'Thêm thành công');
            }else{
                Yii::$app->session->setFlash('errors', 'Danh mục đã tồn tại');
            }
           
            return $this->redirect(['category/view/'.$id]);
        }
     
        return $this->render('view', [
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    
        ]);
    }

    public function actionEdit($id,$parent_id){
        $data = Category::findOne(['_id' => $id]);
        $array = [];
        foreach ($data->parent as $value) {
            $array[$value['id']] = ['id' => $id, 'parent_id' => $value['id'], 'title' => $value['title'], 'slug' => $value['slug']];
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $array,
            'pagination' => [
                'defaultPageSize' => 20
            ],
        ]);

        $key = array_search($parent_id, array_column($data->parent, 'id'));
        $model = new Category;
        $model->title = $data->parent[$key]['title'];
        $model->slug = $data->parent[$key]['slug'];
        $model->_id = $parent_id;

        if($model->load(Yii::$app->request->post())){
             Yii::$app->mongodb->getCollection('category')->update(['_id' => $id,'parent.id'=>$parent_id], ['$set' =>[
                        'parent.$.slug' => Constant::slug($model->title),
                        'parent.$.title' => $model->title,
                    ]]);
              Yii::$app->session->setFlash('success', 'Sửa thành công');
             return $this->redirect(['category/view/'.$id.'?parent_id='.$parent_id]);
        }
        $this->view->title = $data->parent[$key]['title'];
        return $this->render('view',[
            'dataProvider' => $dataProvider,
            'model' => $model,
            'category' => $data,
        ]);
    }

    public function actionRemove($id,$parent_id){
        $data = Category::findOne(['_id' => $id]);
        $key = array_search($parent_id, array_column($data->parent, 'id'));
        Yii::$app->mongodb->getCollection('category')->update(['_id' => $id], ['$pull' =>[
                        'parent' => $data->parent[$key]
                    ]]);
         Yii::$app->session->setFlash('success', 'Xóa thành công');
        return $this->redirect(['category/view/'.$id]);
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionDoaction() {
        if (!empty($_POST['selection']) && !empty($_POST['action']) && ($_POST['action'] == "delete")) {
            foreach ($_POST['selection'] as $value) {
                $this->findModel($value)->delete();
            }
            \Yii::$app->session->setFlash('success', \Yii::t('app', 'Delete success'));
            return $this->redirect(['index']);
        }
        if (!empty($_POST['action']) && ($_POST['action'] == "order")) {
            foreach ($_POST['order'] as $k => $value) {
                $model = $this->findModel($k);
                $model->order = (int) $_POST['order'][$k];
                $model->save();
            }
            \Yii::$app->session->setFlash('success', \Yii::t('app', 'Order success'));
            return $this->redirect(['index']);
        }
        return $this->redirect(['index']);
    }

    protected function categories(&$data = [], $parent = NULL) {
        $category = Category::find()->andWhere(['type' => 'category'])->where(['parent_id' => $parent])->all();
        foreach ($category as $key => $value) {
            $data[] = $value;
            unset($category[$key]);
            $this->categories($data, $value->id);
        }
        return $data;
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
