<?php
namespace app\models;

use yii\data\ActiveDataProvider;
use yii\mongodb\ActiveRecord;
use yii\mongodb\Query;
use app\models\Products;

class ProductsSearch extends Products{

    public function rules(){
        return[
            [['name'], 'safe']
        ];
    }

    public function getProducts($params){
        $query = new Query();
        $query->from('products');
        $results = $query->all();
        //$query = Products::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        $this->load($params);

        $test = $this->validate();
        if(!$this->validate()){
            return $dataProvider;
        }

        $query->where(['name' => $this->name]);

        return $dataProvider;
    }
}
?>