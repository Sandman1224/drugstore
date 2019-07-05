<?php

namespace app\models;

use yii\mongodb\ActiveRecord;
use yii\mongodb\Query;
use yii\data\ActiveDataProvider;

class Configuration extends ActiveRecord{
    public static function collectionName(){
        return 'configuration';
    }

    /**
     * Devuelve un array con la lista de atributos de la colección
     */
    public function attributes(){
        return ['_id', 'mountByPoint'];
    }

    public function rules(){
        return[
            [['mountByPoint'], 'required'],
            [['mountByPoint'], 'number'],
            ['mountByPoint', 'compare', 'compareValue' => -1, 'operator' => '>']
        ];
    }

    public function attributeLabels(){
        return[
            '_id' => 'Configuración',
            'mountByPoint' => 'Monto por punto',
        ];
    }

}