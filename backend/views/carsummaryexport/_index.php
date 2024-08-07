<?php

$date_day = '';
$date_month = '';
$date_year = '';


use kartik\date\DatePicker;

$model_line = null;

if ($from_date != '' && $to_date != '') {
    $date_day = date('d', strtotime($to_date));
    $date_month = \backend\helpers\Thaimonth::getTypeById((int)(date('m', strtotime($to_date))));
    $date_year = date('Y', strtotime($to_date)) + 543;

    if ($search_car_id != null) {
        $model_line = \common\models\QueryCarWorkSummary::find()->where(['company_id' => $search_car_id])->andFilterWhere(['>=', 'date(work_queue_date)', date('Y-m-d', strtotime($from_date))])->andFilterWhere(['<=', 'date(work_queue_date)', date('Y-m-d', strtotime($to_date))])->orderBy(['work_queue_date' => SORT_ASC])->all();
    } else {
        $model_line = \common\models\QueryCarWorkSummary::find()->where(['>=', 'date(work_queue_date)', date('Y-m-d', strtotime($from_date))])->andFilterWhere(['<=', 'date(work_queue_date)', date('Y-m-d', strtotime($to_date))])->orderBy(['work_queue_date' => SORT_ASC])->all();
    }

    $from_date = date('d-m-Y', strtotime($from_date));
    $to_date = date('d-m-Y', strtotime($to_date));

    //echo $from_date.' '.$to_date;
}
$driver_id = \backend\models\Car::getDriver($search_car_id);
$emp_company_id = \backend\models\Employee::findEmpcompanyid($driver_id);

?>
<style>
    /*body {*/
    /*    font-family: sarabun;*/
    /*    !*font-family: garuda;*!*/
    /*    font-size: 18px;*/
    /*    width: 350px;*/
    /*}*/

    /*table.table-header {*/
    /*    border: 0px;*/
    /*    border-spacing: 1px;*/
    /*}*/

    /*table.table-qrcode {*/
    /*    border: 0px;*/
    /*    border-spacing: 1px;*/
    /*}*/

    /*table.table-qrcode td, th {*/
    /*    border: 0px solid #dddddd;*/
    /*    text-align: left;*/
    /*    padding-top: 2px;*/
    /*    padding-bottom: 2px;*/
    /*}*/

    /*table.table-footer {*/
    /*    border: 0px;*/
    /*    border-spacing: 0px;*/
    /*}*/

    /*table.table-header td, th {*/
    /*    border: 0px solid #dddddd;*/
    /*    text-align: left;*/
    /*    padding-top: 2px;*/
    /*    padding-bottom: 2px;*/
    /*}*/

    /*table.table-title {*/
    /*    border: 0px;*/
    /*    border-spacing: 0px;*/
    /*}*/

    /*table.table-title td, th {*/
    /*    border: 0px solid #dddddd;*/
    /*    text-align: left;*/
    /*    padding-top: 2px;*/
    /*    padding-bottom: 2px;*/
    /*}*/

    /*table {*/
    /*    border-collapse: collapse;*/
    /*    width: 100%;*/
    /*}*/

    /*td, th {*/
    /*    border: 1px solid #dddddd;*/
    /*    text-align: left;*/
    /*    padding: 8px;*/
    /*}*/

    /*tr:nth-child(even) {*/
    /*    !*background-color: #dddddd;*!*/
    /*}*/

    /*table.table-detail {*/
    /*    border-collapse: collapse;*/
    /*    width: 100%;*/
    /*}*/

    /*table.table-detail td, th {*/
    /*    border: 1px solid #dddddd;*/
    /*    text-align: left;*/
    /*    padding: 2px;*/
    /*}*/

    @media print {
        @page {
            size: auto;
        }
    }

    /*@media print {*/
    /*    html, body {*/
    /*        width: 80mm;*/
    /*        height:100%;*/
    /*        position:absolute;*/
    /*    }*/
    /*}*/

</style>
<div class="row">
    <div class="col-lg-12" style="text-align: right;">
        <div class="btn btn-default btn-print" onclick="printContent('print-area')">พิมพ์</div>
    </div>
