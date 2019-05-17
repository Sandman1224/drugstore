<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Productos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="containter">
    <h1><?= Html::encode($this->title) ?> </h1>

    <?= Html::a('<span class="glyphicon glyphicon-plus"></span>', ['create'], ['class' => 'btn btn-primary']) ?>

    <?= 
    GridView::widget([
        'dataProvider' => $dataProducts,
        'emptyText' => '¡No se encontraron productos',
        'columns' => [
            [
                'attribute' => '_id',
                'visible' => false
            ],
            [
                'attribute' => 'name',
                'label' => 'Nombre'
            ],
            [
                'attribute' => 'price',
                'label' => 'Precio'
            ],
            [
                'attribute' => 'description',
                'label' => 'Descripción'
            ],
            [
                'attribute' => 'quantity',
                'label' => 'Cantidad'
            ]
        ]
    ]);
    ?>
</div>