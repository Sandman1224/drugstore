<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin(['id' => 'pjax-grid-view']) ?>

<div class="containter">
    <h1><?= Html::encode($this->title) ?> </h1>

    <?= 
    GridView::widget([
        'dataProvider' => $dataProducts,
        'filterModel' => $searchModel,
        'emptyText' => '¡No se encontraron productos',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => '_id',
                'visible' => false
            ],
            [
                'attribute' => 'dni',
                'label' => 'Dni'
            ],
            [
                'attribute' => 'firstname',
                'label' => 'Nombre/s'
            ],
            [
                'attribute' => 'lastname',
                'label' => 'Apellido'
            ],
            [
                'attribute' => 'points',
                'label' => 'Puntos'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'header' => 'Acción',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
                                    'title' => Yii::t('app', 'Agregar Puntos'), 'class' => 'updatePoints',
                        ]);
                    },
                ]
            ]
        ],
        'hover' => true,
        'responsive' => true,
        'export' => false,
    ]);
    ?>
</div>
<?php Pjax::end(); ?>


<div class="modal fade" id="updatePoints_popup" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?= Html::button('', ['class' => 'close', 'data-dismiss' => 'modal', 'aria-hidden' => 'true']) ?>
                <h4 class="modal-title">Editar Puntos</h4>
            </div>

            <div class="modal-body">
                <form id="form-updatePoints">
                    <div class="form-group">
                        <label for="dni">Dni</label>
                        <input id="txt-dni" class="form-control" name="dni" disabled required/>
                    </div>

                    <div class="form-group">
                        <label for="firstname">Suma/Resta de puntos</label>
                        <input id="txt-points" class="form-control" name="points" type="number" step="0.25" required/>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
