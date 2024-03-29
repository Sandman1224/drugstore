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

    public function actionCreate(){
        $clientModel = new Clients();

        $clientModel['points'] = 0;

        if($clientModel->load(Yii::$app->request->post())){
            $clientModel['dni'] = strtoupper($clientModel['dni']);

            if($clientModel->validate()){
                $clientModel->insert();
                
                return $this->redirect(['view', 'id' => (string) $clientModel['_id']]);
            }else{
                return $this->render('create', ['model' => $clientModel]);    
            }
        }else{
            return $this->render('create', ['model' => $clientModel]);
        }
    }

    public function actionUpdate($id){
        $clientModel = $this->findClient($id);
        $clientModel->scenario = Clients::SCENARIO_UPDATE;

        if ($clientModel->load(Yii::$app->request->post())) {
            $clientModel['dni'] = strtoupper($clientModel['dni']);

            if($clientModel->validate()){
                //$pointsUpdated = $clientModel['pointsUpdated'] != "" && $clientModel['pointsUpdated'] != 0 ? ;
                if($clientModel['pointsUpdated'] != "" && $clientModel['pointsUpdated'] != 0){
                    $pointsResult = round($clientModel['points'], 2) + round((float) $clientModel['pointsUpdated'], 2);
                    $clientModel['points'] = $pointsResult;
    
                    // Transacción - Inicio
                    $modelClientTransaction = new ClientsTransaction();
                    $modelClientTransaction['updatedPoints'] = round((float) $clientModel['pointsUpdated'], 2);
                    $modelClientTransaction['amount'] = (float) $clientModel['points'];
                    $modelClientTransaction['dni'] = $clientModel['dni'];
                    $modelClientTransaction['type'] = 'user';
                    $modelClientTransaction['date'] = time() - 10800; // GMT - 3 Según zona horaria en argentina
    
                    $modelClientTransaction->save();
                    // Transacción - Fin
                }

                $clientModel->save(false);

                //Guardamos los cambios de la obra seleccionada
                return $this->redirect(['view', 'id' => (string) $clientModel['_id']]);
            }else{
                return $this->render('update', [
                    'model' => $clientModel
                ]);    
            }
        } else {
            return $this->render('update', [
                    'model' => $clientModel
            ]);
        }
    }

    public function actionDelete($id){
        $modelClient = $this->findClient($id);
        $modelClient->delete();

        return $this->redirect(['index']);
    }

    public function actionView($id){
        return $this->render('view', [
            'model' => $this->findClient($id)
        ]);
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

    public function actionChangepoints(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $dniClient = $_POST['dniClient'];
        $pointsToChange = $_POST['pointsToChange'];

        $modelClient = new Clients();
        $client = $modelClient->findOne(['dni' => $dniClient]);

        if(!isset($client)){
            $out = [
                'result' => 'error',
                'message' => 'No se encontró el cliente seleccionado'
            ];

            return $out;
        }

        $pointsResult = round($client['points'] - (float) $pointsToChange, 2);

        if($pointsResult < 0){
            $out = [
                'result' => 'error',
                'message' => 'El cliente no posee la cantidad de puntos suficientes para realizar el canje'
            ];

            return $out;
        }

        $client['points'] = $pointsResult;
        $client->save();

        // Transacción - Inicio
        $modelClientTransaction = new ClientsTransaction();
        $modelClientTransaction['updatedPoints'] = round((float) $pointsToChange, 2);
        $modelClientTransaction['amount'] = (float) $client['points'];
        $modelClientTransaction['dni'] = $client['dni'];
        $modelClientTransaction['type'] = 'change';
        $modelClientTransaction['date'] = time() - 10800; // GMT - 3 Según zona horaria en argentina

        $modelClientTransaction->save();
        // Transacción - Fin

        $out = [
            'result' => 'success',
            'changedPoints' => round($pointsToChange, 2)
        ];

        return $out;
    }

    protected function findClient($idProduct){
        if(($model = Clients::findOne($idProduct)) !== null){
            return $model;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
