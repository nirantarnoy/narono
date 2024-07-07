<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Workorder $model */
/** @var yii\widgets\ActiveForm $form */
?>

    <div class="workorder-form">

        <?php $form = ActiveForm::begin(); ?>
        <input type="hidden" name="removelist" class="remove-list" value="">
        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'workorder_no')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-3">
                <?php $model->trans_date = $model->isNewRecord ? date('Y-m-d') : date('d-m-Y', strtotime($model->trans_date)) ?>
                <?= $form->field($model, 'trans_date')->widget(\kartik\date\DatePicker::className(), [
                    'value' => date('d-m-Y'),
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true,
                        'todayBtn' => true,
                        'autoclose' => true
                    ]
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'emp_inform_id')->widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->all(), 'id', function ($data) {
                        return $data->fname . ' ' . $data->lname;
                    }),
                    'options' => [
                        'placeholder'=>'--เลือกรายการ--'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'car_id')->widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Car::find()->all(), 'id', function ($data) {
                        return $data->name;
                    }),
                    'options' => [

                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <label for="">ประเภทรถ</label>
                <input type="text" class="form-control work-car-type-id" name="" value="" readonly>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'mile_data')->textInput() ?>
            </div>
            <div class="col-lg-3"></div>
            <div class="col-lg-3"></div>
        </div>
        <br/>
        <h5><b>รายการที่แจ้งซ่อม</b></h5>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered" id="table-list">
                    <thead>
                    <tr>
                        <th style="text-align: center;width: 5%">#</th>
                        <th>รายละเอียด</th>
                        <th style="width: 8%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($model->isNewRecord): ?>
                        <tr>
                            <td style="text-align: center;">1</td>
                            <td>
                                <input type="text" class="form-control line-text" name="line_text[]" value="">
                            </td>
                            <td style="text-align: center;">
                                <div class="btn btn-danger" onclick="removeline($(this))"><i class="fa fa-trash"></i>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                    <?php $lineno = 0;?>
                        <?php if ($model_line != null): ?>
                        <?php foreach ($model_line as $value):?>
                                <?php $lineno +=1;?>
                                <tr data-var="<?=$value->id?>">
                                    <td style="text-align: center;"><?=$lineno?></td>
                                    <td>
                                        <input type="text" class="form-control line-text" name="line_text[]" value="<?=$value->description?>">
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="btn btn-danger" onclick="removeline($(this))"><i
                                                    class="fa fa-trash"></i>
                                        </div>
                                    </td>
                                </tr>
                        <?php endforeach;?>
                        <?php else: ?>
                            <tr>
                                <td style="text-align: center;">1</td>
                                <td>
                                    <input type="text" class="form-control line-text" name="line_text[]" value="">
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn btn-danger" onclick="removeline($(this))"><i
                                                class="fa fa-trash"></i>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>

                    </tbody>
                    <tfoot>
                    <tr>
                        <td>
                            <div class="btn btn-primary" onclick="addline($(this))"><i class="fa fa-plus"></i></div>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <br/>

        <div class="row">
            <div class="col-lg-3">
                <label for="">ผู้อนุมัติ</label>
                <input type="text" class="form-control emp-approve" readonly>
            </div>
            <div class="col-lg-3">
                <label for="">ผู้รับแจ้งซ่อม</label>
                <input type="text" class="form-control emp-notify" readonly>
            </div>
            <div class="col-lg-3">
                <label for="">สถานะใบแจ้งซ่อม</label>
                <input type="text" class="form-control work-status" value="<?=$model->status == null ?\backend\helpers\WorkorderStatus::getTypeById(1):\backend\helpers\WorkorderStatus::getTypeById($model->status)?>" readonly>
            </div>
        </div>

        <br/>

        <div class="btn-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            <div class="btn btn-warning">อนุมัติ</div>
            <div class="btn btn-danger">ไม่อนุมัติ</div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
$js = <<<JS
var removelist = [];
$(function(){
    
});
function addline(e){
    var tr = $("#table-list tbody tr:last");
    var clone = tr.clone();
    clone.find(":text").val("");
    tr.after(clone);
    cal_line_no();
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
                  });
           } else {
              e.parent().parent().remove();
           }
          cal_line_no();    
    }
        
        
}
function cal_line_no(){
    var linenum = 0;
    $("#table-list tbody tr").each(function () {
         linenum+=1;
         $(this).find("td:eq(0)").html(linenum);
    });
}
JS;
$this->registerJs($js, static::POS_END);
?>