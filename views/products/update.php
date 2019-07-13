<?php

use yii\helpers\Html;

$this->title = 'Actualizar Producto: ' . $model['name'];
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model['name'], 'url' => ['view', 'id' => (string) $model['_id']]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>

<div class="container">
<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
        'model' => $model
    ]) ;
?>
</div>