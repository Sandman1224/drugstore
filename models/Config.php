<?php

namespace app\models;

use yii\mongodb\ActiveRecord;

class Config extends ActiveRecord{
    public static function collectionName(){
        return 'config';
    }

    /**
     * Devuelve un array con la lista de atributos de la colección
     */
    public function attributes(){
        return ['_id', 'creditsByPrice'];
    }

    public function rules(){
        return[
            [['creditsByPrice'], 'required'],
            [['creditsByPrice'], 'number']
        ];
    }

    public function attributeLabels(){
        return[
            'creditsByPrice' => 'Créditos por precio de venta',
        ];
    }
}