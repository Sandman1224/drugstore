<?php
use yii\helpers\Html;
?>

<div id="mainButtons">
    <?= 
        Html::a("Productos", ['products/index'], ['id' => 'btnProducts', 'class' => 'mainButton']);
    ?>
    <?= 
        Html::a("Ventas", ['sales/main'], ['id' => 'btnSales', 'class' => 'mainButton']);
    ?>
</div>