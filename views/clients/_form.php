<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="productos-form">
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'dni')->textInput(); ?>

        <?= $form->field($model, 'firstname')->textInput(); ?>

        <?= $form->field($model, 'lastname')->textInput(); ?>

        <?php if($model->scenario == 'update'){ ?>
            <?= $form->field($model, 'points')->textInput(['readOnly' => true]); ?>

            <?= $form->field($model, 'pointsUpdated')->textInput(['placeholder' => '(+/-) Por ej: 10: Suma 10 puntos, -10: Resta 10 puntos']); ?>

        <?php } ?>
        
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>