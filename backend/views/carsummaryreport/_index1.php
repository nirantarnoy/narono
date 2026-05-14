<?php

$date_day = '';
$date_month = '';
$date_year = '';


use kartik\date\DatePicker;

$model_line = null;

if ($from_date != '' && $to_date != '') {
    $date_day = date('d', strtotime($from_date));
    $date_month = \backend\helpers\Thaimonth::getTypeById((int)(date('m', strtotime($from_date))));
    $date_year = date('Y', strtotime($from_date)) + 543;

    if ($search_car_id != null) {
        $model_line = \common\models\QueryCarWorkSummary::find()->select(['query_car_work_summary.*','work_queue.sunday_price','work_queue.rangsit_price','work_queue.diligence_price','work_queue.delivery_2_cus_price','work_queue.traffic_fine_price','work_queue.labour_price_general','work_queue.labour_price_special','work_queue.incentive_price'])->joinWith('workqueue')->where(['query_car_work_summary.car_id' => $search_car_id])->andFilterWhere(['>=', 'date(query_car_work_summary.work_queue_date)', $from_date])->andFilterWhere(['<=', 'date(query_car_work_summary.work_queue_date)', $to_date])->orderBy(['query_car_work_summary.work_queue_date' => SORT_ASC])->all();
    }

    $from_date = date('d-m-Y', strtotime($from_date));
    $to_date = date('d-m-Y', strtotime($to_date));
}

//echo $_SESSION['driver_login'] ;
$driver_login_id = 0;
if (isset($_SESSION['driver_login'])){
    $driver_login_id = $_SESSION['driver_login'];
}

$driver_car_id = 0;
$model_find_emp_id = \backend\models\User::find()->where(['id'=>$driver_login_id])->one();
if ($model_find_emp_id){
    $model_x = \backend\models\Car::find()->where(['driver_id'=>$model_find_emp_id->employee_ref_id])->one();
    if ($model_x){
        $driver_car_id = $model_x->id;
    }
}

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
<div class="row">
    <div class="col-lg-12">
        <form action="<?= \yii\helpers\Url::to(['carsummaryreport/indexnew'], true) ?>" method="post">
            <div class="row">
                <div class="col-lg-3">
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
                <div class="col-lg-3">

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
                        'data' => \yii\helpers\ArrayHelper::map(\backend\models\Car::find()->where(['status' => 1,'id'=>$driver_car_id])->all(), 'id', 'name'),
                        'value' => $search_car_id,
