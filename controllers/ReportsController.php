<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class ReportsController extends Controller{

    public function actionSales(){
        $data = '[
            {
                "price": 110,
                "date": 1564710168,
                "items": [
                    {
                        "name": "Gaseosa Paso de los toros 350cc",
                        "price": 20,
                        "quantity": 1
                    },
                    {
                        "name": "Papas lays 500 gr.",
                        "price": 90,
                        "quantity": 1
                    }
                ]
            },
            {
                "price": 110,
                "date": 1564710168,
                "items": [
                    {
                        "name": "Gaseosa Paso de los toros 350cc",
                        "price": 20,
                        "quantity": 1
                    },
                    {
                        "name": "Papas lays 500 gr.",
                        "price": 90,
                        "quantity": 1
                    }
                ]
            }
        ]';

        $salesData = json_decode($data, true);


        return $this->render("salesReport", [
            "sales" => $salesData
        ]);
    }
}