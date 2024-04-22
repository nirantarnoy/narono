<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Location $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="location-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'company_id')->widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Company::find()->all(), 'id', 'name'),
                'options' => [
                    'placeholder' => '--เลือกบริษัท--'
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?= $form->field($model, 'status')->widget(\toxor88\switchery\Switchery::className())->label(false) ?>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
