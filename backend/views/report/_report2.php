<?php

use kartik\date\DatePicker;

$this->title = 'รายงานสรุปน้ำมันแยกคัน';

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
    }
    
    #print-area {
        width: 100%;
        max-width: none;
    }
    
    #table-data {
        width: 100% !important;
        font-size: 11px;
        border-collapse: collapse;
    }
    
    #table-data th,
    #table-data td {
        padding: 3px 5px;
        border: 1px solid #000;
        font-size: 10px;
        white-space: nowrap;
    }
    
    #table-data th {
        background-color: #f5f5f5 !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
    
    /* ซ่อนปุ่มและฟอร์มตอนพิมพ์ */
    form, .btn, .row:last-child {
        display: none !important;
    }
    
    /* ปรับให้ตารางอยู่กึ่งกลาง */
    .container, .container-fluid {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 0;
    }
    
    /* ปรับขนาดคอลัมน์ให้พอดี */
    #table-data th:first-child,
    #table-data td:first-child {
        width: 8%;
    }
    
    #table-data th:nth-child(2),
    #table-data td:nth-child(2) {
        width: 10%;
    }
    
    #table-data th:nth-child(3),
    #table-data td:nth-child(3),
    #table-data th:nth-child(4),
    #table-data td:nth-child(4) {
        width: 12%;
    }
    
    /* คอลัมน์ตัวเลข */
    #table-data th:nth-child(n+5),
    #table-data td:nth-child(n+5) {
        width: 6%;
        text-align: right;
    }
}

/* CSS สำหรับหน้าจอปกติ */
@media screen {
    #table-data {
        font-size: 14px;
    }
}
");

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

if($car_search != null){
    $model = \backend\models\Workqueue::find()->innerJoin('car','work_queue.car_id = car.id')
        ->where(['like', 'car.plate_no', $car_search])->orderBy(['id' => SORT_ASC])
        ->andFilterWhere(['AND',['>=','date(work_queue_date)',$find_date],['<=','date(work_queue_date)',$find_to_date]])
        ->all();
}

