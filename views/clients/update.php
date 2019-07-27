<?php

use yii\helpers\Html;

$this->title = 'Actualizar Cliente: ' . $model['lastname'] . ', ' . $model['firstname'];
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model['lastname'] . ', ' . $model['firstname'], 'url' => ['view', 'id' => (string) $model['_id']]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>

<div class="container">
<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
        'model' => $model
    ]) ;
?>
</div>