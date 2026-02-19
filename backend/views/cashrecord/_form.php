<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Cashrecord $model */
/** @var yii\widgets\ActiveForm $form */

$cost_title_data = \common\models\FixcostTitle::find()->where(['type_id' => 1])->all();
$model_office = null;
if($model->company_id > 0){
    $model_office = \common\models\Location::find()->where(['company_id' => $model->company_id])->all();
}

?>

    <div class="cashrecord-form">

        <?php $form = ActiveForm::begin(); ?>

        <input type="hidden" class="remove-list" name="remove_list" value="">
        <input type="hidden" class="remove-account-list" name="remove_account_list" value="">
        <input type="hidden" name="status" value="<?= $model->isNewRecord ? 1 : $model->status ?>">

        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'journal_no')->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?>
            </div>
            <div class="col-lg-3">
                <?php $model->trans_date = $model->isNewRecord ? date('d-m-Y') : date('d-m-Y', strtotime($model->trans_date)) ?>
                <?= $form->field($model, 'trans_date')->widget(\kartik\date\DatePicker::className(), [
                    'value' => date('d/m/Y'),
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true,
                        'todayBtn' => true,
                    ]
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'pay_for_type_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\helpers\PayForType::asArrayObject(), 'id', 'name'),
                    'options' => [
                        'id' => 'pay-for-type-id',
                        'placeholder' => '--ประเภทผู้รับเงิน--',
                        'onchange' => 'checkpaytype($(this))',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]) ?>
            </div>

            <div class="col-lg-3">
                <?= $form->field($model, 'pay_for')->textInput(['maxlength' => true, 'class' => 'form-control pay-for-name']) ?>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'car_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Car::find()->where(['type_id' => '1'])->all(), 'id', function ($data) {
                        return $data->name . ' ' . \backend\models\Car::findDrivername($data->id);
                    }),

                    'options' => [
                        'placeholder' => '--รถ--',
                        'id' => 'car-id',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]) ?>
            </div>

            <div class="col-lg-3">
                <?php //echo $form->field($model, 'car_tail_id')->textInput() ?>
                <?= $form->field($model, 'car_tail_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Car::find()->where(['type_id' => '2'])->all(), 'id', function ($data) {
                        return $data->name;
                    }),

                    'options' => [
                        'placeholder' => '--พ่วง--',
                        'onchange' => 'getTailinfo($(this))',
                        'id' => 'car-tail-id',
                    ]
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?php //echo $form->field($model, 'car_tail_id')->textInput() ?>
                <?= $form->field($model, 'trans_ref_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Workqueue::find()->where(['status' => 1])->all(), 'id', function ($data) {
                        return $data->work_queue_no;
                    }),
                    'options' => [
                        'placeholder' => '--ใบงาน--',
                    ]
                ]) ?>
            </div>

            <div class="col-lg-3">
                <?= $form->field($model, 'cashier_by')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->where(['status' => 1, 'is_cashier' => 1])->all(), 'id', function ($data) {
                        return $data->fname . ' ' . $data->lname;
                    }),
                    'options' => [
                        'placeholder' => '--เลือก--',
                    ]
                ]) ?>
                <?php //echo $form->field($model, 'status')->widget(\toxor88\switchery\Switchery::className(), ['options' => ['label' => '', 'class' => 'form-control']])->label() ?>
            </div>

        </div>
        <div class="row">

            <div class="col-lg-3">
                <?= $form->field($model, 'company_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Company::find()->all(), 'id', 'name'),
                    'options' => [
                        'placeholder' => '--เลือก--',
                        'onchange' => 'getOffice($(this))',
                    ]
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'office_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => $model->company_id > 0 ? \yii\helpers\ArrayHelper::map($model_office, 'id', 'name'):null,
                    'options' => [
                        'class' => 'office-select2',
                        'placeholder' => '--เลือก--',
                        'selected' => $model->office_id,

                    ]
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'payment_method_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Paymentmethod::find()->all(), 'id', 'name'),
                    'options' => [
                        'placeholder' => '--เลือก--',
                    ]
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'bank_account')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'check_no')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <label for="">สถานะ</label>
                <input type="text" class="form-control" readonly
                       value="<?= $model->isNewRecord ? 'Open' : \backend\helpers\CashrecordStatus::getTypeById($model->status) ?>"/>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'ref_no')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'vat_per')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\helpers\VatperType::asArrayObject(), 'id', 'name'),
                    'options' => [
                        'id' => 'vat-per-amount',
                        'placeholder' => '--หัก ณ ที่จ่าย--',
                        'onchange'=>'calallvat($(this))'
                    ]
                ]) ?>
            </div>
        </div>

        <br/>
        <h5>รายการค่าใช้จ่าย</h5>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered table-striped" id="table-list2">
                    <thead>
                    <th>รายการค่าใช้จ่าย</th>
                    <th>จำนวนเงิน</th>
                    <th>หัก ณ ที่จ่าย</th>
                    <th>หมายเหตุ</th>
                    <th></th>
                    </thead>
                    <tbody>
                    <?php if ($model->isNewRecord): ?>
                        <tr>
                            <td>
                                <select name="cost_title_id[]" class="form-control cost-title-id" id="">
                                    <option value="0">--ค่าใช้จ่าย--</option>
                                    <?php for ($i = 0; $i <= count($cost_title_data) - 1; $i++) : ?>
                                        <option value="<?= $cost_title_data[$i]['id'] ?>"><?= $cost_title_data[$i]['name'] ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="price_line[]"
                                       step="0.01"
                                       class="form-control price-line" id="" onchange="callinevat($(this))">
                            </td>
                            <td>
                                <input type="text" name="vat_per_line[]"
                                       class="form-control vat-per-line" id="" readonly>
                            </td>
                            <td>
                                <input type="text" name="remark_line[]"
                                       class="form-control remark-line" id="">
                            </td>

                            <td>
                                <div class="btn btn-danger btn-sm" onclick="removeline($(this))"><i
                                            class="fa fa-trash"></i></div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php if (count($model_line)): ?>
                            <?php foreach ($model_line as $key): ?>
                                <tr data-var="<?= $key->id ?>">
                                    <td>
                                        <input type="hidden" class="rec-id" name="rec_id[]" value="<?= $key->id ?>">

                                        <select name="cost_title_id[]" class="form-control cost-title-id" id="">
                                            <option value="0">--ค่าใช้จ่าย--</option>
                                            <?php for ($i = 0; $i <= count($cost_title_data) - 1; $i++) : ?>
                                                <?php
                                                $selected = "";
                                                if ($cost_title_data[$i]['id'] == $key->cost_title_id) {
                                                    $selected = 'selected';
                                                }
                                                ?>
                                                <option value="<?= $cost_title_data[$i]['id'] ?>" <?= $selected ?>><?= $cost_title_data[$i]['name'] ?></option>
                                            <?php endfor; ?>
                                        </select>

                                    </td>
                                    <td>
                                        <input type="number" name="price_line[]"
                                               class="form-control price-line" id=""
                                               step="0.01"
                                               value="<?= $key->amount ?>" onchange="callinevat($(this))">
                                    </td>
                                    <td>
                                        <input type="text" name="vat_per_line[]"
                                               class="form-control vat-per-line" value="<?= $key->vat_amount ?>" id="" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="remark_line[]"
                                               class="form-control remark-line" id=""
                                               value="<?= $key->remark ?>">
                                    </td>
                                    <td>
                                        <div class="btn btn-danger btn-sm" onclick="removeline($(this))"><i
                                                    class="fa fa-trash"></i></div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td>
                                    <select name="cost_title_id[]" class="form-control cost-title-id" id="">
                                        <option value="0">--ค่าใช้จ่าย--</option>
                                        <?php for ($i = 0; $i <= count($cost_title_data) - 1; $i++) : ?>
                                            <option value="<?= $cost_title_data[$i]['id'] ?>"><?= $cost_title_data[$i]['name'] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="price_line[]"
                                           step="0.01"
                                           class="form-control price-line" id="" onchange="callinevat($(this))">
                                </td>
                                <td>
                                    <input type="text" name="vat_per_line[]"
                                           class="form-control vat-per-line" id="" readonly>
                                </td>
                                <td>
                                    <input type="text" name="remark_line[]"
                                           class="form-control remark-line" id="">
                                </td>
                                <td>
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
        <br />
        <h5>สำหรับฝ่ายบัญชี</h5>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered table-striped" id="table-list-account">
                    <thead>
                    <tr>
                        <th>ชื่อบัญชี</th>
                        <th>รหัสบัญชี</th>
                        <th style="text-align: right">เดบิต</th>
                        <th style="text-align: right">เครดิต</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($model->isNewRecord): ?>
                        <tr>
                            <td>
                                <input type="text" name="account_name_line[]" class="form-control account-name-line">
                            </td>
                            <td>
                                <input type="text" name="account_code_line[]" class="form-control account-code-line">
                            </td>
                            <td>
                                <input type="number" name="debit_line[]" class="form-control debit-line" step="0.01" style="text-align: right">
                            </td>
                            <td>
                                <input type="number" name="credit_line[]" class="form-control credit-line" step="0.01" style="text-align: right">
                            </td>
                            <td>
                                <div class="btn btn-danger btn-sm" onclick="removeaccountline($(this))"><i class="fa fa-trash"></i></div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $model_account = \common\models\CashRecordAccount::find()->where(['cash_record_id' => $model->id])->all(); ?>
                        <?php if (count($model_account)): ?>
                            <?php foreach ($model_account as $acc): ?>
                                <tr data-var="<?= $acc->id ?>">
                                    <td>
                                        <input type="hidden" name="acc_rec_id[]" value="<?= $acc->id ?>">
                                        <input type="text" name="account_name_line[]" class="form-control account-name-line" value="<?= $acc->account_name ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="account_code_line[]" class="form-control account-code-line" value="<?= $acc->account_code ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="debit_line[]" class="form-control debit-line" step="0.01" style="text-align: right" value="<?= $acc->debit ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="credit_line[]" class="form-control credit-line" step="0.01" style="text-align: right" value="<?= $acc->credit ?>">
                                    </td>
                                    <td>
                                        <div class="btn btn-danger btn-sm" onclick="removeaccountline($(this))"><i class="fa fa-trash"></i></div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td>
                                    <input type="text" name="account_name_line[]" class="form-control account-name-line">
                                </td>
                                <td>
                                    <input type="text" name="account_code_line[]" class="form-control account-code-line">
                                </td>
                                <td>
                                    <input type="number" name="debit_line[]" class="form-control debit-line" step="0.01" style="text-align: right">
                                </td>
                                <td>
                                    <input type="number" name="credit_line[]" class="form-control credit-line" step="0.01" style="text-align: right">
                                </td>
                                <td>
                                    <div class="btn btn-danger btn-sm" onclick="removeaccountline($(this))"><i class="fa fa-trash"></i></div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5">
                            <div class="btn btn-primary" onclick="addaccountline($(this))">
                                <i class="fa fa-plus-circle"></i> เพิ่มหัวข้อบัญชี
                            </div>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success']) ?>
                    <?php if ($model->status == 1): ?>
                        <a href="<?= \yii\helpers\Url::to(['cashrecord/approve', 'id' => $model->id], true) ?>"
                           class="btn btn-info">อนุมัติ</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6" style="text-align: right;">
                <?php if (!$model->isNewRecord): ?>
                    <a href="<?= \yii\helpers\Url::to(['cashrecord/print', 'id' => $model->id], true) ?>"
                       class="btn btn-warning">พิมพ์</a>
                <?php endif; ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


