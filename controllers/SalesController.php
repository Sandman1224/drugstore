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
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\base\Model;


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
                    $item = [
                        'name' => $modelProduct->name,
                        'price' => $modelProduct->price,
                        'quantity' => $modelProduct->quantity
                    ];

                    $priceSale += $modelProduct->price * $modelProduct->quantity;

                    $items[] = $item;
                }

                $modelSales['date'] = time();
                $modelSales['items'] = $items;
                $modelSales['price'] = $priceSale;

                $modelSales->save(false);  // Guardamos la venta realizada

                if(isset($modelSales['client'])){  // Si se ingreso el dni del cliente entonces se calculan los puntos
                    $clientDb = Clients::findOne(['dni' => (int) $modelSales['client']]);
                    $configurationDb = Configuration::find()->one();

                    $points = ($configurationDb) ? $configurationDb['mountByPoint'] : 0;

                    if($points != 0){
                        $pointsSale = $modelSales['price'] / $points;
                        $clientDb['points'] += $pointsSale;
                        $clientDb->save();
                        
                        Yii::$app->session->setFlash('success', 'Se agregaron ' . '<b>' . $pointsSale . '</b>' . ' puntos al cliente ' . '<b>' . $clientDb['lastname'] . ', ' . $clientDb['firstname'] . '</b>');
                    }else{
                        Yii::$app->session->setFlash('error', 'Los puntos no se cargaron debido a que la configuración de puntos por monto de venta no esta cargada o es cero');
                    }

                    return $this->redirect(['main']);
                }
            }
        }

        $modelSales['date'] = date('d-m-Y h:i A');
        $dataProducts = ArrayHelper::map(Products::find()->all(), function ($model){return (string) $model->_id;}, 'name');

        return $this->render('index', [
            'modelSales' => $modelSales, 
            'modelsProduct' => $modelsProduct,
            'dataProducts' => $dataProducts
        ]);
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
        $configurationModel['mountByPoint'] = (int) $mountByPoint;

        if($configurationModel->validate()){
            $configurationDb = $configurationModel->find()->one();

            if($configurationDb){
                $configurationDb['mountByPoint'] = (int) $mountByPoint;
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