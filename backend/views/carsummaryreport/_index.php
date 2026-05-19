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

    if ($search_car_id != null && $search_emp_id == null) {
        $model_line = \common\models\QueryCarWorkSummary::find()->select(['query_car_work_summary.*','work_queue.sunday_price','work_queue.rangsit_price','work_queue.diligence_price','work_queue.delivery_2_cus_price','work_queue.traffic_fine_price','work_queue.labour_price_general','work_queue.labour_price_special','work_queue.incentive_price','work_queue.towing_price','work_queue.work_double_price'])->joinWith('workqueue')->where(['query_car_work_summary.car_id' => $search_car_id])->andFilterWhere(['>=', 'date(query_car_work_summary.work_queue_date)', $from_date])->andFilterWhere(['<=', 'date(query_car_work_summary.work_queue_date)', $to_date])->orderBy(['query_car_work_summary.work_queue_date' => SORT_ASC])->all();
    }else if($search_car_id != null && $search_emp_id != null) {
        $model_line = \common\models\QueryCarWorkSummary::find()->select(['query_car_work_summary.*','work_queue.sunday_price','work_queue.rangsit_price','work_queue.diligence_price','work_queue.delivery_2_cus_price','work_queue.traffic_fine_price','work_queue.labour_price_general','work_queue.labour_price_special','work_queue.incentive_price','work_queue.towing_price','work_queue.work_double_price'])->joinWith('workqueue')->where(['query_car_work_summary.car_id' => $search_car_id,'query_car_work_summary.emp_assign'=>$search_emp_id])->andFilterWhere(['>=', 'date(query_car_work_summary.work_queue_date)', $from_date])->andFilterWhere(['<=', 'date(query_car_work_summary.work_queue_date)', $to_date])->orderBy(['query_car_work_summary.work_queue_date' => SORT_ASC])->all();
    }else if($search_car_id == null && $search_emp_id != null) {
        $model_line = \common\models\QueryCarWorkSummary::find()->select(['query_car_work_summary.*','work_queue.sunday_price','work_queue.rangsit_price','work_queue.diligence_price','work_queue.delivery_2_cus_price','work_queue.traffic_fine_price','work_queue.labour_price_general','work_queue.labour_price_special','work_queue.incentive_price','work_queue.towing_price','work_queue.work_double_price'])->joinWith('workqueue')->where(['query_car_work_summary.emp_assign'=>$search_emp_id])->andFilterWhere(['>=', 'date(query_car_work_summary.work_queue_date)', $from_date])->andFilterWhere(['<=', 'date(query_car_work_summary.work_queue_date)', $to_date])->orderBy(['query_car_work_summary.work_queue_date' => SORT_ASC])->all();
    }

    $from_date = date('d-m-Y', strtotime($from_date));
    $to_date = date('d-m-Y', strtotime($to_date));
}
$emp_company_id = 0;
$driver_id = \backend\models\Car::getDriver($search_car_id);
if($search_car_id != null) {
    $emp_company_id = \backend\models\Employee::findEmpcompanyid($driver_id);
}else{
    $emp_company_id = \backend\models\Employee::findEmpcompanyid($search_emp_id);
}


$fine_employee_amount = 0;
if($driver_id != null){
    $fine_employee_amount = \backend\models\Employeefine::find()->where(['emp_id' => $driver_id])->andFilterWhere(['>=', 'date(trans_date)', date('Y-m-d',strtotime($from_date))])->andFilterWhere(['<=', 'date(trans_date)', date('Y-m-d',strtotime($to_date))])->sum('fine_amount');
}

