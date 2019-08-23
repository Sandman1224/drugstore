<?php
    use yii\helpers\Html;
    use kartik\grid\GridView;
    use kartik\daterange\DateRangePicker;
    use yii\widgets\ActiveForm;

    $this->title = 'Ventas';
    $this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <h1><?= Html::encode($this->title) ?> </h1>

    <?= Html::a('<span class="glyphicon glyphicon-plus"></span>', ['index'], ['class' => 'btn btn-primary']) ?>
    <?= Html::button('<span class="glyphicon glyphicon-cog"></span>', ['id' => 'configSales', 'class' => 'btn btn-warning']); ?>
    <?= Html::button('<span class="glyphicon glyphicon-file"></span>', ['class' => 'btn btn-info salesSelect']) ?>

    <?=
    GridView::widget([
        'id' => 'salesTable',
        'dataProvider' => $dataSales,
        'filterModel' => $searchModel,
        'emptyText' => '¡No se encontraron ventas realizadas',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => '_id',
                'hidden' => true
            ],
            [
                'attribute' => 'date',
                'label' => 'Fecha de Compra',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_range',
                    'value'=>'01-Jan-18 to 20-Feb-19',
                    'convertFormat' => true,
                    'startAttribute'=>'from_date',
                    'endAttribute'=>'to_date',
                    'pluginOptions' => [
                        'timePicker' => true,
                        'timePickerIncrement' => 10,
                        'locale' => [
                            'format' => 'd-M-y h:i A',
                            'separator' => ' - '
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
            [
                'class' => 'yii\grid\CheckboxColumn'
            ]
        ],
        'showPageSummary' => true,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
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

<div class="modal fade" id="exportSales_popup" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?= Html::button('', ['class' => 'close', 'data-dismiss' => 'modal', 'aria-hidden' => 'true']) ?>
                <h4 class="modal-title">Informe de Ventas</h4>
            </div>

            <div class="modal-body">
                <?php $reportForm = ActiveForm::begin(['action' => ['exportbyranges'], 'options' => ['method' => 'post']]) ?>
                <label class="control-label">Rango de Fechas</label>
                <div class="drp-container">
                    <?=
                    DateRangePicker::widget([
                        'id' => 'export_daterangepicker',
                        'name' => 'date_range_report',
                        'convertFormat' => true,
                        'startAttribute' => 'from_date_report',
                        'endAttribute' => 'to_date_report',
                        'pluginOptions' => [
                            'timePicker' => true,
                            'timePickerIncrement' => 10,
                            'locale' => [
                                'format' => 'd-M-y h:i A',
                                'separator' => ' - '
                            ]
                        ],
                        'presetDropdown' => true,
                        'hideInput' => true
                    ])
                    ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Generar', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>