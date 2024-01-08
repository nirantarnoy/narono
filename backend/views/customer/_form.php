<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Customer $model */
/** @var yii\widgets\ActiveForm $form */

$address_chk = \backend\models\AddressInfo::find()->where(['party_id' => $model->id])->one();

$district_data = \backend\models\District::find()->all();
$city_data = \backend\models\Amphur::find()->all();
$province_data = \backend\models\Province::find()->all();
$district_chk = \backend\models\AddressInfo::findDistrictId($model->id);
$city_chk = \backend\models\AddressInfo::findAmphurId($model->id);
$province_chk = \backend\models\AddressInfo::findProvinceId($model->id);

$partycat_data = \backend\helpers\PartycatType::asArrayObject();
//$partycat_chk1 = \backend\models\AddressInfo::find()->where(['party_id' => $model->id])->one();

$contactcat_data = \backend\helpers\ContactcatType::asArrayObject();

$x_address = $address_chk == null ? '' : $address_chk->address;
$x_street = $address_chk == null ? '' : $address_chk->street;
$x_zipcode = $address_chk == null ? '' : $address_chk->zipcode;

$group_assign_list = [];
if($model_user_group_list!=null){
    foreach ($model_user_group_list as $value){
        array_push($group_assign_list,$value->group_id);
    }
}

//print_r($address_chk) ; return;
?>

<div class="customer-form">

    <?php $form = ActiveForm::begin(); ?>
    <input type="hidden" class="remove-list" name="remove_list" value="">

    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'business_type')->textInput() ?>
        </div>
        <div class="col-lg-3">
            <?php $model->customer_group_id = $group_assign_list;?>
            <?= $form->field($model, 'customer_group_id')->Widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Customergroup::find()->all(), 'id', function ($data) {
                    return $data->name;
                }),
                'options' => [
                    'placeholder' => '--กลุ่มลูกค้า--'
                ],
                'pluginOptions' => [
                    'multiple' => true,
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($model, 'work_type_id')->Widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\WorkOptionType::find()->all(), 'id', function ($data) {
                    return $data->name;
                }),
                'options' => [
                    'placeholder' => '--ประเภทงาน--'
                ]
            ]) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'company_id')->Widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Company::find()->all(), 'id', function ($data) {
                    return $data->name;
                }),
                'options' => [
                    'placeholder' => '--company--'
                ]
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <label for="">ที่อยู่</label>
            <input type="text" class="form-control cus-address" id="cus-address"
                   value="<?= $model->isNewRecord ? '' : $x_address ?>" name="cus_address">
        </div>
        <div class="col-lg-4">
            <label for="">ถนน</label>
            <input type="text" class="form-control cus-street" id="cus-street"
                   value="<?= $model->isNewRecord ? '' : $x_street ?>" name="cus_street">
        </div>
    </div>
    <br/>

    <div class="row">
        <div class="col-lg-3">
            <label for="">จังหวัด</label>
            <select name="province_id" class="form-control province-id" id=""
                    onchange="getCity($(this))">
                <option value="0">--จังหวัด--</option>
                <?php foreach ($province_data as $val3): ?>
                    <?php
                    $selected = '';
                    if ($val3->PROVINCE_ID == $province_chk)
                        $selected = 'selected';
//                    ?>
                    <option value="<?= $val3->PROVINCE_ID ?>" <?= $selected ?>><?= $val3->PROVINCE_NAME ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-lg-3">
            <label for="">อำเภอ/เขต</label>
            <select name="city_id" class="form-control city-id" id="city"
                    onchange="getDistrict($(this))">
                <option value="0">--อำเภอ/เขต--</option>
                <?php foreach ($city_data as $val2): ?>
                    <?php
                    $selected = '';
                    if ($val2->AMPHUR_ID == $city_chk)
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
                    if ($val->DISTRICT_ID == $district_chk)
                        $selected = 'selected';
