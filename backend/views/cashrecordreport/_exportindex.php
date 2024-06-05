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
        $model_line = \common\models\QueryCarWorkSummary::find()->where(['car_id' => $search_car_id])->andFilterWhere(['>=', 'date(work_queue_date)', $from_date])->andFilterWhere(['<=', 'date(work_queue_date)', $to_date])->orderBy(['work_queue_date' => SORT_ASC])->all();
    }

    $from_date = date('d-m-Y', strtotime($from_date));
    $to_date = date('d-m-Y', strtotime($to_date));
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
            <form action="<?= \yii\helpers\Url::to(['carsummaryreport/exportindex'], true) ?>" method="post">
                <div class="row">
                    <div class="col-lg-2">
                        <label class="form-label">ตั้งแต่วันที่</label>

                        <?php
                        echo DatePicker::widget([
                            'name' => 'search_from_date',
                            'type' => DatePicker::TYPE_INPUT,
                            'value' => $from_date,
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
                            'value' => $to_date,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy'
                            ]
                        ]);
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label">รถ</label>

                        <?php
                        echo \kartik\select2\Select2::widget([
                            'name' => 'search_car_id',
                            'data' => \yii\helpers\ArrayHelper::map(\backend\models\Car::find()->where(['status' => 1])->all(), 'id', 'name'),
                            'value' => $search_car_id,
                            'options' => [
                                'placeholder' => '---เลือกรถ---'
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
        <table style="width: 100%">
            <tr>
                <td style="text-align: right;width: 33%"></td>
                <td style="text-align: center;width: 33%"><h4>
                        <b><?= \backend\models\Company::findCompanyName($emp_company_id) ?></b></h4></td>
                <td style="text-align: right;width: 33%"></td>
            </tr>
            <tr>
                <td style="text-align: right;width: 33%"></td>
                <td style="text-align: center;width: 33%">
                    <h6><?= \backend\models\Company::findAddress($emp_company_id) ?></h6></td>
                <td style="text-align: right;width: 33%"></td>
            </tr>
        </table>
        <br>
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
        <table style="width: 100%">
            <tr>
                <td style="width:30%;padding: 5px;">ชื่อพนักงานขับรถ
                    <b><?= \backend\models\Car::findDrivername($search_car_id) ?>
                    </b>
                </td>
                <!--            <td><input type="text" class="form-control"></td>-->
                <td style="width:50%;padding: 5px;"> ทะเบียน
                    <b><?= \backend\models\Car::getPlateno($search_car_id) ?></b>
                </td>
                <!--            <td><input type="text" class="form-control"></td>-->
            </tr>
        </table>
        <br>

        <table style="width: 100%;border: 1px solid grey;">
            <thead>
            <tr>
                <th style="text-align: center;padding: 10px;border: 1px solid grey;"><b>ลำดับที่</b></th>
                <th style="text-align: center;padding: 10px;border: 1px solid grey;"><b>ชื่อพนักงาน</b></th>
                <th style="text-align: center;padding: 10px;border: 1px solid grey;"><b>วันที่</b></th>
                <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>ลูกค้า</b></th>
                <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>จำนวนเงิน</b></th>
            </tr>
            </thead>
            <tbody>

            </tbody>

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
