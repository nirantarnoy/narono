<?php

use kartik\date\DatePicker;

$display_date = date('d-m-Y');
$display_to_date = date('d-m-Y');
$find_date = date('Y-m-d');
$find_to_date = date('Y-m-d');
if ($search_date != null) {
    $find_date = date('Y-m-d', strtotime($search_date));
    $display_date = date('d-m-Y', strtotime($search_date));
}
if ($search_to_date != null) {
    $find_to_date = date('Y-m-d', strtotime($search_to_date));
    $display_to_date = date('d-m-Y', strtotime($search_to_date));
}
$model = null;

//$model = \backend\models\Workqueue::find()->where(['date(work_queue_date)' => $find_date,'work_option_type_id'=>[1,2]])->all();
if ($search_cost_type != null) {
    $model = \common\models\QueryCashRecord::find()->where(['cost_title_id' => $search_cost_type, 'company_id' => $search_company_id, 'office_id' => $search_office_id])->andFilterWhere(['>=', 'date(trans_date)', $find_date])->andFilterWhere(['<=', 'date(trans_date)', $find_to_date])->all();
} else {
    $model = \common\models\QueryCashRecord::find()->where(['company_id' => $search_company_id, 'office_id' => $search_office_id])->andFilterWhere(['>=', 'date(trans_date)', $find_date])->andFilterWhere(['<=', 'date(trans_date)', $find_to_date])->all();
}

?>
<form action="<?= \yii\helpers\Url::to(['cashreportdaily/index'], true) ?>" method="post">
    <div class="row">
        <div class="col-lg-2">
            <label class="form-label">ตั้งแต่วันที่</label>

            <?php
            echo DatePicker::widget([
                'name' => 'search_date',
                'type' => DatePicker::TYPE_INPUT,
                'value' => $display_date,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-mm-yyyy'
                ]
            ]);
            ?>
        </div>
        <div class="col-lg-2">
            <label class="form-label">ถึงวันที่</label>
            <?php
            echo DatePicker::widget([
                'name' => 'search_to_date',
                'type' => DatePicker::TYPE_INPUT,
                'value' => $display_to_date,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd-mm-yyyy'
                ]
            ]);
            ?>
        </div>
        <div class="col-lg-2">
            <label class="form-label">ประเภท</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name' => 'search_cost_type',
                'data' => \yii\helpers\ArrayHelper::map(\common\models\FixcostTitle::find()->where(['status' => 1])->all(), 'id', 'name'),
                'value' => $search_cost_type,
                'options' => [
                    'placeholder' => '---เลือกประเภทค่าใช้จ่าย---'
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]);
            ?>
        </div>
        <div class="col-lg-2">
            <label class="form-label">บริษัท</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name' => 'search_company_id',
                'data' => \yii\helpers\ArrayHelper::map(\common\models\Company::find()->where(['status' => 1])->all(), 'id', 'name'),
                'value' => $search_company_id,
                'options' => [
                    'placeholder' => '---เลือกบริษัท---',
                    'onchange' => 'getOfficeList($(this).val());'
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]);
            ?>
        </div>
        <div class="col-lg-2">
            <label class="form-label">สำนักงาน</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name' => 'search_office_id',
                'data' => \yii\helpers\ArrayHelper::map(\backend\helpers\OfficeType::asArrayObject(), 'id', 'name'),
                'value' => $search_office_id,
                'id' => 'office-list',
                'options' => [
                    'placeholder' => '---เลือกสำนักงาน---'
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]);
            ?>
        </div>
        <div class="col-lg-2">
            <button class="btn btn-primary" style="margin-top: 30px;">ค้นหา</button>
        </div>
    </div>