//                    ?>
                    <option value="<?= $val->DISTRICT_ID ?>" <?= $selected ?>><?= $val->DISTRICT_NAME ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-lg-3">
            <label for="">รหัสไปรษณีย์</label>
            <input type="text" class="form-control zipcode" id="zipcode"
                   value="<?= $model->isNewRecord ? '' : $x_zipcode ?>" name="zipcode" readonly>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-3">

            <?= $form->field($model, 'payment_term_id')->Widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Paymentterm::find()->all(), 'id', function ($data) {
                    return $data->name;
                }),
                'options' => [
                    'placeholder' => '--เลือกเงื่อนไขชำระเงิน--'
                ]
            ]) ?>

        </div>
        <div class="col-lg-3">

            <?= $form->field($model, 'payment_method_id')->Widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Paymentmethod::find()->all(), 'id', function ($data) {
                    return $data->name;
                }),
                'options' => [
                    'placeholder' => '--เลือกวิธีชำระเงิน--'
                ]
            ]) ?>

        </div>
        <div class="col-lg-3"></div>
        <div class="col-lg-3"></div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($model, 'taxid')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'address')->textarea(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'branch_code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'branch_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <!--    <? //= $form->field($model, 'status')->textInput() ?>-->
    <?php echo $form->field($model, 'status')->widget(\toxor88\switchery\Switchery::className(), ['options' => ['label' => '', 'class' => 'form-control']])->label() ?>

    <h4>รายชื่อผู้ติดต่อ</h4>
    <br/>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-striped" id="table-list">
                <thead>
                <tr>
                    <th>ชื่อผู้ติดต่อ</th>
                    <th>ช่องทางการติดต่อ</th>
                    <th>ข้อมูล</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php if ($model->isNewRecord): ?>
                    <tr data-var="">
                        <td>
                            <input type="text" class="form-control line-name" name="line_name[]" value="">
                        </td>
                        <td>
                            <select name="line_type_id[]" class="form-control line-type-id" id=""
                                    onchange="">
                                <option value="0">--ช่องทาง--</option>
                                <?php for ($i = 0; $i <= count($contactcat_data) - 1; $i++) : ?>
                                    <option value="<?= $contactcat_data[$i]['id'] ?>"><?= $contactcat_data[$i]['name'] ?></option>
                                <?php endfor; ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control line-contact-no" name="line_contact_no[]" value="">
                        </td>
                        <td style="text-align: center">
                            <div class="btn btn-danger btn-sm" onclick="removeline($(this))"><i
                                        class="fa fa-trash"></i></div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php if (count($model_contact_line) > 0) : ?>
                        <?php foreach ($model_contact_line as $value): ?>
                            <tr data-var="<?= $value->id ?>">
                                <td>
                                    <input type="hidden" class="rec-id" name="rec_id[]"
                                           value="<?= $value->id ?>">
                                    <input type="text" class="form-control line-name" name="line_name[]"
                                           value=" <?= trim($value->contact_name) ?>">
                                </td>
                                <td>
                                    <select name="line_type_id[]" class="form-control line-type-id" id=""
                                            onchange="">
                                        <option value="0">--ช่องทาง--</option>
                                        <?php for ($i = 0; $i <= count($contactcat_data) - 1; $i++) : ?>
                                            <?php
                                            $selected = '';
                                            if ($contactcat_data[$i]['id'] == $value->type_id) {
                                                $selected = 'selected';
                                            }
                                            ?>
                                            <option value="<?= $contactcat_data[$i]['id'] ?>" <?= $selected ?>><?= $contactcat_data[$i]['name'] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control line-contact-no" name="line_contact_no[]"
                                           value=" <?= trim($value->contact_no) ?>">
                                </td>
                                <td style="text-align: center">
                                    <div class="btn btn-danger btn-sm" onclick="removeline($(this))"><i
                                                class="fa fa-trash"></i></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr data-var="">
                            <td>
                                <input type="text" class="form-control line-name" name="line_name[]" value="">
                            </td>
                            <td>
                                <select name="line_type_id[]" class="form-control line-type-id" id=""
                                        onchange="">
                                    <option value="0">--ช่องทาง--</option>
                                    <?php for ($i = 0; $i <= count($contactcat_data) - 1; $i++) : ?>
                                        <option value="<?= $contactcat_data[$i]['id'] ?>"><?= $contactcat_data[$i]['name'] ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control line-contact-no" name="line_contact_no[]"
                                       value="">
                            </td>
                            <td style="text-align: center">
                                <div class="btn btn-danger btn-sm" onclick="removeline($(this))"><i
                                            class="fa fa-trash"></i></div>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endif; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="4">
                        <div class="btn btn-primary"
                             onclick="addline($(this))">
                            <i class="fa fa-plus-circle"></i>
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

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

