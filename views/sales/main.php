<?php
    use yii\helpers\Html;
    use kartik\grid\GridView;
    use kartik\daterange\DateRangePicker;

    $this->title = 'Ventas';
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <h1><?= Html::encode($this->title) ?> </h1>

    <?= Html::a('<span class="glyphicon glyphicon-plus"></span>', ['index'], ['class' => 'btn btn-primary']) ?>
    <?= Html::button('<span class="glyphicon glyphicon-cog"></span>', ['id' => 'configSales', 'class' => 'btn btn-warning']); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataSales,
        'filterModel' => $searchModel,
        'emptyText' => '¡No se encontraron ventas realizadas',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => '_id',
                'visible' => false
            ],
            [
                'attribute' => 'date',
                'label' => 'Fecha de Compra',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_range',
                    'convertFormat' => true,
                    'startAttribute'=>'from_date',
                    'endAttribute'=>'to_date',
                    'pluginOptions' => [
                        'timePicker' => true,
                        'timePickerIncrement' => 10,
                        'locale' => [
                            'format' => 'd-m-Y h:i A'
                        ]
                    ],
                    'pluginEvents' => [
                        'cancel.daterangepicker' => 'function(ev, picker){
                            $("#salessearch-date_range").val("");
                            $("#salessearch-date_range").attr("value", "");
                            $("#salessearch-date_range-start").attr("value", "");
                            $("#salessearch-date_range-end").attr("value", "");
                            $(".grid-view").yiiGridView("applyFilter");
                        }'
                    ]
                ]),
                'value' => function($data){
                    $saleDate = $data['date'];
                    $dt = new DateTime("@$saleDate");
                    return $dt->format('d-m-Y h:i A');
                }
            ],
            [
                'attribute' => 'client',
                'label' => 'Cliente'
            ],
            [
                'attribute' => 'price',
                'label' => 'Monto de Compra',
                'pageSummary' => true
            ],
        ],
        'showPageSummary' => true,
        'hover' => true,
        'responsive' => true,
        'export' => false
    ]);
    ?>
</div>

<div class="modal fade" id="configSales_popup" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?= Html::button('', ['class' => 'close', 'data-dismiss' => 'modal', 'aria-hidden' => 'true']) ?>
                <h4 class="modal-title">Configuración de Ventas</h4>
            </div>

            <div class="modal-body">
                <form id="form-configSales">
                    <div class="form-group">
                        <label for="firstname">Monto por Punto</label>
                        <input id="txt-mount" class="form-control" name="mount" type="number" required/>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>