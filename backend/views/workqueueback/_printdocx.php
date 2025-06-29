<?php


$date_day = date('d',strtotime($model->work_queue_date));
$date_month = \backend\helpers\Thaimonth::getTypeById((int)(date('m',strtotime($model->work_queue_date))));
$date_year = date('Y',strtotime($model->work_queue_date)) + 543;
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
    <div class="col-lg-12">
        <div class="btn btn-default btn-print" onclick="printContent('print-area')">พิมพ์</div>
    </div>
</div>
<div id="print-area">
    <table style="width: 100%">
        <tr>
            <td style="text-align: right;width: 33%"></td>
            <td style="text-align: center;width: 33%"><h4><b><?=\backend\models\Company::findCompanyName($model->company_id)?></b></h4></td>
            <td style="text-align: right;width: 33%"></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%">
        <tr>
            <td style="padding: 5px;width: 33%">เล่มที่</td>
            <td style="text-align: center;width: 33%"><h5><b>ใบสั่งจ่ายน้ำมัน</b></h5></td>
            <td style="text-align: right;width: 33%">เลขที่ <b><?= $model->work_queue_no ?></b></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%">
        <tr>
            <td style="text-align: center"><b>วันที่ <?= $date_day ?> เดือน <?= $date_month ?>
                    พ.ศ. <?= $date_year ?></b></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%">
        <tr>
            <td style="width:15%;padding: 5px;">ทะเบียนหัว <b><?= \backend\models\Car::getPlateno($model->car_id) ?></b></td>
            <!--            <td><input type="text" class="form-control"></td>-->
            <td style="width:15%;padding: 5px;"> ทะเบียนหาง <b><?= \backend\models\Car::getPlateno($model->tail_id) ?></b></td>
            <!--            <td><input type="text" class="form-control"></td>-->
            <td style="width:15%;padding: 5px;"> ประเภทรถ <b><?= \backend\models\Car::getCartype($model->car_id) ?></b></td>
            <!--            <td><input type="text" class="form-control"></td>-->
            <td style="width:15%;padding: 5px;"> แรงรถ <b><?= \backend\models\Car::getHp($model->car_id) ?></b></td>
            <!--            <td><input type="text" class="form-control"></td>-->
        </tr>
    </table>
    <br>
    <table style="width: 100%">
        <tr>
            <td style="width:15%;padding: 5px;">
                พนักงาน <b><?= \backend\models\Employee::findFullName($model->emp_assign) ?></b>
            </td>
            <td style="width:15%;padding: 5px;">
                DP_NO <b><?= $model->dp_no ?></b>
            </td>
        </tr>
    </table>
    <br>
    <table style="width: 100%">
        <tr>
            <td style="width:15%;padding: 5px;">
                ต้นทาง-ปลายทาง <b><?= \backend\models\RoutePlan::findDes($model->route_plan_id) ?></b>
            </td>
        </tr>
    </table>

    <br>
    <table style="width: 100%">
        <tr>
            <td style="width:15%;padding: 5px;"><b>เที่ยวไป</b></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%">
        <tr>
            <td style="width:15%;padding: 5px;">น้ำหนักเที่ยวไป</td>
            <td style="width:15%;padding: 5px;"><b><?= number_format($model->weight_on_go, 2) ?></b></td>
            <td style="width:15%;padding: 5px;">หัก</td>
            <td style="width:15%;padding: 5px;"><b><?= number_format($model->weight_go_deduct, 2) ?></b></td>
            <td style="width:15%;padding: 5px;">เหตุผล</td>
            <td style="width:15%;padding: 5px;"><b><?=$model->go_deduct_reason?></b></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%">
        <tr>
            <td style="width:15%;padding: 5px;"><b>เที่ยวกลับ</b></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%">
        <tr>
            <td style="width:15%;padding: 5px;">น้ำหนักเที่ยวกลับ</td>
            <td style="width:15%;padding: 5px;"><b><?= number_format($model->weight_on_back, 2) ?></b></td>
            <td style="width:15%;padding: 5px;">เรทน้ำมันกลับ</td>
            <td style="width:15%;padding: 5px;"><b><?= number_format($model->oil_daily_price, 2) ?></b></td>
            <td style="width:15%;padding: 5px;">หัก</td>
            <td style="width:15%;padding: 5px;"><b><?= number_format($model->back_deduct, 2) ?></b></td>
        </tr>
        <tr>
            <td style="width:15%;padding: 5px;">เหตุผล</td>
            <td style="width:15%;padding: 5px;"><b><?=$model->back_reason?></b></td>
            <td style="width:15%;padding: 5px;">หางกลับ</td>
            <td style="width:15%;padding: 5px;"><b><?= \backend\models\Car::getPlateno($model->tail_back_id) ?></b></td>
        </tr>
    </table>
    <br>


</div>

<?php
$js = <<<JS
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
