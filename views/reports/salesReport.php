<?php
    $totalSales = 0;
?>

<?php foreach($sales as $i => $sale){ ?>
    <div class="indent-1">
        <h3>Venta <?= $i + 1 ?> (Fecha <?= date('d-m-Y h:i', $sale['date']) ?>) </h3>
        <?php 
            $saleMount = 0;
        ?>
        <?php foreach($sale['items'] as $item){ ?>
            <div class="indent-2">
                Producto: <?= $item['name'] ?> <br>
                Cantidad: <?= $item['quantity'] ?> <br>
                Precio: <?= $item['price'] ?> <br>
            </div>

            <hr />

            <?php 
                $saleMount += $item['price'];
            ?>
        <?php } ?>
        <div class="indent-2">
            <b>Monto compra:</b> <?= $saleMount ?>
        </div>

        <?php
            $totalSales += $saleMount;
        ?>      
    </div>
<?php } ?>

<hr />

<div class="indent-1">
    <b>Monto final de ventas:</b> <?= $totalSales ?>
</div>