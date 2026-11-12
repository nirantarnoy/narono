<?php

use kartik\date\DatePicker;

// เพิ่ม CSS สำหรับการพิมพ์
$this->registerCss("
@media print {
    @page {
        size: A4 landscape;
        margin: 0.5cm;
    }
    
    body {
        font-size: 12px;
        margin: 0;
        padding: 0;
        line-height: 1.2;
    }
    
    #print-area {
        width: 100%;
        max-width: none;
    }
    
    #table-data {
        width: 100% !important;
        font-size: 10px;
        border-collapse: collapse;
        margin-top: 10px;
    }
    
    #table-data th,
    #table-data td {
        padding: 2px 4px;
        border: 1px solid #000;
        font-size: 9px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    #table-data th {
        background-color: #f5f5f5 !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
        font-weight: bold;
    }
    
    /* ซ่อนฟอร์มและปุ่มตอนพิมพ์ */
    form, .btn, .row:last-child {
        display: none !important;
    }
    
    /* ปรับขนาดคอลัมน์ให้พอดีกับกระดาษ */
    #table-data th:nth-child(1), #table-data td:nth-child(1) { width: 4%; }
    #table-data th:nth-child(2), #table-data td:nth-child(2) { width: 6%; }
    #table-data th:nth-child(3), #table-data td:nth-child(3) { width: 7%; }
    #table-data th:nth-child(4), #table-data td:nth-child(4) { width: 7%; }
    #table-data th:nth-child(5), #table-data td:nth-child(5) { width: 7%; }
    #table-data th:nth-child(6), #table-data td:nth-child(6) { width: 8%; }
    #table-data th:nth-child(7), #table-data td:nth-child(7) { width: 7%; }
    #table-data th:nth-child(8), #table-data td:nth-child(8) { width: 6%; }
    #table-data th:nth-child(9), #table-data td:nth-child(9) { width: 12%; }
    #table-data th:nth-child(10), #table-data td:nth-child(10) { width: 7%; }
    #table-data th:nth-child(11), #table-data td:nth-child(11) { width: 7%; }
    #table-data th:nth-child(12), #table-data td:nth-child(12) { width: 6%; }
    #table-data th:nth-child(13), #table-data td:nth-child(13) { width: 8%; }
    #table-data th:nth-child(14), #table-data td:nth-child(14) { width: 12%; }
    
    /* ปรับหัวรายงาน */
    .print-header h3 {
        font-size: 16px;
        margin: 0 0 5px 0;
    }
    
    .print-header p {
        font-size: 12px;
        margin: 2px 0;
    }
    
    /* ปรับ footer */
    tfoot td {
        font-weight: bold;
        background-color: #f0f0f0 !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
}

/* CSS สำหรับหน้าจอปกติ */
@media screen {
    #table-data {
        font-size: 14px;
    }
}
");

