<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Cityzone $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cityzone-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <?php echo $form->field($model, 'province_id')->widget(Select2::className(), [
                'data' => ArrayHelper::map(\common\models\Province::find()->all(), 'PROVINCE_ID', 'PROVINCE_NAME'),
                'options' => [
                    'placeholder' => '--เลือกจังหวัด--',
                    'onchange' => 'getCity($(this))'
                ]
            ]) ?>
        </div>
        <div class="col-lg-8">

            <?= $form->field($model, 'city_id')->widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\common\models\Amphur::find()->all(), 'AMPHUR_ID', 'AMPHUR_NAME'),
                'theme' => Select2::THEME_BOOTSTRAP,
                //     'theme' => Select2::THEME_DEFAULT,
                //         'theme' => Select2::THEME_BOOTSTRAP,
                //     'theme' => Select2::THEME_CLASSIC,

                'options' => [
                    'placeholder' => 'Select ...',
                    'multiple' => true
                ],
                'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => [',', ' '],
                    'maximumInputLength' => 100
                ],
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$url_to_getcity = \yii\helpers\Url::to(['customer/showcity'], true);
$js = <<<JS
$(function(){
     $("select#cityzone-city_id").prop("disabled","disabled");
   $(".btn-pull-price").on("click",function(){
       getapiprice();
   }) ;
});

function getCity(e){
    $.post("$url_to_getcity"+"&id="+e.val(),function(data){
       // alert(data);
        $("select#cityzone-city_id").html(data);
        $("select#cityzone-city_id").prop("disabled","");
    });
}

function addCommas(nStr) {
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
 }
JS;
$this->registerJs($js, static::POS_END);
?>