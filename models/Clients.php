<?php

namespace app\models;

use yii\mongodb\ActiveRecord;
use yii\mongodb\Query;
use yii\data\ActiveDataProvider;

class Clients extends ActiveRecord{
    const SCENARIO_UPDATE = 'update';

    public $pointsUpdated;

    public static function collectionName(){
        return 'clients';
    }

    /**
     * Devuelve un array con la lista de atributos de la colección
     */
    public function attributes(){
        return ['_id', 'dni', 'firstname', 'lastname', 'points', 'pointsUpdated'];
    }

    public function scenarios(){
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPDATE] = ['dni', 'firstname', 'lastname', 'points','pointsUpdated'];

        return $scenarios;
    }

    public function rules(){
        return[
            [['firstname', 'lastname', 'dni'], 'required', 'message' => 'Campo requerido'],
            ['dni', 'unique', 'message' => 'Ya existe un cliente con este dni o pasaporte'],
            [['dni'], 'unique', 'on' => 'update', 'when' => function($model){
                return $model->isAttributeChanged('dni');
            }],
            [['points', 'pointsUpdated'], 'number'],
            [['pointsUpdated'], 'safe'],
            [['pointsUpdated'], 'validatePoints', 'on' => 'update'],
        ];
    }

    public function attributeLabels(){
        return[
            '_id' => 'Producto',
            'dni' => 'Dni o Pasaporte',
            'firstname' => 'Nombre/s',
            'lastname' => 'Apellido/s',
            'points' => 'Puntos',
            'pointsUpdated' => 'Sumar o restar puntos'
        ];
    }

    public function searchClientByDni($dni){
        $client = $this::findOne(['dni' => $dni]);

        return $client;
    }

    public function validatePoints($attribute, $params){
        $resultPoints = (float) round($this->points + $this->pointsUpdated, 2);

        if($resultPoints < 0){
            $this->addError($attribute, 'No posee la cantidad suficiente de puntos para actualizar');
        }
    }
}