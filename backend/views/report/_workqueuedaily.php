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

//$display_date = date('d-m-Y');
//$display_to_date = date('d-m-Y');
//$find_date = date('Y-m-d');
//$find_to_date = date('Y-m-d');
//if ($search_date != null) {
//    $find_date = date('Y-m-d', strtotime($search_date));
//    $display_date = date('d-m-Y', strtotime($search_date));
//}
//if ($search_to_date != null) {
//    $find_to_date = date('Y-m-d', strtotime($search_to_date));
//    $display_to_date = date('d-m-Y', strtotime($search_to_date));
//}
//$model = null;
//
////$model = \backend\models\Workqueue::find()->where(['date(work_queue_date)' => $find_date,'work_option_type_id'=>[1,2]])->all();
//if ($search_car_type != null) {
//    $model_car = \backend\models\Car::find()->select(['id'])->where(['car_type_id' => $search_car_type])->all();
//    $car_list = [];
//    if ($model_car) {
//        foreach ($model_car as $value) {
//            array_push($car_list, $value->id);
//        }
//        //$model = \backend\models\Workqueue::find()->where(['date(work_queue_date)' => $find_date, 'car_id' => $car_list])->all();
//        if ($search_company_id != null) {
//            //$model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'company_id' => $search_company_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//            if ($search_car_id != null && $search_emp_id != null) {
//                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'company_id' => $search_company_id, 'car_id' => $search_car_id, 'emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//            } else if ($search_car_id != null && $search_emp_id == null) {
//                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'company_id' => $search_company_id, 'car_id' => $search_car_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//            } else if ($search_car_id == null && $search_emp_id != null) {
//                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'company_id' => $search_company_id, 'emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//            } else {
//                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'company_id' => $search_company_id,])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//            }
//        } else {
//            //  $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//            if ($search_car_id != null && $search_emp_id != null) {
//                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'car_id' => $search_car_id, 'emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//            } else if ($search_car_id != null && $search_emp_id == null) {
//                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'car_id' => $search_car_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//            } else if ($search_car_id == null && $search_emp_id != null) {
//                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//            } else {
//                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//            }
//
//        }
//
//    }
//
//} else {
//    if ($search_company_id != null) {
//        if ($search_car_id != null && $search_emp_id != null) {
//            $model = \backend\models\Workqueue::find()->where(['company_id' => $search_company_id, 'car_id' => $search_car_id, 'emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//        } else if ($search_car_id != null && $search_emp_id == null) {
//            $model = \backend\models\Workqueue::find()->where(['company_id' => $search_company_id, 'car_id' => $search_car_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//        } else if ($search_car_id == null && $search_emp_id != null) {
//            $model = \backend\models\Workqueue::find()->where(['company_id' => $search_company_id, 'emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//        } else {
//            $model = \backend\models\Workqueue::find()->where(['company_id' => $search_company_id,])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//        }
//
//    } else {
//        //$model = \backend\models\Workqueue::find()->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//        if ($search_car_id != null && $search_emp_id != null) {
//            $model = \backend\models\Workqueue::find()->where(['car_id' => $search_car_id, 'emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//        } else if ($search_car_id != null && $search_emp_id == null) {
//            $model = \backend\models\Workqueue::find()->where(['car_id' => $search_car_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//        } else if ($search_car_id == null && $search_emp_id != null) {
//            $model = \backend\models\Workqueue::find()->where(['emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//        } else {
//            $model = \backend\models\Workqueue::find()->where(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
//        }
//    }
//
//}


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

    $model = null;

// สร้าง query builder และเพิ่ม conditions
    $query = \backend\models\Workqueue::find()
        ->alias('w')
        ->joinWith(['customer c']);
    $conditions = [];

// จัดการ car_type filter
    if ($search_car_type != null) {
        $car_list = \backend\models\Car::find()
            ->select(['id'])
            ->where(['car_type_id' => $search_car_type])
            ->column();

        if ($car_list) {
            // ถ้ามี search_car_id ให้ตรวจสอบว่าอยู่ใน car_list หรือไม่
            if ($search_car_id != null && in_array($search_car_id, $car_list)) {
                $conditions['car_id'] = $search_car_id;
            } else if ($search_car_id == null) {
                $conditions['car_id'] = $car_list;
            }
            // ถ้า search_car_id ไม่อยู่ใน car_list จะไม่เพิ่ม condition (ไม่มีผลลัพธ์)
        }
    } else if ($search_car_id != null) {
        $conditions['car_id'] = $search_car_id;
    }

// เพิ่ม company และ employee filters
    if ($search_company_id != null) $conditions['company_id'] = $search_company_id;
    if ($search_emp_id != null) $conditions['emp_assign'] = $search_emp_id;
    if ($search_work_type_id != null)
    {
        $conditions['c.work_type_id'] = $search_work_type_id;
    }