if($driver_id == null || $search_emp_id !=null){
    $fine_employee_amount = \backend\models\Employeefine::find()->where(['emp_id' => $search_emp_id])->andFilterWhere(['>=', 'date(trans_date)', date('Y-m-d',strtotime($from_date))])->andFilterWhere(['<=', 'date(trans_date)', date('Y-m-d',strtotime($to_date))])->sum('fine_amount');
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
<div id="table-data">
    <div class="row">
        <div class="col-lg-12">
            <form action="<?= \yii\helpers\Url::to(['carsummaryreport/index'], true) ?>" method="post">
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
                    <div class="col-lg-2">
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
                    <div class="col-lg-2">
                        <label class="form-label">พขร.</label>

                        <?php
                        echo \kartik\select2\Select2::widget([
                            'name' => 'search_emp_id',
                            'data' => \yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->where(['status' => 1])->all(), 'id', function($data){
                                return $data->fname.' '.$data->lname;
                            }),
                            'value' => $search_emp_id,
                            'options' => [
                                'placeholder' => '---เลือกพนักงาน---'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ]
                        ]);
                        ?>


                    </div>
                    <div class="col-lg-2">

                        <label class="form-label">% ประกันสังคม</label>
                        <input type="number" class="form-control" name="social_per" min="0" value="<?= $social_per ?>">
                    </div>
                    <div class="col-lg-2">
                        <div style="height: 35px;"></div>
                        <button class="btn btn-sm btn-primary">ค้นหาและคำนวน</button>
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
                    <b><?= $search_car_id !=null? \backend\models\Car::findDrivername($search_car_id): \backend\models\Employee::findFullName($search_emp_id) ?>
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
                <th rowspan="2" style="text-align: center;padding: 5px;border: 1px solid grey;"><b>วันที่ขึ้นสินค้า</b></th>
                <th rowspan="2" style="text-align: center;padding: 5px;border: 1px solid grey;"><b>สถานที่</b></th>
                <th rowspan="2" style="text-align: center;padding: 5px;border: 1px solid grey;"><b>รายการ</b></th>
                <th colspan="2" style="text-align: center;padding: 5px;border: 1px solid grey;"><b>กลุ่ม 1</b></th>
                <th colspan="5" style="text-align: center;padding: 5px;border: 1px solid grey;"><b>กลุ่ม 2</b></th>
                <th colspan="4" style="text-align: center;padding: 5px;border: 1px solid grey;"><b>กลุ่ม 3</b></th>
                <th colspan="1" style="text-align: center;padding: 5px;border: 1px solid grey;"><b>กลุ่ม 4</b></th>
                <th rowspan="2" style="text-align: center;padding: 5px;border: 1px solid grey;"><b>รวม</b></th>
            </tr>
            <tr>
                <th style="text-align: right;padding: 5px;border: 1px solid grey;"><b>ค่าเที่ยวทั่วไป</b></th>
                <th style="text-align: right;padding: 5px;border: 1px solid grey;"><b>ค่าเที่ยวพิเศษ</b></th>
                <th style="text-align: right;padding: 5px;border: 1px solid grey;"><b>ค่าแบก</b></th>
                <th style="text-align: right;padding: 5px;border: 1px solid grey;"><b>ค่าคลุมผ้าใบ</b></th>
                <th style="text-align: right;padding: 5px;border: 1px solid grey;"><b>ค่าบวกคลัง</b></th>
                <th style="text-align: right;padding: 5px;border: 1px solid grey;"><b>ค่าส่ง 2 ลูกค้า</b></th>
                <th style="text-align: right;padding: 5px;border: 1px solid grey;"><b>ค่าเบิ้ลงาน</b></th>
                <th style="text-align: right;padding: 5px;border: 1px solid grey;"><b>ขึ้นงานวันอาทิตย์</b></th>
                <th style="text-align: right;padding: 5px;border: 1px solid grey;"><b>ขึ้นงานข้ามฝั่ง</b></th>
                <th style="text-align: right;padding: 5px;border: 1px solid grey;"><b>ค่าค้างคืน</b></th>
                <th style="text-align: right;padding: 5px;border: 1px solid grey;"><b>ค่าเบี้ยขยัน</b></th>
                <th style="text-align: right;padding: 5px;border: 1px solid grey;"><b>พิเศษอื่นๆ</b></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sum_general = 0;
            $sum_special = 0;
            $sum_towing = 0;
            $sum_cover = 0;
            $sum_warehouse = 0;
            $sum_delivery2 = 0;
            $sum_double = 0;
            $sum_sunday = 0;
            $sum_rangsit = 0;
            $sum_overnight = 0;
            $sum_diligence = 0;
            $sum_other = 0;
            $sum_total = 0;
            $sum_traffic_fine = 0;

            $test_price = 0;
            $damage_price = 0;
            $deduct_other_price = 0;
            $other_amt_deduct = 0;

            $cost_living_price = 0;
            if($search_car_id != null && $search_emp_id == null){
                $cost_living_price = \backend\models\Employee::findCostLivingPrice($search_car_id);
            }else if($search_car_id == null && $search_emp_id != null){
                $cost_living_price = \backend\models\Employee::findCostLivingPriceByDriver($search_emp_id);
            }

            $social_price = \backend\models\Company::findCompanySocialPer($emp_company_id);
            $social_base_price = \backend\models\Company::findSocialbasePrice($emp_company_id);
            $deduct_total = 0;

            if ($social_per != '' || $social_per != null || $social_per != 0) {
                $social_price = $social_per;
            }
            ?>
            <?php if ($model_line != null): ?>
                <?php foreach ($model_line as $value): ?>
                    <?php
                    $val_general = $value->labour_price_general ?: ($value->work_labour_price ?: 0);
                    $val_special = $value->labour_price_special ?: 0;
                    $val_towing = $value->towing_price ?: 0;
                    $val_cover = $value->cover_sheet_price ?: 0;
                    $val_warehouse = $value->warehouse_plus_price ?: 0;
                    $val_delivery2 = $value->delivery_2_cus_price ?: 0;
                    $val_double = $value->work_double_price ?: 0;
                    $val_sunday = $value->sunday_price ?: 0;
                    $val_rangsit = $value->rangsit_price ?: 0;
                    $val_overnight = $value->overnight_price ?: 0;
                    $val_diligence = $value->diligence_price ?: 0;
                    $val_other = $value->work_other_price ?: 0;

                    $sum_general += $val_general;
                    $sum_special += $val_special;
                    $sum_towing += $val_towing;
                    $sum_cover += $val_cover;
                    $sum_warehouse += $val_warehouse;
                    $sum_delivery2 += $val_delivery2;
                    $sum_double += $val_double;
                    $sum_sunday += $val_sunday;
                    $sum_rangsit += $val_rangsit;
                    $sum_overnight += $val_overnight;
                    $sum_diligence += $val_diligence;
                    $sum_other += $val_other;
                    
                    $sum_traffic_fine += $value->traffic_fine_price ?: 0;
                    $test_price += $value->test_price ?: 0;
                    $damage_price += $value->damaged_price ?: 0;
                    $deduct_other_price += $value->deduct_other_price ?: 0;
                    $other_amt_deduct += $value->other_amt ?: 0;

                    $line_total = $val_general + $val_special + $val_towing + $val_cover + $val_warehouse + $val_delivery2 + $val_double + $val_sunday + $val_rangsit + $val_overnight + $val_diligence + $val_other;
                    $sum_total += $line_total;
                    ?>
                    <tr>
                        <td style="border: 1px solid grey;padding: 5px;text-align: center;"><?= date('d-m-Y', strtotime($value->work_queue_date)) ?></td>
                        <td style="border: 1px solid grey;padding: 5px;"><?= \backend\models\Customer::findCusName($value->customer_id) ?></td>
                        <td style="border: 1px solid grey;padding: 5px;text-align: center;"><?= \backend\models\Customer::findWorkTypeByCustomerid($value->customer_id) ?></td>
                        <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($val_general, 2) ?></td>
                        <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($val_special, 2) ?></td>
                        <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($val_towing, 2) ?></td>
                        <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($val_cover, 2) ?></td>
                        <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($val_warehouse, 2) ?></td>
                        <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($val_delivery2, 2) ?></td>
                        <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($val_double, 2) ?></td>
                        <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($val_sunday, 2) ?></td>
                        <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($val_rangsit, 2) ?></td>
                        <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($val_overnight, 2) ?></td>
                        <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($val_diligence, 2) ?></td>
                        <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($val_other, 2) ?></td>
                        <td style="border: 1px solid grey;padding: 5px;text-align: right;"><?= number_format($line_total, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php
            $total_gross_for_social = $sum_general + $sum_special + $cost_living_price;
            $base_deduct = (($social_base_price * $social_price)/100);
            if($total_gross_for_social >= $social_base_price){
                $deduct_total = $base_deduct;
            }else{
                $deduct_total = ($total_gross_for_social * $social_price / 100);
            }

            $sum_group1 = $sum_general + $sum_special;
            $sum_group2 = $sum_towing + $sum_cover + $sum_warehouse + $sum_delivery2 + $sum_double;
            $sum_group3 = $sum_sunday + $sum_rangsit + $sum_overnight + $sum_diligence;
            $sum_group4 = $sum_other;
            $grand_total_income = $sum_total + $cost_living_price;
            $grand_total_net = $grand_total_income - $deduct_total - $test_price - $damage_price - ($fine_employee_amount + $sum_traffic_fine) - $deduct_other_price - $other_amt_deduct;
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" style="border: 1px solid grey;padding: 5px;text-align: right;"><b>รวม</b></td>
                <td style="border: 1px solid grey;padding: 5px;text-align: right;"><b><?= number_format($sum_general, 2) ?></b></td>
                <td style="border: 1px solid grey;padding: 5px;text-align: right;"><b><?= number_format($sum_special, 2) ?></b></td>
                <td style="border: 1px solid grey;padding: 5px;text-align: right;"><b><?= number_format($sum_towing, 2) ?></b></td>
                <td style="border: 1px solid grey;padding: 5px;text-align: right;"><b><?= number_format($sum_cover, 2) ?></b></td>
                <td style="border: 1px solid grey;padding: 5px;text-align: right;"><b><?= number_format($sum_warehouse, 2) ?></b></td>
                <td style="border: 1px solid grey;padding: 5px;text-align: right;"><b><?= number_format($sum_delivery2, 2) ?></b></td>
                <td style="border: 1px solid grey;padding: 5px;text-align: right;"><b><?= number_format($sum_double, 2) ?></b></td>
                <td style="border: 1px solid grey;padding: 5px;text-align: right;"><b><?= number_format($sum_sunday, 2) ?></b></td>
                <td style="border: 1px solid grey;padding: 5px;text-align: right;"><b><?= number_format($sum_rangsit, 2) ?></b></td>
                <td style="border: 1px solid grey;padding: 5px;text-align: right;"><b><?= number_format($sum_overnight, 2) ?></b></td>
                <td style="border: 1px solid grey;padding: 5px;text-align: right;"><b><?= number_format($sum_diligence, 2) ?></b></td>
                <td style="border: 1px solid grey;padding: 5px;text-align: right;"><b><?= number_format($sum_other, 2) ?></b></td>
                <td style="border: 1px solid grey;padding: 5px;text-align: right;"><b><?= number_format($sum_total, 2) ?></b></td>
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
                <td style="padding-left: 10px;">เงินเดือน/ค่าครองชีพ</td>
                <td></td>
                <td style="text-align: right;padding: 5px;"><?= number_format($cost_living_price, 2) ?></td>
                <td style="text-align: center;padding: 5px;">บาท</td>
                <td style="padding-left: 10px;">ค่าประกันสังคม <?= $social_price . ' %' ?></td>
                <td style="text-align: right;padding: 5px;"><?= number_format($deduct_total, 2) ?></td>
                <td style="text-align: center;padding: 5px;">บาท</td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-left: 10px;">รายได้กลุ่ม 1</td>
                <td></td>
                <td style="text-align: right;padding: 5px;"><?= number_format($sum_group1, 2) ?></td>
                <td style="text-align: center;padding: 5px;">บาท</td>
                <td style="padding-left: 10px;">เงินยืมทดรอง</td>
                <td style="text-align: right;padding: 5px;"><?= number_format($test_price, 2) ?></td>
                <td style="text-align: center;padding: 5px;">บาท</td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-left: 10px;">รายได้กลุ่ม 2</td>
                <td></td>
                <td style="text-align: right;padding: 5px;"><?= number_format($sum_group2, 2) ?></td>
                <td style="text-align: center;padding: 5px;">บาท</td>
                <td style="padding-left: 10px;">ค่าประกันสินค้าเสียหาย</td>
                <td style="text-align: right;padding: 5px;"><?= number_format($damage_price, 2) ?></td>
                <td style="text-align: center;padding: 5px;">บาท</td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-left: 10px;">รายได้กลุ่ม 3</td>
                <td></td>
                <td style="text-align: right;padding: 5px;"><?= number_format($sum_group3, 2) ?></td>
                <td style="text-align: center;padding: 5px;">บาท</td>
                <td style="padding-left: 10px">ค่าปรับจราจร</td>
                <td style="text-align: right;padding: 5px;"><?= number_format($fine_employee_amount + $sum_traffic_fine, 2) ?></td>
                <td style="text-align: center;padding: 5px;">บาท</td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-left: 10px;">รายได้กลุ่ม 4</td>
                <td></td>
                <td style="text-align: right;padding: 5px;"><?= number_format($sum_group4, 2) ?></td>
                <td style="text-align: center;padding: 5px;">บาท</td>
                <td style="padding-left: 10px">หักอื่นๆ</td>
                <td style="text-align: right;padding: 5px;"><?= number_format($deduct_other_price, 2) ?></td>
                <td style="text-align: center;padding: 5px;">บาท</td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-left: 10px;"></td>
                <td></td>
                <td style="text-align: right;padding: 5px;"></td>
                <td style="text-align: center;padding: 5px;"></td>
                <td style="padding-left: 10px">อื่นๆ</td>
                <td style="text-align: right;padding: 5px;"><?= number_format($other_amt_deduct, 2) ?></td>
                <td style="text-align: center;padding: 5px;">บาท</td>
            </tr>
            <tr>
                <td></td>
                <td><b>รวมรายได้</b></td>
                <td></td>
                <td style="text-align: right;padding: 5px;">
                    <b><u><?= number_format($grand_total_income, 2) ?></u></b></td>
                <td style="text-align: center;padding: 5px;">บาท</td>
                <td><b>คงเหลือสุทธิ</b></td>
                <td style="text-align: right;padding: 5px;">
                    <b><u><?= number_format($grand_total_net, 2) ?></u></b>
                </td>
                <td style="text-align: center;padding: 5px;">บาท</td>
            </tr>
        </table>

        <br>
        <br>
        <table style="width: 100%">
            <tr>
                <td style="text-align: center;">ลงชื่อ
                    .........................................................................................
                </td>
                <td style="text-align: center;">ลงชื่อ
                    .........................................................................................
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">พขร.</td>
                <td style="text-align: center;">ผู้จัดการ</td>
            </tr>
        </table>

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
