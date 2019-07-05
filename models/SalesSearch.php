<?php
namespace app\models;

use yii\data\ActiveDataProvider;
use yii\mongodb\ActiveRecord;
use yii\mongodb\Query;
use app\models\Sales;
use kartik\daterange\DateRangeBehavior;

class SalesSearch extends Sales{

    public $date_range;
    public $from_date;
    public $to_date;

    public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::className(),
                'attribute' => 'date_range',
                'dateStartAttribute' => 'from_date',
                'dateEndAttribute' => 'to_date',
            ]
        ];
    }

    public function rules(){
        return[
            [['from_date', 'to_date', 'date_range', 'client'], 'safe'],
            [['date_range'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
        ];
    }
    
    
    public function getSales($params){
        $query = new Query();
        $query->from('sales');

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

        $query->andFilterWhere(['>=', 'date', $this->from_date])
        ->andFilterWhere(['<', 'date', $this->to_date])
        ->andFilterWhere(['like', 'client', $this->client]);

        return $dataProvider;
    }
}