<?php
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Productos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="containter">
    <h1><?= Html::encode($this->title) ?> </h1>

    <?= Html::a('<span class="glyphicon glyphicon-plus"></span>', ['create'], ['class' => 'btn btn-primary']) ?>

    <?= 
    GridView::widget([
        'dataProvider' => $dataProducts,
        'filterModel' => $searchModel,
        'emptyText' => '¡No se encontraron productos',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Acción',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => Yii::t('app', 'Ver Producto'),
                        ]);
                    },
        
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                    'title' => Yii::t('app', 'Actualizar Producto'),
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Eliminar Producto'),
                            'data' => [
                                'confirm' => '¿Esta seguro de que desea eliminar este producto?',
                                'method' => 'post',
                            ],
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        $url ='index.php?r=products/view&id=' . $model['_id'];
                        return $url;
                    }
                    if ($action === 'update') {
                        $url ='index.php?r=products/update&id=' . $model['_id'];
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url ='index.php?r=products/delete&id=' . $model['_id'];
                        return $url;
                    }
        
                },
            ],
        ],
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'hover' => true,
        'responsive' => true,
        'export' => false,
    ]);
    ?>
</div>