<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Products;
use yii\data\ActiveDataProvider;

class ProductsController extends Controller{

    /**
     * Despliegue de la pagina home para productos
     */
    public function actionIndex(){
        $productsModel = new Products();
        $products = $productsModel->getProducts(Yii::$app->request->queryParams);

        $providerData = new ActiveDataProvider([
            'query' => $products
        ]);

        $models = $providerData->getModels();

        return $this->render('home', [
            'dataProducts' => $providerData
        ]);
    }

    public function actionCreate(){
        return $this->render('create');
    }
}