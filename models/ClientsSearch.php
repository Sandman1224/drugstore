<?php
namespace app\models;

use yii\data\ActiveDataProvider;
use yii\mongodb\ActiveRecord;
use yii\mongodb\Query;
use app\models\Clients;

class ClientsSearch extends Clients{
    public function rules(){
        return[
            [['firstname', 'lastname'], 'safe']
        ];
    }

    public function getClients($params){
        $query = new Query();
        $query->from('clients');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        $this->load($params);

        if(!$this->validate()){
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'firstname', $this->firstname])
            ->andFilterWhere(['like', 'lastname', $this->lastname]);

        return $dataProvider;
    }
}