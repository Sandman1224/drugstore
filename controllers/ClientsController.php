<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Clients;
use app\models\ClientsSearch;
use app\models\ClientsTransaction;

class ClientsController extends Controller{

    public function actionIndex(){
        $searchClients = new ClientsSearch();
        $dataProvider = $searchClients->getClients(Yii::$app->request->queryParams);

        return $this->render('home', [
            'dataProducts' => $dataProvider,
            'searchModel' => $searchClients
        ]);
    }

    public function actionUpdatepoints(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $dniClient = $_POST['dniClient'];
        $pointsUpdated = $_POST['pointsUpdated'];

        $modelClient = new Clients();
        $client = $modelClient->findOne(['dni' => (int) $dniClient]);

        if(!isset($client)){
            $out = [
                'result' => 'error',
                'message' => 'No se encontró el cliente seleccionado'
            ];

            return $out;
        }

        $client['points'] += floatval($pointsUpdated);
        $client->save();

        // Transacción - Inicio
        $modelClientTransaction = new ClientsTransaction();
        $modelClientTransaction['updatedPoints'] = floatval($pointsUpdated);
        $modelClientTransaction['amount'] = floatval($client['points']);
        $modelClientTransaction['dni'] = $client['dni'];
        $modelClientTransaction['type'] = 'user';
        $modelClientTransaction['date'] = time();

        $modelClientTransaction->save();
        // Transacción - Fin

        $out = [
            'result' => 'success',
            'updatedPoints' => $client['points']
        ];

        return $out;
    }

    public function actionFindclientbyajax(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $dniClient = $_POST['dniClient'];

        $modelClient = new Clients();
        $dataClient = $modelClient->searchClientByDni(strtoupper($dniClient));
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
        $modelClient['dni'] = strtoupper($dni);
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
