<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\WorkqueueSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="workqueue-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($model, 'globalSearch')->textInput(['placeholder' => 'ค้นหา...', 'class' => 'form-control', 'aria-describedby' => 'basic-addon1'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($model, 'work_queue_date')->widget(\kartik\date\DatePicker::className(), [
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'autoclose' => true,
                ],
                'options' => ['placeholder' => 'วันที่', 'class' => 'form-control']
            ])->label('วันที่') ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'emp_assign')->widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->all(), 'id', function ($data) {
                    return $data->fname . ' ' . $data->lname;
                }),
                'options' => ['placeholder' => 'พนักงานขับรถ'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('พนักงานขับรถ') ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'car_type_id')->widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\CarType::find()->all(), 'id', 'name'),
                'options' => ['placeholder' => 'ประเภทรถ'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('ประเภทรถ') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($model, 'car_id')->widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Car::find()->where(['type_id' => '1'])->all(), 'id', 'name'),
                'options' => ['placeholder' => 'ทะเบียนรถ'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('ทะเบียนรถ') ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'company_id')->widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Company::find()->all(), 'id', 'name'),
                'options' => ['placeholder' => 'บริษัท'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('บริษัท') ?>
        </div>
        <div class="col-lg-3" style="padding-top: 32px;">
            <?= Html::submitButton('ค้นหา', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('ล้าง', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
