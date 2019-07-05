<?php

namespace app\base;

use Yii;
use yii\helpers\ArrayHelper;

class Model extends \yii\base\Model{

    public static function createMultiple($modelClass, $multipleModels = []){
        $model = new $modelClass;
        $formName = $model->formName();
        $post = Yii::$app->request->post($formName);
        $models = [];

        if(!empty($multipleModels)){
            $keys = array_keys(ArrayHelper::map($multipleModels, '_id', '_id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if($post && is_array($post)){
            foreach($post as $i => $item){
                if(isset($item['_id']) && !empty($item['_id']) && isset($multipleModels[$item['_id']])){
                    $models[] = $multipleModels[$item['_id']];
                }else{
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }
}