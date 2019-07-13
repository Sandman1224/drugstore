<?php

namespace app\models;

use yii\mongodb\ActiveRecord;
use yii\mongodb\Query;
use yii\data\ActiveDataProvider;

class Products extends ActiveRecord{
    const SCENARIO_SALES = 'sales';

    public static function collectionName(){
        return 'products';
    }

    /**
     * Devuelve un array con la lista de atributos de la colección
     */
    public function attributes(){
        return ['_id', 'name', 'price', 'description', 'quantity'];
    }

    public function scenarios(){
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SALES] = ['name', 'price', 'quantity'];

        return $scenarios;
    }

    public function rules(){
        return[
            [['name', 'price', 'quantity'], 'required', 'on' => self::SCENARIO_SALES],
            [['name', 'price'], 'required'],
            [['price'], 'number'],
            [['quantity'], 'integer'],
            [['description'], 'safe']
        ];
    }

    public function attributeLabels(){
        return[
            '_id' => 'Producto',
            'name' => 'Nombre del producto',
            'price' => 'Precio',
            'description' => 'Descripción',
            'quantity' => 'Cantidad'
        ];
    }

    /**
     * Describir función
     */
    public function get_products(){
        $query = new Query();
        $query->from($this->collectionName());

        $provider = new ActiveDataProvider([
            'query' => $query
        ]);

        return $provider->getModels();
    }
}