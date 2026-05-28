<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

$this->title = 'รายงานค่าเที่ยว/ค่าแรงพนักงานขับรถ';

$date_month = "";
$date_year = "";

if ($from_date != '') {
    $x1 = explode('-', $from_date);
    $x2 = explode('-', $to_date);
    $date_month = \backend\helpers\Thaimonth::getTypeById((int)$x1[1]);
    $date_year = $x1[0] + 543;
} else {
    $date_month = \backend\helpers\Thaimonth::getTypeById((int)date('m'));
    $date_year = date('Y') + 543;
}

$search_company_id = isset($search_company_id) ? $search_company_id : null;
$social_per = isset($social_per) ? $social_per : null;

// Fetch all drivers or selected driver
$query = \backend\models\Employee::find()->where(['status' => 1]);
if ($search_company_id) {
    $query->andWhere(['company_id' => $search_company_id]);
}
if ($search_emp_id) {
    $query->andWhere(['id' => $search_emp_id]);
}
$employees = $query->all();

?>

<div class="row">
    <div class="col-lg-12">
        <form action="<?= \yii\helpers\Url::to(['carsummaryreport/driverwage'], true) ?>" method="post">
            <div class="row">
                <div class="col-lg-2">
                    <label class="form-label">ตั้งแต่วันที่</label>
                    <?php
                    echo \kartik\date\DatePicker::widget([
                        'name' => 'search_from_date',
                        'value' => date('d-m-Y', strtotime($from_date)),
                        'options' => ['placeholder' => 'เลือกวันที่'],
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'autoclose' => true,
                            'todayHighlight' => true
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-lg-2">
                    <label class="form-label">ถึงวันที่</label>
                    <?php
                    echo \kartik\date\DatePicker::widget([
                        'name' => 'search_to_date',
                        'value' => date('d-m-Y', strtotime($to_date)),
                        'options' => ['placeholder' => 'เลือกวันที่'],
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'autoclose' => true,
                            'todayHighlight' => true
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-lg-2">
                    <label class="form-label">บริษัท</label>
                    <?php
                    echo \kartik\select2\Select2::widget([
                        'name' => 'search_company_id',
                        'data' => \yii\helpers\ArrayHelper::map(\backend\models\Company::find()->where(['status' => 1])->all(), 'id', 'name'),
                        'value' => $search_company_id,
                        'options' => [
                            'placeholder' => '---เลือกทั้งหมด---'
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
                    $emp_dropdown_query = \backend\models\Employee::find()->where(['status' => 1]);
                    if ($search_company_id) {
                        $emp_dropdown_query->andWhere(['company_id' => $search_company_id]);
                    }
                    echo \kartik\select2\Select2::widget([
                        'name' => 'search_emp_id',
                        'data' => \yii\helpers\ArrayHelper::map($emp_dropdown_query->all(), 'id', function($data){
                            return $data->fname.' '.$data->lname;
                        }),
                        'value' => $search_emp_id,
                        'options' => [
                            'placeholder' => '---เลือกทั้งหมด---'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-lg-1">
                    <label class="form-label">% ประกันสังคม</label>
                    <input type="number" class="form-control" name="social_per" min="0" value="<?= Html::encode($social_per) ?>">
                </div>
                <div class="col-lg-3">
                    <div style="height: 35px;"></div>
                    <button class="btn btn-sm btn-primary">ค้นหาและคำนวณ</button>
                    <div class="btn btn-sm btn-info" id="btn-export-excel">Export Excel</div>
                    <div class="btn btn-sm btn-default" onclick="printContent('print-area')"><i class="fa fa-print"></i> พิมพ์</div>
                </div>
            </div>
        </form>
    </div>
</div>

<div style="height: 20px;"></div>

<div id="print-area">
    <table style="width: 100%">
        <?php if ($search_company_id): ?>
        <tr>
            <td style="text-align: center;"><h4><b><?= Html::encode(\backend\models\Company::findCompanyName($search_company_id)) ?></b></h4></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td style="text-align: center;"><h4><b>ค่าเที่ยว/ค่าแรงพนักงานขับรถ เดือน <?= $date_month . ' ' . $date_year ?></b></h4></td>
        </tr>
    </table>
    <br>

    <div class="table-responsive">
    <table id="table-data" style="width: 100%; border-collapse: collapse; border: 1px solid grey;" border="1">
        <thead>
            <tr style="background-color: #f5f5f5;">
                <th style="text-align: center; padding: 5px; border: 1px solid grey;">ลำดับ</th>
                <th style="text-align: center; padding: 5px; border: 1px solid grey;">ชื่อ-นามสกุล</th>
                <th style="text-align: center; padding: 5px; border: 1px solid grey;">ค่าครองชีพ<br>(1.00)</th>
                <th style="text-align: center; padding: 5px; border: 1px solid grey;">ค่าเที่ยว<br>(2.00)</th>
                <th style="text-align: center; padding: 5px; border: 1px solid grey;">ยอดรวม<br>(1+2)</th>
                <th style="text-align: center; padding: 5px; border: 1px solid grey;">หักเงินประกันสังคม<br>(4)</th>
                <th style="text-align: center; padding: 5px; border: 1px solid grey;">โอที</th>
                <th style="text-align: center; padding: 5px; border: 1px solid grey;">เงินเบี้ยเลี้ยง</th>
                <th style="text-align: center; padding: 5px; border: 1px solid grey;">หักเงินภาษี ภงด.</th>
                <th style="text-align: center; padding: 5px; border: 1px solid grey;">หักเงินยืมทดรองจ่าย<br>(5)</th>
                <th style="text-align: center; padding: 5px; border: 1px solid grey;">หักค่าปรับจราจร<br>(7)</th>
                <th style="text-align: center; padding: 5px; border: 1px solid grey;">หักเงินประกันของเสียหาย<br>(6)</th>
                <th style="text-align: center; padding: 5px; border: 1px solid grey;">หักค่าสินค้าเสียหาย</th>
                <th style="text-align: center; padding: 5px; border: 1px solid grey;">หักอื่นๆ<br>(8)</th>
                <th style="text-align: center; padding: 5px; border: 1px solid grey;">ยอดสุทธิ</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 0;
            $g_cost_living = 0;
            $g_trip_price = 0;
            $g_total_1_2 = 0;
            $g_social = 0;
            $g_ot = 0;
            $g_allowance = 0;
            $g_tax = 0;
            $g_advance = 0;
            $g_fine = 0;
            $g_damage = 0;
            $g_prod_damage = 0;
            $g_other = 0;
            $g_net = 0;

            foreach ($employees as $emp): 
                // Fetch works for this employee
                $works = \common\models\QueryCarWorkSummary::find()
                    ->select([
                        'query_car_work_summary.*',
                        'work_queue.sunday_price',
                        'work_queue.rangsit_price',
                        'work_queue.diligence_price',
                        'work_queue.delivery_2_cus_price',
                        'work_queue.traffic_fine_price',
                        'work_queue.labour_price_general',
                        'work_queue.labour_price_special',
                        'work_queue.incentive_price',
                        'work_queue.towing_price',
                        'work_queue.work_double_price'
                    ])
                    ->joinWith('workqueue')
                    ->where(['between', 'query_car_work_summary.work_queue_date', $from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->andWhere(['query_car_work_summary.emp_assign' => $emp->id])
                    ->all();
                
                if (count($works) == 0 && !$search_emp_id) continue; // Skip employees with no work if not specifically searched
                
                $i++;
                $cost_living = \backend\models\Employee::findCostLivingPriceByDriver($emp->id) ?: 0;
                
                $trip_price = 0;
                $allowance = 0;
                $advance = 0;
                $fine = 0;
                $damage = 0;
                $other_deduct = 0;
                $sum_general_special = 0;
                
                // Fine from EmployeeFine table
                $fine_employee_amount = \common\models\EmployeeFine::find()
                    ->where(['between', 'trans_date', $from_date . ' 00:00:00', $to_date . ' 23:59:59'])
                    ->andWhere(['emp_id' => $emp->id])
                    ->sum('fine_amount') ?: 0;

                foreach ($works as $w) {
                    $val_general = $w->labour_price_general ?: ($w->work_labour_price ?: 0);
                    $val_special = $w->labour_price_special ?: 0;
                    $val_towing = $w->towing_price ?: 0;
                    $val_cover = $w->cover_sheet_price ?: 0;
                    $val_warehouse = $w->warehouse_plus_price ?: 0;
                    $val_delivery2 = $w->delivery_2_cus_price ?: 0;
                    $val_double = $w->work_double_price ?: 0;
                    $val_other = $w->workqueue ? $w->workqueue->other_price : ($w->work_other_price ?: 0);
                    
                    // Allowances
                    $val_sunday = $w->sunday_price ?: 0;
                    $val_rangsit = $w->rangsit_price ?: 0;
                    $val_overnight = $w->overnight_price ?: 0;
                    $val_diligence = $w->diligence_price ?: 0;

                    $trip_price += ($val_general + $val_special + $val_towing + $val_cover + $val_warehouse + $val_delivery2 + $val_double + $val_other);
                    $allowance += ($val_sunday + $val_rangsit + $val_overnight + $val_diligence);
                    
                    $sum_general_special += ($val_general + $val_special);
                    
                    $advance += $w->test_price ?: 0;
                    $fine += $w->traffic_fine_price ?: 0;
                    $damage += $w->damaged_price ?: 0;
                    $other_deduct += $w->deduct_other_price ?: 0;
                }
                
                $fine += $fine_employee_amount;
                
                $total_1_2 = $cost_living + $trip_price;
                
                // Calculate Social Security based on cost_living + sum_general_special (logic from trip report)
                $social_price_per = \backend\models\Company::findCompanySocialPer($emp->company_id);
                if ($social_per !== '' && $social_per !== null && $social_per != 0) {
                    $social_price_per = $social_per;
                }
                $social_base_price = \backend\models\Company::findSocialbasePrice($emp->company_id);
                $social_deduct = 0;
                
                $social_gross = $cost_living + $sum_general_special;
                if ($social_gross >= $social_base_price && $social_base_price > 0) {
                    $social_deduct = (($social_base_price * $social_price_per) / 100);
                } else if ($social_price_per > 0) {
                    $social_deduct = ($social_gross * $social_price_per / 100);
                }
                
                // Empty placeholders for new columns requested by excel design
                $ot = 0;
                $tax = 0;
                $prod_damage = 0;

                // Net Total Formula from Excel
                $net = $total_1_2 + $ot + $allowance - $social_deduct - $tax - $advance - $fine - $damage - $prod_damage - $other_deduct;

                // Accumulate Grand Totals
                $g_cost_living += $cost_living;
                $g_trip_price += $trip_price;
                $g_total_1_2 += $total_1_2;
                $g_social += $social_deduct;
                $g_ot += $ot;
                $g_allowance += $allowance;
                $g_tax += $tax;
                $g_advance += $advance;
                $g_fine += $fine;
                $g_damage += $damage;
                $g_prod_damage += $prod_damage;
                $g_other += $other_deduct;
                $g_net += $net;
            ?>
            <tr>
                <td style="text-align: center; padding: 5px; border: 1px solid grey;"><?= $i ?></td>
                <td style="padding: 5px; border: 1px solid grey;"><?= $emp->fname . ' ' . $emp->lname ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($cost_living, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($trip_price, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey; font-weight: bold;"><?= number_format($total_1_2, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($social_deduct, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($ot, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($allowance, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($tax, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($advance, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($fine, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($damage, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($prod_damage, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($other_deduct, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey; font-weight: bold;"><?= number_format($net, 2) ?></td>
            </tr>
            <?php endforeach; ?>
            
            <?php if ($i == 0): ?>
            <tr>
                <td colspan="15" style="text-align: center; padding: 20px; border: 1px solid grey;">ไม่พบข้อมูล</td>
            </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #f5f5f5; font-weight: bold;">
                <td colspan="2" style="text-align: center; padding: 5px; border: 1px solid grey;">รวม</td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($g_cost_living, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($g_trip_price, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($g_total_1_2, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($g_social, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($g_ot, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($g_allowance, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($g_tax, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($g_advance, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($g_fine, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($g_damage, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($g_prod_damage, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($g_other, 2) ?></td>
                <td style="text-align: right; padding: 5px; border: 1px solid grey;"><?= number_format($g_net, 2) ?></td>
            </tr>
        </tfoot>
    </table>
    </div>

    <br>
    <div style="font-size: 12px;">
        <p>ยอดรวม คือ ค่าครองชีพ + ค่าเที่ยว</p>
        <p>ยอดคงเหลือสุทธิ คือ ยอดรวม บวกด้วย โอที เงินเบี้ยเลี้ยง ลบด้วย ภาษีเงิน ภงด./เงินยืมทดรอง/ค่าปรับจราจร/เงินประกันของเสียหาย/ค่าสินค้าเสียหาย/หักอื่นๆ/หักเงินประกันสังคม</p>
    </div>
</div>

<?php
$this->registerJsFile(\Yii::$app->request->baseUrl . '/js/jquery.table2excel.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$js = <<<JS
$("#btn-export-excel").click(function(){
  $("#table-data").table2excel({
    exclude: ".noExl",
    name: "Driver Wage Report",
    filename: "DriverWageReport_" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls"
  });
});
function printContent(el) {
    var restorepage = document.body.innerHTML;
    var printcontent = document.getElementById(el).innerHTML;
    document.body.innerHTML = printcontent;
    window.print();
    document.body.innerHTML = restorepage;
    window.location.reload(); // Reload to restore events
}
JS;
$this->registerJs($js, static::POS_END);
?>