?>
    <form action="<?=\yii\helpers\Url::to(['report/report2'],true)?>" method="post">
        <div class="row">
            <div class="col-lg-6">
                <div class="input-group">
                    <input type="text" class="form-control" name="car_search" placeholder="เลขทะเบียน" value="<?=$car_search?>">
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
                    <button class="btn btn-info">ค้นหา</button>
                </div>
            </div>
        </div>
    </form>
    <br/>
    <div id="print-area">
        <!-- เพิ่มหัวรายงานสำหรับการพิมพ์ -->
        <div class="print-header" style="text-align: center; margin-bottom: 20px;">
            <h3 style="margin: 0;">รายงานสรุปน้ำมันแยกคัน</h3>
            <p style="margin: 5px 0;">ช่วงวันที่: <?= $display_date ?> ถึง <?= $display_to_date ?></p>
            <?php if($car_search): ?>
                <p style="margin: 5px 0;">ทะเบียน: <?= $car_search ?></p>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <table id="table-data" class="table table-bordered" style="overflow: scroll">
                    <thead>
                    <tr>
                        <th rowspan="2" style="text-align: center;vertical-align: middle;">วันที่</th>
                        <th rowspan="2" style="text-align: center;vertical-align: middle;">ทะเบียน</th>
                        <th rowspan="2" style="text-align: center;vertical-align: middle;">ชื่อพนักงานขับรถ</th>
                        <th rowspan="2" style="text-align: center;vertical-align: middle;">ชื่อลูกค้า</th>
                        <th colspan="2" style="text-align: center;">ปั้มใน</th>
                        <th colspan="2" style="text-align: center;">ปั๊มนอก</th>
                        <th colspan="2" style="text-align: center;">ปั๊มนอก2</th>
                        <th colspan="2" style="text-align: center;">ปั๊มนอก3</th>
                        <th colspan="2" style="text-align: center;">รวมน้ำมันทั้งหมด</th>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><b>จำนวนลิตร</b></td>
                        <td style="text-align: center;"><b>จำนวนบาท</b></td>
                        <td style="text-align: center;"><b>จำนวนลิตร</b></td>
                        <td style="text-align: center;"><b>จำนวนบาท</b></td>
                        <td style="text-align: center;"><b>จำนวนลิตร</b></td>
                        <td style="text-align: center;"><b>จำนวนบาท</b></td>
                        <td style="text-align: center;"><b>จำนวนลิตร</b></td>
                        <td style="text-align: center;"><b>จำนวนบาท</b></td>
                        <td style="text-align: center;"><b>จำนวนลิตร</b></td>
                        <td style="text-align: center;"><b>จำนวนบาท</b></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $total_lite_all = 0;
                    $total_lite_amount = 0;
                    $total_out_lite_all = 0;
                    $total_out_lite_amount = 0;
                    $total_out_lite_all_2 = 0;
                    $total_out_lite_amount_2 = 0;
                    $total_out_lite_all_3 = 0;
                    $total_out_lite_amount_3 = 0;

                    $lint_total_lite_all = 0;
                    $lint_total_price_amount = 0;

                    ?>
                    <?php if ($model): ?>

                        <?php foreach ($model as $value): ?>
                            <?php
                            $line_litre = ($value->total_lite + $value->total_out_lite + $value->total_out_lite_2 + $value->total_out_lite_3);
                            $line_oil_amount = (($value->total_lite * $value->oil_daily_price) + ($value->total_out_lite * $value->oil_out_price) + ($value->total_out_lite_2 * $value->oil_out_price_2) + ($value->total_out_lite_3 * $value->oil_out_price_3));

                            $total_lite_all += $value->total_lite;
                            $total_lite_amount += ($value->total_lite * $value->oil_daily_price);
                            $total_out_lite_all += $value->total_out_lite;
                            $total_out_lite_amount += ($value->total_out_lite * $value->oil_out_price);

                            $total_out_lite_all_2 += $value->total_out_lite_2;
                            $total_out_lite_amount_2 += ($value->total_out_lite_2 * $value->oil_out_price_2);

                            $total_out_lite_all_3 += $value->total_out_lite_3;
                            $total_out_lite_amount_3 += ($value->total_out_lite_3 * $value->oil_out_price_3);

                            $lint_total_lite_all += $line_litre;
                            $lint_total_price_amount += $line_oil_amount;
                            ?>
                            <tr>
                                <td style="text-align: center;"><?= date('d-m-Y', strtotime($value->work_queue_date)) ?></td>
                                <td><?= \backend\models\Car::getPlateno($value->car_id) ?></td>
                                <td><?= \backend\models\Employee::findFullName($value->emp_assign) ?></td>
                                <td><?= \backend\models\Customer::findCusName($value->customer_id) ?></td>
                                <td style="text-align: right;"><?= number_format($value->total_lite, 2) ?></td>
                                <td style="text-align: right;"><?= number_format(($value->total_lite * $value->oil_daily_price), 2) ?></td>
                                <td style="text-align: right;"><?= number_format($value->total_out_lite, 2) ?></td>
                                <td style="text-align: right;"><?= number_format(( $value->total_out_lite * $value->oil_out_price), 2) ?></td>
                                <td style="text-align: right;"><?= number_format($value->total_out_lite_2, 2) ?></td>
                                <td style="text-align: right;"><?= number_format(( $value->total_out_lite_2 * $value->oil_out_price_2), 2) ?></td>
                                <td style="text-align: right;"><?= number_format($value->total_out_lite_3, 2) ?></td>
                                <td style="text-align: right;"><?= number_format(( $value->total_out_lite_3 * $value->oil_out_price_3), 2) ?></td>
                                <td style="text-align: right;"><?= number_format($line_litre, 2) ?></td>
                                <td style="text-align: right;"><?= number_format($line_oil_amount, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                    <tbody>
                    <tr style="font-weight: bold; background-color: #f0f0f0;">
                        <td colspan="4" style="text-align: center;"><b>รวมทั้งหมด</b></td>
                        <td style="text-align: right;"><b><?= number_format($total_lite_all, 2) ?></b></td>
                        <td style="text-align: right;"><b><?= number_format($total_lite_amount, 2) ?></b></td>
                        <td style="text-align: right;"><b><?= number_format($total_out_lite_all, 2) ?></b></td>
                        <td style="text-align: right;"><b><?= number_format($total_out_lite_amount, 2) ?></b></td>
                        <td style="text-align: right;"><b><?= number_format($total_out_lite_all_2, 2) ?></b></td>
                        <td style="text-align: right;"><b><?= number_format($total_out_lite_amount_2, 2) ?></b></td>
                        <td style="text-align: right;"><b><?= number_format($total_out_lite_all_3, 2) ?></b></td>
                        <td style="text-align: right;"><b><?= number_format($total_out_lite_amount_3, 2) ?></b></td>
                        <td style="text-align: right;"><b><?= number_format($lint_total_lite_all, 2) ?></b></td>
                        <td style="text-align: right;"><b><?= number_format($lint_total_price_amount, 2) ?></b></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-12">
            <div class="btn btn-default btn-print" onclick="printContent('print-area')">พิมพ์</div>
            <div class="btn btn-info" id="btn-export-excel">Export Excel</div>
        </div>
    </div>

<?php
$this->registerJsFile(\Yii::$app->request->baseUrl . '/js/jquery.table2excel.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$js = <<<JS
$("#btn-export-excel").click(function(){
  $("#table-data").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "รายงานสรุปน้ำมันแยกคัน"
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
            <title>รายงานสรุปน้ำมันแยกคัน</title>
            <style>
                @page {
                    size: A4 landscape;
                    margin: 0.5cm;
                }
                
                body {
                    font-family: 'TH Sarabun New', Arial, sans-serif;
                    font-size: 12px;
                    margin: 0;
                    padding: 0;
                    line-height: 1.2;
                }
                
                .print-header h3 {
                    font-size: 18px;
                    font-weight: bold;
                    margin: 0 0 10px 0;
                }
                
                .print-header p {
                    font-size: 14px;
                    margin: 5px 0;
                }
                
                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 11px;
                    margin-top: 10px;
                }
                
                th, td {
                    border: 1px solid #000;
                    padding: 3px 5px;
                    font-size: 10px;
                    white-space: nowrap;
                }
                
                th {
                    background-color: #f5f5f5;
                    font-weight: bold;
                    text-align: center;
                }
                
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                
                /* ปรับขนาดคอลัมน์ */
                th:first-child, td:first-child { width: 8%; }
                th:nth-child(2), td:nth-child(2) { width: 10%; }
                th:nth-child(3), td:nth-child(3),
                th:nth-child(4), td:nth-child(4) { width: 12%; }
                th:nth-child(n+5), td:nth-child(n+5) { 
                    width: 6%; 
                    text-align: right;
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