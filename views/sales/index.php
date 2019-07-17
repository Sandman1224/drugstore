<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use \kartik\select2\Select2;
use app\assets\AppAsset;

$this->title = 'Nueva venta';
$this->params['breadcrumbs'][] = ['label' => 'Ventas', 'url' => ['main']];
$this->params['breadcrumbs'][] = 'Nueva venta';
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="container">
    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($modelSales, 'date')->textInput(['readonly' => true]) ?>
            </div>

            <div class="col-sm-6">
                <?= $form->field($modelSales, 'client')->textInput() ?>

                <div class="buttonsForm">
                    <?= Html::button('Buscar', ['id' => 'btnFind-client', 'class' => 'btn btn-primary']); ?>
                    <?= Html::tag('span', '', ['class' => 'clientFound']) ?>
                    <?= Html::button('<i class="glyphicon glyphicon-user"> Nuevo cliente</i>', ['id' => 'btn-newClient', 'class' => 'btn btn-info']); ?>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="glyphicon glyphicon-th-list"></i> Productos</h4>
            </div>

            <div class="panel-body">
                <?php
                    DynamicFormWidget::begin([
                        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                        'widgetBody' => '.container-items', // required: css class selector
                        'widgetItem' => '.item', // required: css class
                        'limit' => 999, // the maximum times, an element can be cloned (default 999)
                        'min' => 1, // 0 or 1 (default 1)
                        'insertButton' => '.add-item', // css class
                        'deleteButton' => '.remove-item', // css class
                        'model' => $modelsProduct[0],
                        'formId' => 'dynamic-form',
                        'formFields' => [
                            '_id',
                            'name',
                            'price',
                            'quantity'
                        ],
                    ]);
                 ?>

                 <div class="container-items">
                    <?php foreach($modelsProduct as $i => $modelsProduct){ ?>
                        <div class="item panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left">Item</h3>
                                <div class="pull-right">
                                    <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                    <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="panel-body">
                                <?php
                                    // necessary for update action.
                                    //if (! $modelAddress->isNewRecord) {
                                    //    echo Html::activeHiddenInput($modelAddress, "[{$i}]id");
                                    //}
                                ?>

                                <?= $form->field($modelsProduct, "[{$i}]_id")->widget(Select2::classname(), [
                                    'data' => $dataProducts,
                                    'options' => [
                                        'placeholder' => 'Seleccione un producto', 
                                        'class' => 'selectProduct'
                                    ]
                                ]); 
                                ?>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <?= $form->field($modelsProduct, "[{$i}]name")->textInput(['class' => 'productName form-control', 'readOnly' => true]) ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                    <?= $form->field($modelsProduct, "[{$i}]price")->textInput(['type' => 'number', 'class' => 'productPrice form-control', 'readOnly' => true]) ?>
                                    </div>

                                    <div class="col-sm-6">
                                    <?= $form->field($modelsProduct, "[{$i}]quantity")->textInput(['type' => 'number', 'class' => 'productQuantity form-control', 'min' => 1, 'value' => 1]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                 </div>

                <!-- Precio Final -->
                <div class="row">
                    <div class="col-sm-12">
                        <?= $form->field($modelSales, 'price')->textInput(['value' => 0, 'readOnly' => true]) ?>
                    </div>
                </div>

                 <div class="form-group">
                    <?= Html::submitButton($modelsProduct->isNewRecord ? 'Finalizar' : 'Actualizar', ['class' => 'btn btn-primary']) ?>
                 </div>

                 <?php DynamicFormWidget::end(); ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>

<div class="modal fade" id="client_popup" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?= Html::button('', ['class' => 'close', 'data-dismiss' => 'modal', 'aria-hidden' => 'true']) ?>
                <h4 class="modal-title">Añadir Cliente</h4>
            </div>

            <form id="form-client">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="firstname">Nombre/s</label>
                        <input id="txt-firstname" class="form-control" name="firstname" type="text" required/>
                    </div>

                    <div class="form-group">
                        <label for="lastname">Apellido/s</label>
                        <input id="txt-lastname" class="form-control" name="lastname" type="text" required/>
                    </div>

                    <div class="form-group">
                        <label for="dni">Dni</label>
                        <input id="txt-dni" class="form-control" name="dni"/>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Crear', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>

                <div id="errors">
                    <!-- Contenido generado dinámicamente -->
                </div>
            </form>

        </div>
    </div>
</div>