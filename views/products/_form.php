<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="productos-form">
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'name')->textInput(); ?>

        <?= $form->field($model, 'price')->textInput(); ?>

        <?= $form->field($model, 'description')->textInput(); ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>