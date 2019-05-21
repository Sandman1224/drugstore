<?php

namespace app\models;

use yii\mongodb\ActiveRecord;
use yii\mongodb\Query;

class Products extends ActiveRecord{

    /*
    public $name;
    public $price;
    public $description;
    public $quantity;
    */

    public static function collectionName(){
        return 'products';
    }

    public function rules(){
        return[
            [['name', 'price', 'quantity'], 'required'],
            [['price'], 'number'],
            [['quantity'], 'integer'],
            [['description'], 'safe']
        ];
    }

    /**
     * Devuelve un array con la lista de atributos de la colección
     */
    public function attributes(){
        return ['_id', 'name', 'price', 'description', 'quantity'];
    }

    public function attributeLabels(){
        return[
            'name' => 'Nombre del producto',
            'price' => 'Precio',
            'description' => 'Descripción',
            'quantity' => 'Cantidad'
        ];
    }

    
}