<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Json;
use yii\web\View;

$this->title = $model['name'];
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => (string) $model['_id']], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Eliminar', ['delete', 'id' => $model['_id']], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Esta seguro de que desea eliminar este producto?',
                'method' => 'post',
            ],
        ]);
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'price',
            'quantity',
            'description'
        ],
    ])
    ?>
</div>