// จัดการ date format
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

    // ตรวจสอบคอลัมน์ warehouse_plus เผื่อกรณี database ยังไม่ได้รัน migration
    $hasWarehousePlus = Yii::$app->db->getTableSchema('work_queue_dropoff')->getColumn('warehouse_plus') !== null;
    $warehousePlusSelect = $hasWarehousePlus ? 'wqd.warehouse_plus' : '0 as warehouse_plus';

    // ดึงข้อมูลแบบ Single Query พร้อม JOIN ตารางที่เกี่ยวข้องเพื่อขจัดปัญหา N+1
    $query = (new \yii\db\Query())
        ->select([
            'w.id as work_queue_id',
            'w.work_queue_no',
            'w.work_queue_date',
            'w.go_deduct_reason',
            'car_head.name as car_head_name',
            'car_tail.name as car_tail_name',
            'emp.fname as emp_fname',
            'emp.lname as emp_lname',
            'wot.name as work_type_name',
            'wqd.id as dropoff_id',
            'wqd.dropoff_no',
            'c.name as customer_name',
            'ct.name as car_type_name',
            'wqd.is_charter',
            'wqd.weight',
            'wqd.price_per_ton',
            new \yii\db\Expression($warehousePlusSelect),
        ])
        ->from('work_queue w')
        ->innerJoin('work_queue_dropoff wqd', 'wqd.work_queue_id = w.id')
        ->leftJoin('car car_head', 'car_head.id = w.car_id')
        ->leftJoin('cartype ct', 'ct.id = car_head.car_type_id')
        ->leftJoin('car car_tail', 'car_tail.id = w.tail_id')
        ->leftJoin('employee emp', 'emp.id = w.emp_assign')
        ->leftJoin('customer c', 'c.id = w.customer_id')
        ->leftJoin('work_option_type wot', 'wot.id = c.work_type_id');

    // Conditions
    if ($search_car_type != null) {
        $query->andWhere(['car_head.car_type_id' => $search_car_type]);
    }
    if ($search_car_id != null) {
        $query->andWhere(['w.car_id' => $search_car_id]);
    }
    if ($search_company_id != null) {
        $query->andWhere(['w.company_id' => $search_company_id]);
    }
    if ($search_emp_id != null) {
        $query->andWhere(['w.emp_assign' => $search_emp_id]);
    }
    if ($search_work_type_id != null) {
        $query->andWhere(['c.work_type_id' => $search_work_type_id]);
    }
    if ($search_dropoff_id != null) {
        $query->andWhere(['wqd.dropoff_id' => $search_dropoff_id]);
    }

    // ช่วงวันที่ใช้ range บนคอลัมน์โดยตรงเพื่อให้ MySQL ใช้ index ได้
    $query->andWhere(['>=', 'w.work_queue_date', $find_date . ' 00:00:00'])
        ->andWhere(['<=', 'w.work_queue_date', $find_to_date . ' 23:59:59'])
        ->orderBy(['w.work_queue_date' => SORT_ASC, 'w.id' => SORT_ASC, 'wqd.id' => SORT_ASC]);

    $model = $query->all();

    // ดึงข้อมูลสำหรับ dropdown แบบ asArray เพื่อประหยัด memory และโหลดเร็วขึ้น
    $carTypeList = \yii\helpers\ArrayHelper::map(\backend\models\CarType::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name');
    $companyList = \yii\helpers\ArrayHelper::map(\backend\models\Company::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name');
    $carList = \yii\helpers\ArrayHelper::map(\backend\models\Car::find()->select(['id', 'plate_no'])->asArray()->all(), 'id', 'plate_no');
    $empList = \yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->select(['id', 'fname', 'lname'])->asArray()->all(), 'id', function ($data) {
        return trim(($data['fname'] ?? '') . ' ' . ($data['lname'] ?? ''));
    });
    $workTypeList = \yii\helpers\ArrayHelper::map(\backend\models\WorkOptionType::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name');
    $dropoffList = \yii\helpers\ArrayHelper::map(\backend\models\DropoffPlace::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name');


    ?>
    <form action="<?= \yii\helpers\Url::to(['report/workqueuedaily'], true) ?>" method="post">
        <div class="row">
            <div class="col-lg-12">
                <label class="form-label">เลือกวันที่</label>
                <div class="input-group">
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
                    <?php
                    echo \kartik\select2\Select2::widget([
                        'name' => 'search_car_type',
                        'data' => $carTypeList,
                        'value' => $search_car_type,
                        'options' => [
                            'placeholder' => '---เลือกประเภทรถ---'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]);
                    echo \kartik\select2\Select2::widget([
                        'name' => 'search_company_id',
                        'data' => $companyList,
                        'value' => $search_company_id,
                        'options' => [
                            'placeholder' => '---เลือกบริษัท---'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]);
                    echo \kartik\select2\Select2::widget([
                        'name' => 'search_car_id',
                        'data' => $carList,
                        'value' => $search_car_id,
                        'options' => [
                            'placeholder' => '---เลือกทะเบียน---'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]);
                    echo \kartik\select2\Select2::widget([
                        'name' => 'search_emp_id',
                        'data' => $empList,
                        'value' => $search_emp_id,
                        'options' => [
                            'placeholder' => '---พขร---'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]);
                    echo \kartik\select2\Select2::widget([
                        'name' => 'search_work_type_id',
                        'data' => $workTypeList,
                        'value' => $search_work_type_id,
                        'options' => [
                            'placeholder' => '---ประเภทงาน---'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]);
                    echo \kartik\select2\Select2::widget([
                        'name' => 'search_dropoff_id',
                        'data' => $dropoffList,
                        'value' => $search_dropoff_id,
                        'options' => [
                            'placeholder' => '---เลือกคลังทั้งหมด---'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]);
                    ?>
                    <button class="btn btn-primary">ค้นหา</button>
                </div>
            </div>
        </div>
    </form>
    <br/>
    <div id="print-area">
        <!-- เพิ่มหัวรายงานสำหรับการพิมพ์ -->
        <div class="print-header" style="text-align: center; margin-bottom: 15px;">
            <h3 style="margin: 0;"><b>รายงานประจำวัน</b></h3>
            <p style="margin: 5px 0;"><b>ตั้งแต่วันที่ <?= date('d/m/Y', strtotime($find_date)); ?>
                    ถึง <?= date('d/m/Y', strtotime($find_to_date)); ?></b></p>

            <!-- แสดงเงื่อนไขการค้นหา -->
            <?php if($search_car_type || $search_company_id || $search_car_id || $search_emp_id): ?>
                <div style="font-size: 12px; margin-top: 5px;">
                    <?php if($search_car_type && isset($carTypeList[$search_car_type])): ?>
                        <span>ประเภทรถ: <?= $carTypeList[$search_car_type] ?> | </span>
                    <?php endif; ?>
                    <?php if($search_company_id && isset($companyList[$search_company_id])): ?>
                        <span>บริษัท: <?= $companyList[$search_company_id] ?> | </span>
                    <?php endif; ?>
                    <?php if($search_car_id && isset($carList[$search_car_id])): ?>
                        <span>ทะเบียน: <?= $carList[$search_car_id] ?> | </span>
                    <?php endif; ?>
                    <?php if($search_emp_id && isset($empList[$search_emp_id])): ?>
                        <span>พขร: <?= $empList[$search_emp_id] ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <table class="table table-bordered" id="table-data">
            <thead>
            <tr>
                <th style="width: 5%;text-align: center;">เที่ยว</th>
                <th style="width: 5%;text-align: center;">เลขที่</th>
                <th style="width: 8%;text-align: center;">วันที่</th>
                <th style="width: 8%;text-align: center;">ทะเบียนหัว</th>
                <th style="width: 8%;text-align: center;">ทะเบียนหาง</th>
                <th style="width: 8%;text-align: center;">ชื่อ พขร.</th>
                <th style="width: 8%;text-align: center;">ประเภทงาน</th>
                <th style="width: 8%;text-align: center;">เลขDP</th>
                <th style="width: 10%;text-align: center;">ลูกค้า</th>
                <th style="width: 8%;text-align: center;">ประเภทรถ</th>
                <th style="width: 8%;text-align: center;">น้ำหนัก(ตัน)</th>
                <th style="width: 8%;text-align: center;">ราคา</th>
                <th style="width: 8%;text-align: center;">ค่าบวกคลัง</th>
                <th style="width: 8%;text-align: center;">จำนวนเงิน</th>
                <th>หมายเหตุ</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            $line_num = 0;
            $total_weight = 0;
            $total_warehouse_plus = 0;
            $total_line_amount = 0;
            $old_work_id = 0;
            ?>
            <?php if (!empty($model)): ?>
                <?php foreach ($model as $row): ?>
                    <?php
                    $is_new_trip = ($old_work_id != $row['work_queue_id']);
                    if ($is_new_trip) {
                        $line_num++;
                    }

                    $is_charter = ((int)($row['is_charter'] ?? 0) === 1);
                    $weight = (float)($row['weight'] ?? 0);
                    $price_per_ton = (float)($row['price_per_ton'] ?? 0);
                    $warehouse_plus = (float)($row['warehouse_plus'] ?? 0);

                    if ($is_charter) {
                        $weight_display = 'เหมา';
                        $line_amount = $price_per_ton + $warehouse_plus;
                    } else {
                        $weight_display = number_format($weight, 3);
                        $line_amount = ($weight * $price_per_ton) + $warehouse_plus;
                        $total_weight += $weight;
                    }

                    $total_warehouse_plus += $warehouse_plus;
                    $total_line_amount += $line_amount;

                    $emp_name = trim(($row['emp_fname'] ?? '') . ' ' . ($row['emp_lname'] ?? ''));
                    $work_date = !empty($row['work_queue_date']) ? date('d/m/Y', strtotime($row['work_queue_date'])) : '';
                    ?>
                    <tr>
                        <td style="width: 5%;text-align: center;"><?= $is_new_trip ? $line_num : '' ?></td>
                        <td style="width: 8%;text-align: center;"><?= $row['work_queue_no'] ?? '' ?></td>
                        <td style="width: 8%;text-align: center;"><?= $work_date ?></td>
                        <td style="width: 8%;text-align: center;"><?= $row['car_head_name'] ?? '' ?></td>
                        <td style="width: 8%;text-align: center;"><?= $row['car_tail_name'] ?? '' ?></td>
                        <td style="width: 8%;text-align: center;"><?= $emp_name ?></td>
                        <td style="width: 8%;text-align: center;"><?= $row['work_type_name'] ?? '' ?></td>
                        <td style="width: 8%;text-align: center;"><?= $row['dropoff_no'] ?? '' ?></td>
                        <td style="width: 10%;text-align: center;"><?= $row['customer_name'] ?? '' ?></td>
                        <td style="width: 8%;text-align: center;"><?= $row['car_type_name'] ?? '' ?></td>
                        <td style="width: 8%;text-align: center;"><?= $weight_display ?></td>
                        <td style="width: 8%;text-align: center;"><?= number_format($price_per_ton, 2) ?></td>
                        <td style="width: 8%;text-align: center;"><?= number_format($warehouse_plus, 2) ?></td>
                        <td style="width: 8%;text-align: center;"><?= number_format($line_amount, 2) ?></td>
                        <td><?= $row['go_deduct_reason'] ?? '' ?></td>
                    </tr>
                    <?php $old_work_id = $row['work_queue_id']; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <tr style="font-weight: bold; background-color: #f0f0f0;">
                <td colspan="10" style="width: 8%;text-align: right;"><b>รวม</b></td>
                <td style="width: 8%;text-align: center;"><b><?= number_format($total_weight, 3) ?></b></td>
                <td style="width: 8%;text-align: center;"><b></b></td>
                <td style="width: 8%;text-align: center;"><b><?= number_format($total_warehouse_plus, 2) ?></b></td>
                <td style="width: 8%;text-align: center;"><b><?= number_format($total_line_amount, 2) ?></b></td>
                <td></td>
            </tr>
            </tfoot>

        </table>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="btn btn-warning btn-print" onclick="printContent('print-area')">พิมพ์</div>
            <div class="btn btn-info" id="btn-export-excel">Export Excel</div>
        </div>
    </div>
<?php

function getDropoffPriceperton($workqueue_id)
{
    $price = 0;
    $model = \common\models\WorkQueueDropoff::find()->where(['work_queue_id' => $workqueue_id])->one();
    if ($model) {
        $price = $model->price_per_ton;
    }
    return $price;
}
function getDropoffPricepertonNew($id)
{
    $price = 0;
    $model = \common\models\WorkQueueDropoff::find()->where(['id' => $id])->one();
    if ($model) {
        $price = $model->price_per_ton;
    }
    return $price;
}

function getDropoffWarehousePlusNew($id)
{
    $warehouse_plus = 0;
    $model = \common\models\WorkQueueDropoff::find()->where(['id' => $id])->one();
    if ($model) {
        $warehouse_plus = $model->warehouse_plus;
    }
    return $warehouse_plus;
}

function getDropoffWeightton($workqueue_id)
{
//    $price = 0;
//    $model = \common\models\WorkQueueDropoff::find()->where(['work_queue_id' => $workqueue_id])->one();
//    if ($model) {
//        if($model->is_charter == 0){
//            $price = $model->weight;
//        }
//    }
//    return $price;
    $data = [];
    $model = \common\models\WorkQueueDropoff::find()->where(['work_queue_id' => $workqueue_id])->one();
    if ($model) {
        if ($model->is_charter == 0 || $model->is_charter == null) {
            array_push($data, ['is_charter' => $model->is_charter, 'weight' => $model->weight]);
        } else {
            array_push($data, ['is_charter' => 1, 'weight' => 1]);
        }
    } else {
        array_push($data, ['is_charter' => 0, 'weight' => 0]);
    }
    return $data;
}

function getDropoffWeighttonNew($id)
{
//    $price = 0;
//    $model = \common\models\WorkQueueDropoff::find()->where(['work_queue_id' => $workqueue_id])->one();
//    if ($model) {
//        if($model->is_charter == 0){
//            $price = $model->weight;
//        }
//    }
//    return $price;
    $data = [];
    $model = \common\models\WorkQueueDropoff::find()->where(['id' => $id])->one();
    if ($model) {
        if ($model->is_charter == 0 || $model->is_charter == null) {
            array_push($data, ['is_charter' => $model->is_charter, 'weight' => $model->weight]);
        } else {
            array_push($data, ['is_charter' => 1, 'weight' => 1]);
        }
    } else {
        array_push($data, ['is_charter' => 0, 'weight' => 0]);
    }
    return $data;
}

function getDropoffDP($workqueue_id)
{
    $dropoff_no = 0;
    $model = \common\models\WorkQueueDropoff::find()->where(['work_queue_id' => $workqueue_id])->one();
    if ($model) {
        $dropoff_no = $model->dropoff_no;
    }
    return $dropoff_no;
}


$this->registerJsFile(\Yii::$app->request->baseUrl . '/js/jquery.table2excel.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$js = <<<JS
$("#btn-export-excel").click(function(){
  $("#table-data").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "รายงานประจำวัน"
  });
});

function printContent(el) {
    // เปิด window ใหม่สำหรับการพิมพ์
    var printWindow = window.open('', '_blank');
    var printcontent = document.getElementById(el).innerHTML;
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>รายงานประจำวัน</title>
            <style>
                @page {
                    size: A4 landscape;
                    margin: 0.4cm;
                }
                
                body {
                    font-family: 'TH Sarabun New', Arial, sans-serif;
                    font-size: 12px;
                    margin: 0;
                    padding: 0;
                    line-height: 1.1;
                }
                
                .print-header h3 {
                    font-size: 16px;
                    font-weight: bold;
                    margin: 0 0 8px 0;
                    text-align: center;
                }
                
                .print-header p {
                    font-size: 12px;
                    margin: 3px 0;
                    text-align: center;
                }
                
                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 9px;
                    margin-top: 8px;
                }
                
                th, td {
                    border: 1px solid #000;
                    padding: 2px 3px;
                    font-size: 8px;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                
                th {
                    background-color: #f5f5f5;
                    font-weight: bold;
                    text-align: center;
                    vertical-align: middle;
                }
                
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                
                /* กำหนดความกว้างคอลัมน์เฉพาะ */
                th:nth-child(1), td:nth-child(1) { width: 4%; text-align: center; }
                th:nth-child(2), td:nth-child(2) { width: 6%; text-align: center; }
                th:nth-child(3), td:nth-child(3) { width: 7%; text-align: center; }
                th:nth-child(4), td:nth-child(4) { width: 7%; text-align: center; }
                th:nth-child(5), td:nth-child(5) { width: 7%; text-align: center; }
                th:nth-child(6), td:nth-child(6) { width: 8%; text-align: center; }
                th:nth-child(7), td:nth-child(7) { width: 7%; text-align: center; }
                th:nth-child(8), td:nth-child(8) { width: 6%; text-align: center; }
                th:nth-child(9), td:nth-child(9) { width: 12%; text-align: center; }
                th:nth-child(10), td:nth-child(10) { width: 7%; text-align: center; }
                th:nth-child(11), td:nth-child(11) { width: 7%; text-align: right; }
                th:nth-child(12), td:nth-child(12) { width: 6%; text-align: right; }
                th:nth-child(13), td:nth-child(13) { width: 8%; text-align: right; }
                th:nth-child(14), td:nth-child(14) { width: 12%; }
                
                /* จัดรูปแบบ footer */
                tfoot td {
                    font-weight: bold;
                    background-color: #f0f0f0;
                }
                
                /* ปรับขนาดฟอนต์ในหัวรายงาน */
                .print-header {
                    margin-bottom: 10px;
                }
                
                .print-header div {
                    font-size: 10px;
                    text-align: center;
                    margin-top: 3px;
                }
            </style>
        </head>
        <body>
            ` + printcontent + `
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    
    // รอให้โหลดเสร็จแล้วค่อยพิมพ์
    printWindow.onload = function() {
        printWindow.print();
        printWindow.close();
    };
}
JS;
$this->registerJs($js, static::POS_END);
?>