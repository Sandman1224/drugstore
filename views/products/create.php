<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

$this->title = 'Nuevo Producto';
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model
    ])
    ?>
</div>