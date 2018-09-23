<?php

namespace backend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use common\components\Constant;
use common\models\Page;
use yii\helpers\Url;
use Yii;

class PageForm extends Model {

    public function init() {
        parent::init();
        if($this->id){
            $model = Page::findOne($this->id);
            $this->attributes = $model->attributes;
        }
    }

    /**
     * @inheritdoc
     */
    public $id;
    public $title;
    public $slug;
    public $content;
    public $image;
    public $widget;
    public $status;
    public $created_at;
    public $updated_at;
    public $fileImg;

    public function rules() {
        return [
            [['title', 'content'], 'required'],
            [['status','widget'],'integer'],
            [['fileImg'], 'file', 'skipOnEmpty' => true,'extensions' => 'png,jpg,jpeg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'title' => 'Tiêu đề',
            'content' => 'Nội dung',
            'image' => 'Hình ảnh',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày tạo',
        ];
    }

    public function widget(){
        return [
            Page::WIDGET_INFO => 'Về gía tại vườn',
            Page::WIDGET_COOPERATE => 'Hợp tác & Tuyển dụng',
            Page::WIDGET_SUPPORT => 'Hỗ trợ khách hàng',
            Page::WIDGET_ADDRESS => 'Địa chỉ công ty',
        ];
    }


    public function save() {
        
       if($this->validate()){
            $collection = Yii::$app->mongodb->getCollection('page');

            $file = UploadedFile::getInstance($this, 'fileImg');

            $this->slug = Constant::slug($this->title);
            $this->updated_at = time();
            $this->created_at = time();

            $data = [
                'title' => $this->title,
                'content' => $this->content,
                'slug' => $this->slug,
                'widget' => (int)$this->widget,
                'status' => (int)$this->status,
                'updated_at' => $this->updated_at,
            ];

            if($file){
                $name = time() . '.' . $file->extension;

                if (!file_exists(\Yii::getAlias("@cdn/web/images/pages"))) {
                    mkdir(\Yii::getAlias("@cdn/web/images/pages"), 0777, true);
                }
                $file->saveAs(\Yii::getAlias("@cdn/web/images/pages/".$name));
                
                $this->image ='images/pages/'.$name;
                $data['image'] = $this->image;
            }


            if($this->id){
                if($file){
                    $nameImage = $collection->findOne(['_id'=>$this->id])['image'];
                    unlink(\Yii::getAlias("@cdn/web/".$nameImage));
                }
                $collection->update(['_id' => $this->id],$data);
            }else{
                $collection->insert(array_merge($data,['created_at' => $this->created_at]));
            }

            return true;
       }
       return null;
    }

}
