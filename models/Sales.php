<?php

namespace app\models;

use yii\mongodb\ActiveRecord;
use yii2tech\embedded\ContainerInterface;
use yii2tech\embedded\ContainerTrait;

class Sales extends ActiveRecord implements ContainerInterface{
    use ContainerTrait;

    public function attributes(){
        return ['_id', 'date', 'price', 'client', 'items'];
    }

    public function embedItem(){
        return $this->mapEmbedded('items', Products::className());
    }

    public function attributeLabels(){
        return[
            'date' => 'Fecha de Compra',
            'price' => 'Precio',
            'client' => 'Dni del Cliente'
        ];
    }
    
    public function rules(){
        return[
            [['date', 'price'], 'required'],
            [['price'], 'number'],
            [['client'], 'safe'],
            ['items', 'app\validators\EmbedDocValidator', 'model' => '\app\models\Sales']
        ];
    }

    public function beforeSave($insert){
        if(!parent::beforeSave($insert)){
            return false;
        }

        $this->refreshFromEmbedded();
        return true;
    }
}