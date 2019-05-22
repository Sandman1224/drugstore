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
        $productModel = new Products();

        if($productModel->load(Yii::$app->request->post()) && $productModel->insert()){
            return $this->redirect(['view', 'id' => (string) $productModel['_id']]);
        }else{
            return $this->render('create', ['model' => $productModel]);
        }
    }

    public function actionView($id){
        return $this->render('view', [
            'model' => $this->findProduct($id)
        ]);
    }

    public function actionUpdate($id){
        $productModel = $this->findProduct($id);

        if ($productModel->load(Yii::$app->request->post()) && $productModel->save()) {
            //Guardamos los cambios de la obra seleccionada
            return $this->redirect(['view', 'id' => (string) $productModel['_id']]);
        } else {
            return $this->render('update', [
                    'model' => $productModel
            ]);
        }
    }

    public function actionDelete($id){
        $modelProduct = $this->findProduct($id);
        $modelProduct->delete();

        return $this->redirect(['index']);
    }

    protected function findProduct($idProduct){
        if(($model = Products::findOne($idProduct)) !== null){
            return $model;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}