// Apply conditions และ date range
    $query->andFilterWhere($conditions)
        ->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])
        ->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date]);

    $model = $query->all();


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
                        'data' => \yii\helpers\ArrayHelper::map(\backend\models\CarType::find()->all(), 'id', 'name'),
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
                        'data' => \yii\helpers\ArrayHelper::map(\backend\models\Company::find()->all(), 'id', 'name'),
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
                        'data' => \yii\helpers\ArrayHelper::map(\backend\models\Car::find()->all(), 'id', function ($data) {
                            return $data->plate_no;
                        }),
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
                        'data' => \yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->all(), 'id', function ($data) {
                            return $data->fname . ' ' . $data->lname;
                        }),
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
                        'data' => \yii\helpers\ArrayHelper::map(\backend\models\WorkOptionType::find()->all(), 'id','name'),
                        'value' => $search_work_type_id,
                        'options' => [
                            'placeholder' => '---ประเภทงาน---'
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
                    <?php if($search_car_type): ?>
                        <span>ประเภทรถ: <?= \backend\models\CarType::findOne($search_car_type)->name ?> | </span>
                    <?php endif; ?>
                    <?php if($search_company_id): ?>
                        <span>บริษัท: <?= \backend\models\Company::findOne($search_company_id)->name ?> | </span>
                    <?php endif; ?>
                    <?php if($search_car_id): ?>
                        <span>ทะเบียน: <?= \backend\models\Car::findOne($search_car_id)->plate_no ?> | </span>
                    <?php endif; ?>
                    <?php if($search_emp_id): ?>
                        <span>พขร: <?= \backend\models\Employee::findOne($search_emp_id)->fname . ' ' . \backend\models\Employee::findOne($search_emp_id)->lname ?></span>
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
                <th style="width: 8%;text-align: center;">จำนวนเงิน</th>
                <th>หมายเหตุ</th>
            </tr>
            </thead>
            <tbody>
            <?php $line_num = 0;
            $total_weight = 0;
            $total_line_amount = 0;
            $old_work_id = 0;
            ?>
            <?php if ($model): ?>
                <?php foreach ($model as $value): ?>
                    <?php
                    if($old_work_id != $value->id || $old_work_id == 0){
                        $line_num += 1;
                    }

                    // $line_price_per_ton = getDropoffPriceperton($value->id);
                    //    $line_weight_ton = getDropoffWeightton($value->id);
                    //    $line_dp = getDropoffDP($value->id);

                    ?>
                    <?php
                    $model_line_dp = \common\models\WorkQueueDropoff::find()->where(['work_queue_id' => $value->id])->all();
                    ?>
                    <?php if ($model_line_dp != null): ?>
                        <?php foreach ($model_line_dp as $value_dp): ?>
                            <?php $line_weight_ton_new = getDropoffWeighttonNew($value_dp->id); ?>
                            <?php $line_price_per_ton = getDropoffPricepertonNew($value_dp->id); ?>
                            <?php
                            if ($line_weight_ton_new != null) {
                                //if($line_weight_ton[0]['is_charter'] == 0){
                                $total_weight += $line_weight_ton_new[0]['is_charter'] == 1 ? 0 : ($line_weight_ton_new[0]['weight']);
                                $total_line_amount += ($line_weight_ton_new[0]['weight'] * $line_price_per_ton);
                                //  $total_line_amount += ( $line_price_per_ton);
                                // }
                            } else {
                                $total_weight += 0;
                                $total_line_amount += 10;
                            }
                            ?>
                            <tr>
                                <td style="width: 5%;text-align: center;"><?= $old_work_id != $value->id ? $line_num : '' ?></td>
                                <td style="width: 8%;text-align: center;"><?= $value->work_queue_no ?></td>
                                <td style="width: 8%;text-align: center;"><?= date('d/m/Y', strtotime($value->work_queue_date)) ?></td>
                                <td style="width: 8%;text-align: center;"><?= \backend\models\Car::findName($value->car_id) ?></td>
                                <td style="width: 8%;text-align: center;"><?= \backend\models\Car::findName($value->tail_id) ?></td>
                                <td style="width: 8%;text-align: center;"><?= \backend\models\Employee::findFullName($value->emp_assign) ?></td>
                                <td style="width: 8%;text-align: center;"><?= \backend\models\Customer::findWorkTypeByCustomerid($value->customer_id) ?></td>
                                <td style="width: 8%;text-align: center;"><?= $value_dp->dropoff_no ?></td>
                                <td style="width: 10%;text-align: center;"><?= \backend\models\Customer::findCusName($value->customer_id) ?></td>
                                <td style="width: 8%;text-align: center;"><?= \backend\models\Car::getCartype($value->car_id) ?></td>
                                <td style="width: 8%;text-align: center;"><?= $line_weight_ton_new[0]['is_charter'] == 1 ? 'เหมา' : number_format($line_weight_ton_new[0]['weight'], 3) ?></td>
                                <td style="width: 8%;text-align: center;"><?= number_format($line_price_per_ton, 2) ?></td>
                                <td style="width: 8%;text-align: center;"><?= $line_weight_ton_new[0]['is_charter'] == 1 ? number_format($line_price_per_ton, 2) : number_format(($line_weight_ton_new[0]['weight'] * $line_price_per_ton), 2) ?></td>
                                <td><?= $value->go_deduct_reason ?></td>
                            </tr>
                            <?php $old_work_id = $value->id; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php $old_work_id = $value->id; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <tr style="font-weight: bold; background-color: #f0f0f0;">
                <td colspan="10" style="width: 8%;text-align: right;"><b>รวม</b></td>
                <td style="width: 8%;text-align: center;"><b><?= number_format($total_weight, 3) ?></b></td>
                <td style="width: 8%;text-align: center;"><b></b></td>
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