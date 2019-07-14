<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="containter">
    <h1><?= Html::encode($this->title) ?> </h1>

    <?= 
    GridView::widget([
        'dataProvider' => $dataProducts,
        'filterModel' => $searchModel,
        'emptyText' => '¡No se encontraron clientes',
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
                'template' => '{update}{change}',
                'header' => 'Acción',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
                                    'title' => Yii::t('app', 'Sumar/Restar Puntos'), 'class' => 'updatePoints',
                        ]);
                    },
                    'change' => function ($url, $model) {
                        return Html::button('<span class="glyphicon glyphicon-tags"></span>', [
                                    'title' => Yii::t('app', 'Canjear Puntos'), 'class' => 'changePoints',
                        ]);
                    }
                ]
            ]
        ],
        'hover' => true,
        'responsive' => true,
        'export' => false,
    ]);
    ?>
</div>

<!-- Popup de asignación manual de puntos -->
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

<!-- Popup de canjeo de puntos -->
<div class="modal fade" id="changePoints_popup" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?= Html::button('', ['class' => 'close', 'data-dismiss' => 'modal', 'aria-hidden' => 'true']) ?>
                <h4 class="modal-title">Canjear Puntos</h4>
            </div>

            <div class="modal-body">
                <form id="form-changePoints">
                    <div class="form-group">
                        <label for="dni">Dni</label>
                        <input id="txt-dni-points" class="form-control" name="dni" disabled required/>
                    </div>

                    <div class="form-group">
                        <label for="selectPoints">Puntos a canjear</label>
                        <select id="sl-points" class="form-control" name="selectPoints">
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="">Otro..</option>
                        </select>
                        <input id="txt-points-change" class="form-control" name="points" type="number" placeholder="Ingrese la cantidad de puntos que desee"/>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Canjear', ['class' => 'btn btn-primary']) ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
