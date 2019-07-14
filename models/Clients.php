<?php

namespace app\models;

use yii\mongodb\ActiveRecord;
use yii\mongodb\Query;
use yii\data\ActiveDataProvider;

class Clients extends ActiveRecord{
    public static function collectionName(){
        return 'clients';
    }

    /**
     * Devuelve un array con la lista de atributos de la colección
     */
    public function attributes(){
        return ['_id', 'dni', 'firstname', 'lastname', 'points'];
    }

    public function rules(){
        return[
            [['firstname', 'lastname', 'dni'], 'required'],
            ['dni', 'unique', 'message' => 'Ya existe un cliente con este dni'],
            [['points'], 'number']
        ];
    }

    public function attributeLabels(){
        return[
            '_id' => 'Producto',
            'dni' => 'Dni',
            'firstname' => 'Nombre/s',
            'lastname' => 'Apellido/s',
            'points' => 'Puntos'
        ];
    }

    public function searchClientByDni($dni){
        $client = $this::findOne(['dni' => $dni]);

        return $client;
    }
}