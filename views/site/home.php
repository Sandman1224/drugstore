<?php
use yii\helpers\Html;
?>

<div id="mainButtons">
    <div class ="row">
        <div class="col">
        <?=
            Html::a("Productos", ['products/index'], ['id' => 'btnProducts', 'class' => 'mainButton']);
        ?>
        </div>

        <div class="col">
        <?=
            Html::a("Ventas", ['sales/main'], ['id' => 'btnSales', 'class' => 'mainButton']);
        ?>
        </div>
    </div>

    <div class="row">
        <?=
            Html::a("Puntos", ['clients/index'], ['id' => 'btnPoints', 'class' => 'btn-info']);
        ?>
    </div>
</div>