//                        'options' => [
//                            'placeholder' => '---เลือกรถ---'
//                        ],
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
                    <b><?= \backend\models\Company::findCompanyName(1) ?></b></h4></td>
            <td style="text-align: right;width: 33%"></td>
        </tr>
        <tr>
            <td style="text-align: right;width: 33%"></td>
            <td style="text-align: center;width: 33%">
                <h6><?= \backend\models\Company::findAddress(1) ?></h6></td>
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
            <td style="text-align: center;width: 33%"><h5><b>เดือน <?=$date_month.' '.$date_year?></b></h5></td>
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
            <td style="width:50%;padding: 5px;"> ทะเบียน <b><?= \backend\models\Car::getPlateno($search_car_id) ?></b>
            </td>
            <!--            <td><input type="text" class="form-control"></td>-->
        </tr>
    </table>
    <br>

    <table style="width: 100%;border: 1px solid grey;">
        <thead>
        <tr>
            <th style="text-align: center;padding: 10px;border: 1px solid grey;"><b>วันที่ขึ้นสินค้า</b></th>
            <th style="text-align: center;padding: 10px;border: 1px solid grey;"><b>สถานที่</b></th>
            <th style="text-align: center;padding: 10px;border: 1px solid grey;"><b>รายการ</b></th>
            <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>ค่าเที่ยว</b></th>
            <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>ค่าคลุมผ้าใบ</b></th>
            <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>ค่าค้างคืน</b></th>
            <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>ค่าบวกคลัง</b></th>
            <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>ค่าเบี้ยขยัน</b></th>
            <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>พิเศษอื่นๆ</b></th>
            <th style="text-align: right;padding: 10px;border: 1px solid grey;"><b>รวม</b></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sum_col_4 = 0;
        $sum_col_5 = 0;
        $sum_col_6 = 0;
        $sum_col_7 = 0;
        $sum_col_8 = 0;
        $sum_col_9 = 0;
        $sum_col_10 = 0;
        $sum_incentive_all = 0;
        $sum_traffic_fine = 0;

        $cost_living_price = \backend\models\Employee::findCostLivingPrice($search_car_id);
        //$social_price = \backend\models\Employee::findSocialPrice($search_car_id); // percent
        $social_price = \backend\models\Company::findCompanySocialPer(1); // company percent
        //$social_per_text = \backend\models\Employee::findSocialPricePer($search_car_id);
        $social_base_price = \backend\models\Company::findSocialbasePrice(1);

        $deduct_total = 0;
        $social_deduct_amount = 0;
        ?>
        <?php if ($model_line != null): ?>
            <?php foreach ($model_line as $value): ?>
                <?php
                $sum_col_4 += ($value->work_labour_price);
                $sum_col_5 += ($value->work_express_road_price);
                $sum_col_6 += ($value->cover_sheet_price);
                $sum_col_7 += ($value->overnight_price);
                $sum_col_8 += ($value->warehouse_plus_price);
                $sum_col_9 += ($value->work_other_price);

                $line_incentive = ($value->sunday_price + $value->rangsit_price + $value->diligence_price + $value->delivery_2_cus_price + $value->incentive_price);
                $sum_incentive_all += $line_incentive;
                $sum_traffic_fine += $value->traffic_fine_price;

                $line_total = ($value->work_labour_price + $value->work_express_road_price + $value->cover_sheet_price  + $value->overnight_price + $value->warehouse_plus_price + $value->work_other_price + $line_incentive) - $value->traffic_fine_price;
                $sum_col_10 += ($line_total);


                ?>
                <tr>
                    <td style="border: 1px solid grey;padding: 5px;text-align: center;"><?= date('d-m-Y', strtotime($value->work_queue_date)) ?></td>
                    <td style="border: 1px solid grey;padding: 5px;"><?= \backend\models\Customer::findCusName($value->customer_id) ?></td>
                    <td style="border: 1px solid grey;padding: 5px;text-align: center;"><?= \backend\models\Customer::findWorkTypeByCustomerid($value->customer_id) ?></td>
                    <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($value->work_labour_price, 2) ?></td>
                    <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($value->cover_sheet_price, 2) ?></td>
                    <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($value->overnight_price, 2) ?></td>
                    <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($value->warehouse_plus_price, 2) ?></td>
                    <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($line_incentive, 2) ?></td>
                    <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($value->work_other_price, 2) ?></td>
                    <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($line_total, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php
        $base_deduct = (($social_base_price * $social_price)/100); //15000
        if(($sum_col_4 + $cost_living_price) >= $social_base_price){
            $deduct_total = $base_deduct;
        }else{
            $deduct_total = (($sum_col_4 + $cost_living_price) * $social_price /100);
        }

        ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" style="border: 1px solid grey;padding: 5px;text-align: right;"><b>รวม</b></td>
            <td style="border: 1px solid grey;padding: 5px;text-align: right;">
                <b><?= number_format($sum_col_4, 2) ?></b></td>
            <td style="border: 1px solid grey;padding: 5px;text-align: right;">
                <b><?= number_format($sum_col_6, 2) ?></b></td>
            <td style="border: 1px solid grey;padding: 5px;text-align: right;">
                <b><?= number_format($sum_col_7, 2) ?></b></td>
            <td style="border: 1px solid grey;padding: 5px;text-align: right;">
                <b><?= number_format($sum_col_8, 2) ?></b></td>
            <td style="border: 1px solid grey;padding: 5px;text-align: right;">
                <b><?= number_format($sum_incentive_all, 2) ?></b></td>
            <td style="border: 1px solid grey;padding: 5px;text-align: right;">
                <b><?= number_format($sum_col_9, 2) ?></b></td>
            <td style="border: 1px solid grey;padding: 5px;text-align: right;">
                <b><?= number_format($sum_col_10, 2) ?></b></td>
        </tr>
        </tfoot>
    </table>
    <br>
    <table style="width: 100%;border: 1px solid grey">
        <tr>
            <td></td>
            <td style="padding-top:20px;"><b>รายได้</b></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="padding-top:20px;"><b>หัก</b></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td style="padding-left: 10px;">ค่าครองชีพ</td>
            <td></td>
            <td style="text-align: right;padding: 5px;"><?=number_format($cost_living_price,2)?></td>
            <td style="text-align: center;padding: 5px;">บาท</td>
            <td style="padding-left: 10px;">ค่าประกันสังคม <?=$social_price.' %'?></td>
            <td style="text-align: right;padding: 5px;"><?=number_format($deduct_total,2)?></td>
            <td style="text-align: center;padding: 5px;">บาท</td>
        </tr>
        <tr>
            <td></td>

            <td style="padding-left: 10px;">ค่าเที่ยว</td>
            <td></td>
            <td style="text-align: right;padding: 5px;"><?=number_format($sum_col_4,2)?></td>
            <td style="text-align: center;padding: 5px;">บาท</td>
            <td style="padding-left: 10px;">เงินยืมทดลอง</td>
            <td style="text-align: right;padding: 5px;">0</td>
            <td style="text-align: center;padding: 5px;">บาท</td>
        </tr>
        <tr>
            <td></td>

            <td style="padding-left: 10px;">ค่าคลุมผ้าใบ</td>
            <td></td>
            <td style="text-align: right;padding: 5px;"><?=number_format($sum_col_6,2)?></td>
            <td style="text-align: center;padding: 5px;">บาท</td>
            <td style="padding-left: 10px;">ค่าประกันสินค้า</td>
            <td style="text-align: right;padding: 5px;">0</td>
            <td style="text-align: center;padding: 5px;">บาท</td>
        </tr>
        <tr>
            <td></td>
            <td style="padding-left: 10px;">ค่าค้างคืน</td>
            <td></td>
            <td style="text-align: right;padding: 5px;"><?=number_format($sum_col_7,2)?></td>
            <td style="text-align: center;padding: 5px;">บาท</td>
            <td></td>
            <td style="text-align: right;padding: 5px;"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td style="padding-left: 10px;">ค่าบวกคลัง</td>
            <td></td>
            <td style="text-align: right;padding: 5px;"><?=number_format($sum_col_8,2)?></td>
            <td style="text-align: center;padding: 5px;">บาท</td>
            <td></td>
            <td style="text-align: right;padding: 5px;"></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td style="padding-left: 10px;">ค่าพิเศษอื่นๆ/เบี้ยขยัน</td>
            <td></td>
            <td style="text-align: right;padding: 5px;"><?=number_format($sum_col_9 + $sum_incentive_all,2)?></td>
            <td style="text-align: center;padding: 5px;">บาท</td>
            <td>หักค่าปรับจราจร</td>
            <td style="text-align: right;padding: 5px;"><?=number_format($sum_traffic_fine,2)?></td>
            <td style="text-align: center;padding: 5px;">บาท</td>
        </tr>
        <tr>
            <td></td>
            <td><b>รวม</b></td>
            <td></td>
            <td style="text-align: right;padding: 5px;"><b><u><?=number_format(($sum_col_10 + $cost_living_price),2)?></u></b></td>
            <td style="text-align: center;padding: 5px;">บาท</td>
            <td><b>คงเหลือ</b></td>
            <td style="text-align: right;padding: 5px;"><b><u><?=number_format(($sum_col_10 + $cost_living_price) - $deduct_total - $sum_traffic_fine,2)?></u></b></td>
            <td style="text-align: center;padding: 5px;">บาท</td>
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
