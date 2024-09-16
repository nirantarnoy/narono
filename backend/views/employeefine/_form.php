<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

$district_data = \backend\models\District::find()->all();
$city_data = \backend\models\Amphur::find()->all();
$province_data = \backend\models\Province::find()->all();


/** @var yii\web\View $this */
/** @var backend\models\Employeefine $model */
/** @var yii\widgets\ActiveForm $form */
?>

    <div class="employeefine-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'case_no')->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?>
            </div>
            <div class="col-lg-3">
                <?php $model->trans_date = $model->isNewRecord ? date('Y-m-d') : date('Y-m-d', strtotime($model->trans_date)); ?>
                <?= $form->field($model, 'trans_date')->widget(kartik\datetime\DateTimePicker::className(), [
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
                <?php $model->fine_date = $model->isNewRecord ? date('Y-m-d') : date('Y-m-d', strtotime($model->fine_date)); ?>
                <?= $form->field($model, 'fine_date')->widget(kartik\datetime\DateTimePicker::className(), [
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
                <?= $form->field($model, 'company_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Company::find()->all(), 'id', function ($data) {
                        return $data->name;
                    }),
                    'options' => [
                        'placeholder' => '--เลือก--'
                    ]
                ]) ?>
            </div>
        </div>
        <br/>
        <div class="row">

            <div class="col-lg-4">
                <?= $form->field($model, 'car_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Car::find()->where(['type_id' => '1'])->all(), 'id', function ($data) {
                        return $data->name;
                    }),
                    'options' => [
                        'placeholder' => '--เลือก--',
                        'onchange' => 'getemployee($(this).val())',
                    ]
                ]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'emp_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->where(['status' => '1'])->all(), 'id', function ($data) {
                        return $data->fname . ' ' . $data->lname;
                    }),
                    'options' => [
                        'id' => 'emp-id',
                        'placeholder' => '--เลือก--',
                        'onchange' => '',
                    ]
                ]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'customer_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Customer::find()->all(), 'id', function ($data) {
                        return $data->name;
                    }),
                    'options' => [
                        'placeholder' => '--เลือก--'
                    ]
                ]) ?>
            </div>

        </div>
        <br/>
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'cause_description')->textarea(['maxlength' => true]) ?>
            </div>

            <div class="col-lg-4">
                <?= $form->field($model, 'fine_amount')->textinput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'place')->textInput(['maxlength' => true]) ?>
            </div>

        </div>

        <br/>
        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'street')->textinput(['maxlength' => true]) ?>
            </div>

            <div class="col-lg-3">
                <?= $form->field($model, 'zone')->textinput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'kilometer')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-3">
                <label for="">จังหวัด</label>
                <select name="province_id" class="form-control province-id" id=""
                        onchange="getCity($(this))">
                    <option value="0">--จังหวัด--</option>
                    <?php foreach ($province_data as $val3): ?>
                        <?php
                        $selected = '';
                        if ($val3->PROVINCE_ID == $model->province_id)
                            $selected = 'selected';
//                    ?>
                        <option value="<?= $val3->PROVINCE_ID ?>" <?= $selected ?>><?= $val3->PROVINCE_NAME ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>

        <br/>
        <div class="row">
            <div class="col-lg-3">
                <label for="">อำเภอ/เขต</label>
                <select name="city_id" class="form-control city-id" id="city"
                        onchange="getDistrict($(this))">
                    <option value="0">--อำเภอ/เขต--</option>
                    <?php foreach ($city_data as $val2): ?>
                        <?php
                        $selected = '';
                        if ($val2->AMPHUR_ID == $model->city_id)
                            $selected = 'selected';
//                    ?>
                        <option value="<?= $val2->AMPHUR_ID ?>" <?= $selected ?>><?= $val2->AMPHUR_NAME ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-lg-3">
                <label for="">ตำบล/แขวง</label>
                <select name="district_id" class="form-control district-id" id="district"
                        onchange="">
                    <option value="0">--ตำบล/แขวง--</option>
                    <?php foreach ($district_data as $val): ?>
                        <?php
                        $selected = '';
                        if ($val->DISTRICT_ID == $model->district_id)
                            $selected = 'selected';
//                    ?>
                        <option value="<?= $val->DISTRICT_ID ?>" <?= $selected ?>><?= $val->DISTRICT_NAME ?></option>
                    <?php endforeach; ?>
                </select>
            </div>


        </div>
        <br />

        <!---->
        <!--    --><?php //= $form->field($model, 'status')->textInput() ?>
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

<?php
$url_to_getcity = \yii\helpers\Url::to(['customer/showcity'], true);
$url_to_getdistrict = \yii\helpers\Url::to(['customer/showdistrict'], true);
$url_to_getzipcode = \yii\helpers\Url::to(['customer/showzipcode'], true);
$url_to_getAddress = \yii\helpers\Url::to(['customer/showaddress'], true);

$js = <<<JS
var removelist = [];

$(function(){
    
});
function getemployee(id){
    $.ajax({
        url:'index.php?r=employeefine/getemployee',
        type:'POST',
        dataType:'html',
        data:{id:id},
        success:function(data){
           if(data != null){
             //  alert(data);
               $("#emp-id").html(data);
           }
        }
    });
}
    
function addline(e){
    var tr = $("#table-list tbody tr:last");
                    var clone = tr.clone();
                    //clone.find(":text").val("");
                    // clone.find("td:eq(1)").text("");
                    clone.find(".line-name").val("");
                    clone.find(".line-type-id").val("");
                    clone.find(".line-contact-no").val("");
                  
                    clone.attr("data-var", "");
                    clone.find('.rec-id').val("");
                    
                    tr.after(clone);
     
}
function removeline(e) {
        if (confirm("ต้องการลบรายการนี้ใช่หรือไม่?")) {
            if (e.parent().parent().attr("data-var") != '') {
                removelist.push(e.parent().parent().attr("data-var"));
                $(".remove-list").val(removelist);
            }
            // alert(removelist);
            // alert(e.parent().parent().attr("data-var"));

            if ($("#table-list tbody tr").length == 1) {
                $("#table-list tbody tr").each(function () {
                    $(this).find(":text").val("");
                   // $(this).find(".line-prod-photo").attr('src', '');
                    $(this).find(".line-price").val(0);
                    // cal_num();
                });
            } else {
                e.parent().parent().remove();
            }
            // cal_linenum();
            // cal_all();
        }
    }
function getCity(e){
    $.post("$url_to_getcity"+"&id="+e.val(),function(data){
        $("select#city").html(data);
        $("select#city").prop("disabled","");
    });
}

function getDistrict(e){
    $.post("$url_to_getdistrict"+"&id="+e.val(),function(data){
                                          $("select#district").html(data);
                                          $("select#district").prop("disabled","");

                                        });
                                           $.post("$url_to_getzipcode"+"&id="+e.val(),function(data){
                                                $("#zipcode").val(data);
                                              });
}

function getAddres(e){
    $.post("$url_to_getAddress"+"&id="+e.val(),function(data){
        $("#city").html(data);
        $("select#city").prop("disabled","");
    });
}

JS;
$this->registerJs($js, static::POS_END);
?>


