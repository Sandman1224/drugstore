<?php

namespace app\models;

use yii\mongodb\ActiveRecord;
use yii\mongodb\Query;
use yii\data\ActiveDataProvider;

class ClientsTransaction extends ActiveRecord{
    public static function collectionName(){
        return 'client_transactions';
    }

    /**
     * Devuelve un array con la lista de atributos de la colección
     */
    public function attributes(){
        return ['_id', 'updatedPoints', 'amount', 'date', 'dni', 'type', 'id_sale'];
    }

    public function rules(){
        return[
            [['updatedPoints', 'amount', 'dni', 'date', 'type'], 'required'],
            [['id_sale'], 'safe'],
            [['updatedPoints', 'amount'], 'number']
        ];
    }

    public function attributeLabels(){
        return[
            '_id' => 'Transacción',
            'updatedPoints' => 'Puntos Actualizados',
            'amount' => 'Saldo',
            'type' => 'Tipo de transacción',
            'date' => 'Fecha'
        ];
    }
}