<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Products;
use app\models\ProductsSearch;
use yii\data\ActiveDataProvider;

class ProductsController extends Controller{

    /**
     * Despliegue de la pagina home para productos
     */
    public function actionIndex(){
        $searchProducts = new ProductsSearch();
        $dataProvider = $searchProducts->getProducts(Yii::$app->request->queryParams);

        return $this->render('home', [
            'dataProducts' => $dataProvider,
            'searchModel' => $searchProducts
        ]);
    }

    public function actionCreate(){
        return $this->render('create');
    }
}