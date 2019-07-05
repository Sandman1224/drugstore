<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Clients;

class ClientsController extends Controller{

    public function actionFindclientbyajax(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $dniClient = $_POST['dniClient'];

        $modelClient = new Clients();
        $dataClient = $modelClient->searchClientByDni($dniClient);
        if(!$dataClient){
            $out = [
                'result' => 'error',
                'message' => 'No se encontraron los datos del cliente'
            ];
            return $out;
        }

        $out = [
            'result' => 'success',
            'firstname' => $dataClient['firstname'],
            'lastname' => $dataClient['lastname'],
            'dni' => $dataClient['dni']
        ];

        return $out;
    }

    public function actionCreatebyajax(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Datos recibidos del frontend
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $dni = $_POST['dni'];

        // Carga del modelo
        $modelClient = new Clients();
        $modelClient['firstname'] = $firstname;
        $modelClient['lastname'] = $lastname;
        $modelClient['dni'] = (int) $dni;
        $modelClient['points'] = 0;

        if(!$modelClient->validate()){
            $errors = $modelClient->getErrors();
            $out = [
                'result' => 'error',
                'errors' => $errors
            ];
        }else{
            $modelClient->insert();

            $out = [
                'result' => 'success',
                'client' => $modelClient['dni']
            ];
        }

        return $out;
    }
}