<?php
$url_to_Dropoffdata = \yii\helpers\Url::to(['dropoffplace/getdropoffdata'], true);
$url_to_get_office = \yii\helpers\Url::to(['recieptrecord/getoffice'], true);
$js = <<<JS
var removelist = [];
var removelist_account = [];

$(function(){
    // $('.start-date').datepicker({dateformat: 'dd-mm-yy'});
    // $('.expire-date').datepicker({dateFormat: 'dd-mm-yy'});
    
    if($("#pay-for-type-id").val() <=0){
          $("#pay-for-type-id").val(1).change();
         // $(".pay-for-name").prop("disabled","disabled");
         // $("#car-id").prop("disabled", false);
         // $("#car-tail-id").prop("disabled", false);
    }else{
        //$(".pay-for-name").prop("disabled","");
        //$("#car-id").prop("disabled", true);
        //$("#car-tail-id").prop("disabled", true);
    }
});

function addline(e){
    var tr = $("#table-list2 tbody tr:last");
                    var clone = tr.clone();
                    //clone.find(":text").val("");
                    // clone.find("td:eq(1)").text("");
                    clone.find(".cost-title-id").val("");
                    clone.find(".price-line").val("0");
                    clone.find(".vat-per-line").val("0");
                    clone.find(".remark-line").val("");
                    
                    clone.attr("data-var", "");
                    clone.find('.rec-id').val("");
                    
                    tr.after(clone);
     
}
    function removeline(e) {
        if (confirm("ต้องการลบรายการนี้ใช่หรือไม่?")) {
            if (e.parent().parent().attr("data-var") != '' && e.parent().parent().attr("data-var") != undefined) {
                removelist.push(e.parent().parent().attr("data-var"));
                $(".remove-list").val(removelist);
            }
            // alert(removelist);
            // alert(e.parent().parent().attr("data-var"));

            if ($("#table-list2 tbody tr").length == 1) {
                $("#table-list2 tbody tr").each(function () {
                    $(this).find(":text").val("");
                   // $(this).find(".line-prod-photo").attr('src', '');
                   
                     $(this).find(".price-line").val(0);
                    $(this).find(".remark-line").val('');
                    // cal_num();
                });
            } else {
                e.parent().parent().remove();
            }
            // cal_linenum();
            // cal_all();
        }
    }
