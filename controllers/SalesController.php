<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Sales;
use app\models\Products;
use app\models\Clients;
use app\models\ProductsSearch;
use app\models\SalesSearch;
use app\models\Configuration;
use app\models\ClientsTransaction;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\base\Model;
use DateTime;
use DateTimeZone;


class SalesController extends Controller{

    public function actionMain(){
        $searchSales = new SalesSearch();
        $dataProvider = $searchSales->getSales(Yii::$app->request->queryParams);

        return $this->render('main', [
            'dataSales' => $dataProvider,
            'searchModel' => $searchSales
        ]);
    }

    public function actionIndex(){
        $modelSales = new Sales();
        $modelsProduct = [new Products(['scenario' => Products::SCENARIO_SALES])];

        if($modelSales->load(Yii::$app->request->post())){
            $modelsProduct = Model::createMultiple(Products::classname());
            Model::loadMultiple($modelsProduct, Yii::$app->request->post());

            $valid = $modelSales->validate();
            $valid = Model::validateMultiple($modelsProduct) && $valid;

            if($valid){
                $items = [];
                $priceSale = 0;

                foreach($modelsProduct as $modelProduct){
                    $test = $modelProduct->getAttributes();

                    $item = [
                        'idProduct' => $modelProduct->idsaleproduct,
                        'name' => $modelProduct->name,
                        'price' => $modelProduct->price,
                        'quantity' => $modelProduct->quantity
                    ];

                    $priceSale += $modelProduct->price * $modelProduct->quantity;

                    $items[] = $item;
                }

                $modelSales['date'] = time() - 10800; // GMT - 3 Según zona horaria en argentina
                $modelSales['items'] = $items;
                $modelSales['price'] = $priceSale;

                $modelSales->save(false);  // Guardamos la venta realizada

                if(isset($modelSales['client']) && $modelSales['client'] != ''){  // Si se ingreso el dni del cliente entonces se calculan los puntos
                    $clientDb = Clients::findOne(['dni' => $modelSales['client']]);
                    $configurationDb = Configuration::find()->one();

                    $points = ($configurationDb) ? $configurationDb['mountByPoint'] : 0;

                    if($points != 0){
                        // Puntos para el cliente
                        $pointsSale = round($modelSales['price'] / $points, 2);
                        $clientDb['points'] += $pointsSale;
                        $clientDb->save();
                        // ----------------------

                        // Transacción - Inicio
                        $modelClientTransaction = new ClientsTransaction();
                        $modelClientTransaction['updatedPoints'] = (float) $pointsSale;
                        $modelClientTransaction['amount'] = (float) $clientDb['points'];
                        $modelClientTransaction['dni'] = $modelSales['client'];
                        $modelClientTransaction['type'] = 'sale';
                        $modelClientTransaction['id_sale'] = $modelSales['_id'];
                        $modelClientTransaction['date'] = time() - 10800; // GMT - 3 Según zona horaria en argentina

                        $modelClientTransaction->save();
                        // --------------------
                        
                        Yii::$app->session->setFlash('success', 'Se agregaron ' . '<b>' . $pointsSale . '</b>' . ' puntos al cliente ' . '<b>' . $clientDb['lastname'] . ', ' . $clientDb['firstname'] . '</b>');
                    }else{
                        Yii::$app->session->setFlash('error', 'Los puntos no se cargaron debido a que la configuración de puntos por monto de venta no esta cargada o es cero');
                    }
                }else{
                    Yii::$app->session->setFlash('success', 'La venta se realizó con éxito');
                }

                return $this->redirect(['main']);
            }
        }

        $modelSales['date'] = $this->gettime()->format('d-m-Y h:i A');
        $dataProducts = ArrayHelper::map(Products::find()->all(), function ($model){return (string) $model->_id;}, 'name');

        return $this->render('index', [
            'modelSales' => $modelSales, 
            'modelsProduct' => $modelsProduct,
            'dataProducts' => $dataProducts
        ]);
    }

    private function getTime(){
        $timeSale = time();
        $dateTime = DateTime::createFromFormat('U', $timeSale);
        $dateTime->setTimezone(new DateTimeZone('America/Argentina/Buenos_Aires'));

        return $dateTime;
    }

    public function actionDataproduct(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $idProduct = $_POST['idProduct'];

        $modelProduct = new ProductsSearch();
        $dataProduct = $modelProduct->get_single_product($idProduct);
        if(!$dataProduct){
            $out = [
                'result' => 'error',
                'message' => 'Hubo un error al intentar obtener los datos del producto'
            ];
            return $out;
        }

        $out = [
            'result' => 'success',
            'idProduct' => (string) $dataProduct['_id'],
            'name' => $dataProduct['name'],
            'price' => $dataProduct['price']
        ];

        return $out;
    }

    public function actionGetconfigdata(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $configurationModel = new Configuration();
        $configurationDb = $configurationModel->find()->one();
        if($configurationDb){
            $mountByPoint = $configurationDb['mountByPoint'];
        }else{
            $mountByPoint = '';
        }

        $out = ['result' => 'success', 'mountByPoint' => $mountByPoint];

        return $out;
    }

    public function actionConfigsales(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $mountByPoint = $_POST['mountByPoint'];

        $configurationModel = new Configuration();
        $configurationModel['mountByPoint'] = (float) $mountByPoint;

        if($configurationModel->validate()){
            $configurationDb = $configurationModel->find()->one();

            if($configurationDb){
                $configurationDb['mountByPoint'] = (float) $mountByPoint;
                $configurationDb->save();
            }else{
                $configurationModel->save();    
            }

            $out = [
                'result' => 'success'
            ];
        }else{
            $out = [
                'result' => 'error',
                'message' => 'Error de validación de datos'
            ];
        }

        return $out;
    }
}