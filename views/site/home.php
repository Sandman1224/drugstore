<?php
use yii\helpers\Html;
?>

<div id="mainButtons">
    <?= 
        Html::a("Productos", ['products/index'], ['id' => 'btnProducts', 'class' => 'mainButton']);
    ?>
    <?= 
        Html::a("Ventas", ['sales/index'], ['id' => 'btnSales', 'class' => 'mainButton']);
    ?>
</div>