</div>
<div id="table-data">
    <div class="row">
        <div class="col-lg-12">
            <form action="<?= \yii\helpers\Url::to(['carsummaryexport/index'], true) ?>" method="post">
                <div class="row">
                    <div class="col-lg-2">
                        <label class="form-label">ตั้งแต่วันที่</label>

                        <?php
                        echo DatePicker::widget([
                            'name' => 'search_from_date',
                            'type' => DatePicker::TYPE_INPUT,
                            'value' => date('d-m-Y', strtotime($from_date)),
                            'pluginOptions' => [
                                'autoClose' => true,
                                'format' => 'dd-mm-yyyy',
                                'todayHighlight' => true,
                                'todayBtn' => true,
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
                            'value' => date('d-m-Y', strtotime($to_date)),
                            'pluginOptions' => [
                                'autoClose' => true,
                                'format' => 'dd-mm-yyyy',
                                'todayHighlight' => true,
                                'todayBtn' => true,
                            ]
                        ]);
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label">บริษัท</label>

                        <?php
                        echo \kartik\select2\Select2::widget([
                            'name' => 'search_car_id',
                            'data' => \yii\helpers\ArrayHelper::map(\backend\models\Company::find()->where(['status' => 1])->all(), 'id', 'name'),
                            'value' => $search_car_id,
                            'options' => [
                                'placeholder' => '---เลือก---'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ]
                        ]);
                        ?>


                    </div>
                    <div class="col-lg-3">
                        <div style="height: 35px;"></div>
                        <button class="btn btn-sm btn-primary">ค้นหา</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div style="height: 20px;"></div>
    <div id="print-area">
        <!--        <table style="width: 100%">-->
        <!--            <tr>-->
        <!--                <td style="text-align: right;width: 33%"></td>-->
        <!--                <td style="text-align: center;width: 33%"><h4>-->
        <!--                        <b>-->
        <?php //= \backend\models\Company::findCompanyName($emp_company_id) ?><!--</b></h4></td>-->
        <!--                <td style="text-align: right;width: 33%"></td>-->
        <!--            </tr>-->
        <!--            <tr>-->
        <!--                <td style="text-align: right;width: 33%"></td>-->
        <!--                <td style="text-align: center;width: 33%">-->
        <!--                    <h6>-->
        <?php //= \backend\models\Company::findAddress($emp_company_id) ?><!--</h6></td>-->
        <!--                <td style="text-align: right;width: 33%"></td>-->
        <!--            </tr>-->
        <!--        </table>-->
        <!--        <br>-->
        <table style="width: 100%">
            <tr>
                <td style="padding: 5px;width: 33%"></td>
                <td style="text-align: center;width: 33%"><h5><b>รายงานค่าเที่ยว</b></h5></td>
                <td style="text-align: right;width: 33%"><b></b></td>
            </tr>
        </table>
        <table style="width: 100%">
            <tr>
                <td style="padding: 5px;width: 33%"></td>
                <td style="text-align: center;width: 33%"><h5><b>เดือน <?= $date_month . ' ' . $date_year ?></b></h5>
                </td>
                <td style="text-align: right;width: 33%"><b></b></td>
            </tr>
        </table>
        <br>
        <table style="width: 100%;border: 1px solid grey;">
            <thead>
            <tr>
                <th style="text-align: center;padding: 10px;border: 1px solid grey;width: 10%"><b>ลำดับที่</b></th>
                <th style="text-align: center;padding: 10px;border: 1px solid grey;"><b>ชื่อพนักงาน</b></th>
                <th style="text-align: center;padding: 10px;border: 1px solid grey;"><b>วันที่</b></th>
                <th style="text-align: center;padding: 10px;border: 1px solid grey;"><b>วัน</b></th>
                <th style="text-align: center;padding: 10px;border: 1px solid grey;"><b>เดือน</b></th>
                <th style="text-align: center;padding: 10px;border: 1px solid grey;"><b>ปี</b></th>
                <th style="text-align: center;padding: 10px;border: 1px solid grey;"><b>ลูกค้า</b></th>
                <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>ค่าเที่ยว</b></th>
                <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>ค่าคลุมผ้าใบ</b></th>
                <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>ค่าค้างคืน</b></th>
                <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>ค่าบวกคลัง</b></th>
                <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>ค่าเบิ้ลงาน</b></th>
                <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>ค่าลากจูง</b></th>
                <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>รวมค่าอื่นๆ</b></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total_amount = 0;
            $total_other_amount = 0;
            $total_line_all = 0;
            $total_cover_amount = 0;
            $total_overnight_amount = 0;
            $total_warehouse_plus_amount = 0;
            $total_work_double_amount = 0;
            $total_towing_amount = 0;

            if ($model_line != null):?>
                <?php $i = 1; ?>
                <?php foreach ($model_line as $value): ?>
                    <?php
                    $line_amount = $value->work_labour_price;
                    $other_amount = $value->trail_labour_price + $value->cover_sheet_price + $value->overnight_price + $value->warehouse_plus_price + $value->work_double_price + $value->towing_price;

                    $cover_amount = $value->cover_sheet_price;
                    $overnight_amount = $value->overnight_price;
                    $warehouse_plus_amount = $value->warehouse_plus_price;
                    $work_double_amount = $value->work_double_price;
                    $towing_amount = $value->towing_price;

                    $total_cover_amount = $total_cover_amount + $cover_amount;
                    $total_overnight_amount = $total_overnight_amount + $overnight_amount;
                    $total_warehouse_plus_amount = $total_warehouse_plus_amount + $warehouse_plus_amount;
                    $total_work_double_amount = $total_work_double_amount + $work_double_amount;
                    $total_towing_amount = $total_towing_amount + $towing_amount;





                    $line_total_amount = $line_amount + $other_amount;

                    $total_amount = $total_amount + $line_amount;
                    $total_other_amount = $total_other_amount + $total_other_amount;
                    $total_line_all = $total_line_all + $line_total_amount;
                    ?>
                    <tr>
                        <td style="text-align: center;border: 1px solid grey;padding: 3px;"><?= $i ?></td>
                        <td style="text-align: left;border: 1px solid grey;padding: 3px;"><?= \backend\models\Employee::findFullName($value->emp_assign) ?></td>
                        <td style="text-align: center;border: 1px solid grey;padding: 3px;"><?= date('d-m-Y', strtotime($value->work_queue_date)) ?></td>
                        <td style="text-align: center;border: 1px solid grey;padding: 3px;"><?= date('d', strtotime($value->work_queue_date)) ?></td>
                        <td style="text-align: center;border: 1px solid grey;padding: 3px;"><?= date('m', strtotime($value->work_queue_date)) ?></td>
                        <td style="text-align: center;border: 1px solid grey;padding: 3px;"><?= date('Y', strtotime($value->work_queue_date)) ?></td>
                        <td style="text-align: left;border: 1px solid grey;padding: 3px;"><?= \backend\models\Customer::findCusName($value->customer_id) ?></td>
                        <td style="text-align: right;border: 1px solid grey;padding: 3px;"><?= number_format($line_amount, 2) ?></td>
                        <td style="text-align: right;border: 1px solid grey;padding: 3px;"><?= number_format($cover_amount, 2) ?></td>
                        <td style="text-align: right;border: 1px solid grey;padding: 3px;"><?= number_format($overnight_amount, 2) ?></td>
                        <td style="text-align: right;border: 1px solid grey;padding: 3px;"><?= number_format($warehouse_plus_amount, 2) ?></td>
                        <td style="text-align: right;border: 1px solid grey;padding: 3px;"><?= number_format($work_double_amount, 2) ?></td>
                        <td style="text-align: right;border: 1px solid grey;padding: 3px;"><?= number_format($towing_amount, 2) ?></td>
                        <td style="text-align: right;border: 1px solid grey;padding: 3px;"><?= number_format($line_total_amount, 2) ?></td>
                    </tr>
                    <?php $i += 1; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="7" style="text-align: right;padding: 3px;border: 1px solid grey;"><b>รวม</b></td>
                <td style="text-align: right;padding: 3px;border: 1px solid grey;"><b><?=number_format($total_amount,2)?></b></td>
                <td style="text-align: right;padding: 3px;border: 1px solid grey;"><b><?=number_format($total_cover_amount,2)?></b></td>
                <td style="text-align: right;padding: 3px;border: 1px solid grey;"><b><?=number_format($total_overnight_amount,2)?></b></td>
                <td style="text-align: right;padding: 3px;border: 1px solid grey;"><b><?=number_format($total_warehouse_plus_amount,2)?></b></td>
                <td style="text-align: right;padding: 3px;border: 1px solid grey;"><b><?=number_format($total_work_double_amount,2)?></b></td>
                <td style="text-align: right;padding: 3px;border: 1px solid grey;"><b><?=number_format($total_towing_amount,2)?></b></td>
                <td style="text-align: right;padding: 3px;border: 1px solid grey;"><b><?=number_format($total_line_all,2)?></b></td>
            </tr>
            </tfoot>

        </table>
        <br>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="input-group">
            <div class="btn btn-info" id="btn-export-excel">Export Excel</div>
        </div>

    </div>
</div>
<?php
$this->registerJsFile(\Yii::$app->request->baseUrl . '/js/jquery.table2excel.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
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
JS;
$this->registerJs($js, static::POS_END);
?>
