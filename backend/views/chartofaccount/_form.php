<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ChartOfAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="chart-of-account-form card shadow-sm">
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'account_code')->textInput(['maxlength' => true, 'placeholder' => 'เช่น 111101']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'sub_account_code')->textInput(['maxlength' => true, 'placeholder' => 'เช่น CSH001']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'ชื่อบัญชีภาษาไทย']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'name_en')->textInput(['maxlength' => true, 'placeholder' => 'Account Name (English)']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'status')->dropDownList([1 => 'Active', 0 => 'Inactive']) ?>
            </div>
        </div>

        <div class="form-group mt-3">
            <?= Html::submitButton('<i class="fas fa-save mr-1"></i> บันทึกข้อมูล', ['class' => 'btn btn-success btn-lg shadow-sm px-5']) ?>
            <?= Html::a('ยกเลิก', ['index'], ['class' => 'btn btn-outline-secondary btn-lg shadow-sm px-4 ml-2']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
