<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\FixcostTitle $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="fixcost-title-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_id')->widget(\kartik\select2\Select2::className(),[
            'data'=>\yii\helpers\ArrayHelper::map(\backend\helpers\FixcostType::asArrayObject(),'id','name'),
            'pluginOptions' => [
                  'allowClear'=> true,
            ]
    ]) ?>

    <?= $form->field($model, 'account_id')->widget(\kartik\select2\Select2::className(),[
            'data'=>\yii\helpers\ArrayHelper::map(\common\models\ChartOfAccount::find()->all(),'id', function($data){
                return '['.$data->account_code.'] '.$data->name;
            }),
            'options' => ['placeholder' => 'เลือกผังบัญชี...'],
            'pluginOptions' => [
                  'allowClear'=> true,
            ]
    ]) ?>

    <?php echo $form->field($model, 'status')->widget(\toxor88\switchery\Switchery::className(), ['options' => ['label' => '', 'class' => 'form-control']])->label() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
