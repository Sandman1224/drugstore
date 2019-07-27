<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="containter">
    <h1><?= Html::encode($this->title) ?> </h1>

    <?= Html::a('<span class="glyphicon glyphicon-plus"></span>', ['create'], ['class' => 'btn btn-primary', 'title' => Yii::t('app', 'Crear Cliente')]) ?>

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
                'template' => '{view}{update}{delete}{updatepoints}{change}',
                'header' => 'Acción',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => Yii::t('app', 'Ver Cliente'),
                        ]);
                    },
                    'change' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-usd"></span>', $url, [
                                    'title' => Yii::t('app', 'Canjear Puntos'), 'class' => 'changePoints',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Eliminar Cliente'),
                            'data' => [
                                'confirm' => '¿Esta seguro de que desea eliminar este cliente? Sus puntos se borrarán también',
                                'method' => 'post',
                            ],
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        $url ='index.php?r=clients/view&id=' . $model['_id'];
                        return $url;
                    }
                    if ($action === 'update') {
                        $url ='index.php?r=clients/update&id=' . $model['_id'];
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url ='index.php?r=clients/delete&id=' . $model['_id'];
                        return $url;
                    }
                }
            ]
        ],
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'hover' => true,
        'responsive' => true,
        'export' => false,
    ]);
    ?>
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
                        <input id="txt-points-change" class="form-control" name="points" type="number" step="any" placeholder="Ingrese la cantidad de puntos que desee"/>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Canjear', ['class' => 'btn btn-primary']) ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