</form>
<br/>
<div id="print-area">
    <table style="width: 100%;">
        <tr>
            <td style="text-align: center;"><h4><b>รายงานเงินสดย่อย</b></h4></td>
        </tr>
        <tr>
            <td style="text-align: center;"><h5>
                    <b><?= \backend\models\Company::findCompanyName($search_company_id) ?></b></h5></td>
        </tr>
        <tr>
            <td style="text-align: center;"><h5>หน่วยงาน
                    <b><?= \backend\helpers\OfficeType::getTypeById($search_office_id) ?></b></h5></td>
        </tr>
        <tr>
            <td style="text-align: center;"><b>วันที่ <?= date('d/m/Y', strtotime($find_date)); ?>
                    ถึงวันที่ <?= date('d/m/Y', strtotime($find_to_date)); ?></b></td>
        </tr>
    </table>
    <br>
    <table class="table table-bordered" style="width: 100%;" id="table-data">
        <thead>
        <tr>
            <th style="width: 5%;text-align: center;">ลำดับที่</th>
            <th style="width: 8%;text-align: center;">วันที่</th>
            <th style="width: 8%;text-align: center;">ประเภทจ่ายเงิน</th>
            <th style="width: 10%;text-align: center;">ข้อมูลธนาคาร</th>
            <th style="text-align: center;">ชื่อ</th>
            <th style="width: 8%;text-align: center;">ทะเบียนหัว</th>
            <th style="width: 8%;text-align: center;">ทะเบียนหาง</th>
            <th style="width: 10%;text-align: center;">ประเภทค่าใช้จ่าย</th>
            <th style="width: 8%;text-align: right;">จำนวนเงิน</th>
            <th style="width: 10%;text-align: right;">ผู้จ่ายเงิน</th>
            <th style="width: 10%;text-align: center;">หมายเหตุ</th>
        </tr>
        </thead>

        <?php $line_num = 0;
        $total_amount = 0; ?>
        <?php if ($model): ?>
            <?php foreach ($model as $value): ?>
                <?php
                $line_num += 1;
                $total_amount += ($value->amount);
                ?>
                <tr>
                    <td style="text-align: center;padding: 1px;"><?= $line_num ?></td>
                    <td style="text-align: center;padding: 1px;"><?= date('d/m/Y', strtotime($value->trans_date)) ?></td>
                    <td style="text-align: center;padding: 1px;"><?= \backend\models\Paymentmethod::findName($value->payment_method_id) ?></td>
                    <td style="text-align: center;padding: 1px;"><?= $value->bank_account ?></td>
                    <td style="text-align: left;padding: 1px;"><?= $value->pay_for_type_id != 1 ? $value->pay_for : $value->fname . ' ' . $value->lname ?></td>
                    <td style="text-align: center;padding: 1px;"><?= $value->car_plate_no ?></td>
                    <td style="text-align: center;padding: 1px;"><?= $value->car_tail_plate_no ?></td>
                    <td style="text-align: center;padding: 1px;"><?= $value->name ?></td>
                    <td style="text-align: right;padding: 1px;"><?= number_format($value->amount, 2) ?></td>
                    <td style="text-align: left;padding: 1px;"><?= \backend\models\Employee::findFullName($value->cashier_by) ?></td>
                    <td style="text-align: left;padding: 1px;"><?= $value->remark ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        <tr>
            <td colspan="8" style="width: 8%;text-align: right;"><b>รวมจ่าย</b></td>
            <td style="width: 10%;text-align: right;"><b><?= number_format($total_amount, 3) ?></b></td>
            <td></td>
            <td></td>
        </tr>

    </table>

</div>

<div class="row">
    <div class="col-lg-4">
        <div class="input-group">
            <div class="btn btn-warning btn-print" onclick="printContent('print-area')">พิมพ์</div>
            <div class="btn btn-info" id="btn-export-excel">Export Excel</div>
        </div>

    </div>
</div>
<?php
$this->registerJsFile(\Yii::$app->request->baseUrl . '/js/jquery.table2excel.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$url_to_get_office = \yii\helpers\Url::to(['cashreportdaily/getoffice'], true);
$js = <<<JS
$("#btn-export-excel").click(function(){
  $("#table-data").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "Excel Document Name"
  });
});
function printContent(el)
      {
         var restorepage = document.body.innerHTML;
         var printcontent = document.getElementById(el).innerHTML;
         document.body.innerHTML = printcontent;
         window.print();
         document.body.innerHTML = restorepage;
     }
     
function getOfficeList(el){
    var company_id = $(el).val();
    $.ajax({
        url: '$url_to_get_office',
        type: 'POST',
        dataType: 'html',
        data: {'company_id': company_id},
        success: function (data) {
            alert(data);
        
            $('#office-list').html(data);
        }
        });
}     
JS;
$this->registerJs($js, static::POS_END);
?>


