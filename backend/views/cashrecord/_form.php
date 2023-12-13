<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Cashrecord $model */
/** @var yii\widgets\ActiveForm $form */

$cost_title_data = \common\models\FixcostTitle::find()->where(['type_id' => 1])->all();

?>

    <div class="cashrecord-form">

        <?php $form = ActiveForm::begin(); ?>

        <input type="hidden" class="remove-list2" name="remove_list2" value="">
        <input type="hidden" name="status" value="<?=$model->isNewRecord ? 1: $model->status?>">

        <div class="row">
            <div class="col-lg-4">
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
            <div class="col-lg-4">
                <?= $form->field($model, 'car_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Car::find()->where(['type_id' => '1'])->all(), 'id', function ($data) {
                        return $data->name;
                    }),
                    'options' => [
                        'placeholder' => '--รถ--',
                    ]
                ]) ?>
            </div>

        </div>
        <div class="row">

            <div class="col-lg-3">
                <?php //echo $form->field($model, 'car_tail_id')->textInput() ?>
                <?= $form->field($model, 'car_tail_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Car::find()->where(['type_id' => '2'])->all(), 'id', function ($data) {
                        return $data->name;
                    }),
                    'options' => [
                        'placeholder' => '--พ่วง--',
                        'onchange' => 'getTailinfo($(this))',
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
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->where(['status' => 1])->all(), 'id', 'name'),
                    'options' => [
                        'placeholder' => '--เลือก--',
                    ]
                ]) ?>
                <?php //echo $form->field($model, 'status')->widget(\toxor88\switchery\Switchery::className(), ['options' => ['label' => '', 'class' => 'form-control']])->label() ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'payment_method_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Paymentmethod::find()->all(), 'id', 'name'),
                    'options' => [
                        'placeholder' => '--เลือก--',
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">

            <div class="col-lg-3">
                <label for="">สถานะ</label>
                <input type="text" class="form-control" readonly value="<?=$model->isNewRecord?'Open':\backend\helpers\CashrecordStatus::getTypeById($model->status)?>" />
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
                                       class="form-control price-line" id="">
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
                                               value="<?= $key->amount ?>">
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
                                           class="form-control price-line" id="">
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
                    <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success']) ?>
                    <?php if ($model->status == 1): ?>
                        <a href="<?= \yii\helpers\Url::to(['cashrecord/approve', 'id' => $model->id], true) ?>"
                           class="btn btn-info">อนุมัติ</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6" style="text-align: right;">
                <a href="<?= \yii\helpers\Url::to(['cashrecord/print', 'id' => $model->id], true) ?>"
                   class="btn btn-warning">พิมพ์</a>
            </div>
        </div>


        <?php ActiveForm::end(); ?>

    </div>


<?php
$url_to_Dropoffdata = \yii\helpers\Url::to(['dropoffplace/getdropoffdata'], true);

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
                    clone.find(".cost-title-id").val("");
                    clone.find(".price-line").val("0");
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


JS;

$this->registerJs($js, static::POS_END);

?>