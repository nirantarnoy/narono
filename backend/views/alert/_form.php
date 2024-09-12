<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/** @var yii\web\View $this */
/** @var backend\models\Alert $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="alert-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="col-lg-3">
            <?php $model->trans_date = $model->isNewRecord ? date('Y-m-d') : date('Y-m-d', strtotime($model->trans_date)); ?>
            <?= $form->field($model, 'trans_date')->widget(DateTimePicker::className(), [
                'options' => [
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                    'format' => 'dd-mm-yyyy',
                ],
                'pluginOptions' => [
                    //'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true,
                    'todayBtn' => true,
                ]
            ]) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'alert_month')->textInput() ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'emp_id')->Widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->where(['status' => '1'])->all(), 'id', function ($data) {
                    return $data->fname. ' ' . $data->lname;
                }),
                'options' => [
                    'placeholder' => '--เลือก--',
                    'onchange' => '',
                ]
            ]) ?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-3"><?= $form->field($model, 'normal_alert')->textInput() ?></div>
        <div class="col-lg-3"><?= $form->field($model, 'city_alert')->textInput() ?></div>
        <div class="col-lg-3"><?= $form->field($model, 'four_hour_alert')->textInput() ?></div>
        <div class="col-lg-3"><?= $form->field($model, 'ten_hour_alert')->textInput() ?></div>
    </div>


<!---->
<!--    --><?php //= $form->field($model, 'status')->textInput() ?>
<!---->
<!--    --><?php //= $form->field($model, 'company_id')->textInput() ?>
<!---->
<!--    --><?php //= $form->field($model, 'created_at')->textInput() ?>
<!---->
<!--    --><?php //= $form->field($model, 'created_by')->textInput() ?>
<!---->
<!--    --><?php //= $form->field($model, 'updated_at')->textInput() ?>
<!---->
<!--    --><?php //= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