function checkpaytype(e){
    // var id = e.val();
    // // alert();
    // if(id != 1){
    //     $(".pay-for-name").prop("disabled","");
    //    // $("#car-id").prop("disabled", true);
    //     $("#car-tail-id").prop("disabled", "");
    //     $("#car-id").val(-1).change();
    //     $("#car-tail-id").val(-1).change();
    // }else{
    //     $(".pay-for-name").prop("disabled","disabled");
    //     $("#car-id").prop("disabled", false);
    //     $("#car-tail-id").prop("disabled", false);
    // }
}

function callinevat(e){
    var line_amount = e.val();
    var vat_per = $("#vat-per-amount").val() || 0;
    var vat_amount = (parseFloat(line_amount) || 0) * (parseFloat(vat_per) || 0) / 100;
    
    e.closest("tr").find(".vat-per-line").val(parseFloat(vat_amount).toFixed(2));
}
function calallvat(e){
    var vat_per = e.val() || 0;
 
    $("#table-list2 tbody tr").each(function (){
      var line_amt = $(this).closest("tr").find(".price-line").val() || 0;
      var line_vat_amt = (parseFloat(line_amt) || 0) * (parseFloat(vat_per) || 0) / 100;
      $(this).closest("tr").find(".vat-per-line").val(parseFloat(line_vat_amt).toFixed(2));
    });
   
}
function getOffice(e){
    var office_id = e.val();
    $.ajax({
        url: "$url_to_get_office",
        type: "POST",
        dataType: "html",
        data: {
            'company_id': office_id
        },
        success: function (data) {
            $(".office-select2").html(data);
        }
        
    })
}

function addaccountline(e){
    var tr = $("#table-list-account tbody tr:last");
    var clone = tr.clone();
    clone.find(":text").val("");
    clone.find(':input[type="number"]').val("");
    clone.attr("data-var", "");
    clone.find('input[name="acc_rec_id[]"]').remove();
    tr.after(clone);
}

function removeaccountline(e) {
    if (confirm("ต้องการลบรายการนี้ใช่หรือไม่?")) {
        if (e.parent().parent().attr("data-var") != '' && e.parent().parent().attr("data-var") != undefined) {
             removelist_account.push(e.parent().parent().attr("data-var"));
             $(".remove-account-list").val(removelist_account);
        }
        if ($("#table-list-account tbody tr").length == 1) {
            $("#table-list-account tbody tr").each(function () {
                $(this).find(":text").val("");
                $(this).find(':input[type="number"]').val("");
            });
        } else {
            e.parent().parent().remove();
        }
    }
}
JS;

$this->registerJs($js, static::POS_END);

?>