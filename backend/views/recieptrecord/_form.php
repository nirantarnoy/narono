<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Recieptrecord $model */
/** @var yii\widgets\ActiveForm $form */

$cost_title_data = \common\models\FixcostTitle::find()->where(['type_id' => 2])->all();
$model_office = null;
if($model->company_id > 0){
    $model_office = \common\models\Location::find()->where(['company_id' => $model->company_id])->all();
}
?>

    <div class="recieptrecord-form">

        <?php $form = ActiveForm::begin(); ?>

        <input type="hidden" class="remove-list2" name="remove_list2" value="">

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
                <?= $form->field($model, 'trans_ref_id')->widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Cashrecord::find()->all(), 'id', function ($data) {
                        return $data->journal_no . ' ' . $data->pay_for;
                    }),
                    'options' => [
                        'placeholder' => '--เลือก--',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'emp_id')->widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->all(), 'id', function ($data) {
                        return $data->fname . ' ' . $data->lname;
                    }),
                    'options' => [
                        'placeholder' => '--เลือก--',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]) ?>
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
                    'data'=> $model->company_id > 0 ? \yii\helpers\ArrayHelper::map($model_office, 'id', 'name'):null,
                    'options' => [
                        'class' => 'office-select2',
                        'placeholder' => '--เลือก--',
                    ]
                ]) ?>
            </div>
        </div>

        <br/>
        <h5>รายการรับ</h5>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered table-striped" id="table-list2">
                    <thead>
                    <th>รายการรับ</th>
                    <th>จำนวนเงิน</th>
                    <th>ref id</th>
                    <th>เลขที่อ้างอิง</th>
                    <th>หมายเหตุ</th>
                    <th></th>
                    </thead>
                    <tbody>
                    <?php if ($model->isNewRecord): ?>
                        <tr>
                            <td>
                                <select name="reciept_title_id[]" class="form-control reciept-title-id" id="">
                                    <option value="0">--รายการ--</option>
                                    <?php for ($i = 0; $i <= count($cost_title_data) - 1; $i++) : ?>
                                        <option value="<?= $cost_title_data[$i]['id'] ?>"><?= $cost_title_data[$i]['name'] ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="price_line[]"
                                       class="form-control price-line" id="" step="0.01">
                            </td>
                            <td>
                                <input type="text" name="ref_id[]"
                                       class="form-control ref-id" id="">
                            </td>
                            <td>
                                <input type="text" name="ref_no[]"
                                       class="form-control ref-no" id="">
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
                                        <select name="reciept_title_id[]" class="form-control reciept-title-id" id="">
                                            <option value="0">--ค่าใช้จ่าย--</option>
                                            <?php for ($i = 0; $i <= count($cost_title_data) - 1; $i++) : ?>
                                                <?php
                                                $selected = "";
                                                if ($cost_title_data[$i]['id'] == $key->receipt_title_id) {
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
                                               value="<?= $key->amount ?>" step="0.01">
                                    </td>
                                    <td>
                                        <input type="text" name="ref_id[]"
                                               class="form-control ref-id" id=""
                                               value="<?= $key->ref_id ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="ref_no[]"
                                               class="form-control ref-no" id=""
                                               value="<?= $key->ref_no ?>">
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
                                    <select name="reciept_title_id[]" class="form-control reciept-title-id" id="">
                                        <option value="0">--รายการ--</option>
                                        <?php for ($i = 0; $i <= count($cost_title_data) - 1; $i++) : ?>
                                            <option value="<?= $cost_title_data[$i]['id'] ?>"><?= $cost_title_data[$i]['name'] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="price_line[]"
                                           class="form-control price-line" id="" step="0.01">
                                </td>
                                <td>
                                    <input type="text" name="ref_id[]"
                                           class="form-control ref-id" id="">
                                </td>
                                <td>
                                    <input type="text" name="ref_no[]"
                                           class="form-control ref-no" id="">
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


        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <div class="col-lg-6" style="text-align: right;">
                <?php if (!$model->isNewRecord): ?>
                    <a href="<?= \yii\helpers\Url::to(['recieptrecord/print', 'id' => $model->id], true) ?>"
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
var removelist2 = [];

$(function(){
    // $('.start-date').datepicker({dateformat: 'dd-mm-yy'});
    // $('.expire-date').datepicker({dateFormat: 'dd-mm-yy'});
});

function addline(e){
    var tr = $("#table-list2 tbody tr:last");
                    var clone = tr.clone();
                    //clone.find(":text").val("");
                    // clone.find("td:eq(1)").text("");
                    clone.find(".reciept-title-id").val("");
                    clone.find(".price-line").val("0");
                    clone.find(".ref-id").val("");
                    clone.find(".ref-no").val("");
                    clone.find(".remark-line").val("");
                    
                  
                    clone.attr("data-var", "");
                    clone.find('.rec-id').val("");
                    
                    tr.after(clone);
     
}
    function removeline(e) {
        if (confirm("ต้องการลบรายการนี้ใช่หรือไม่?")) {
            if (e.parent().parent().attr("data-var") != '') {
                removelist2.push(e.parent().parent().attr("data-var"));
                $(".remove-list2").val(removelist2);
            }
            // alert(removelist);
            // alert(e.parent().parent().attr("data-var"));

            if ($("#table-list2 tbody tr").length == 1) {
                $("#table-list2 tbody tr").each(function () {
                    $(this).find(":text").val("");
                   // $(this).find(".line-prod-photo").attr('src', '');
                   
                     $(this).find(".price-line").val(0);
                     $(this).find(".ref-id").val('');
                     $(this).find(".ref-no").val('');
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
JS;

$this->registerJs($js, static::POS_END);

?>