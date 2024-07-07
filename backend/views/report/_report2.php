<?php
$this->title = 'รายงานสรุปน้ำมันแยกคัน';

$model = null;

if($car_search != null){
    $model = \backend\models\Workqueue::find()->innerJoin('car','work_queue.car_id = car.id')->where(['like', 'car.plate_no', $car_search])->orderBy(['id' => SORT_ASC])->all();
}


?>
<form action="<?=\yii\helpers\Url::to(['report/report2'],true)?>" method="post">
    <div class="row">
        <div class="col-lg-3">
            <div class="input-group">
                <input type="text" class="form-control" name="car_search" placeholder="เลขทะเบียน" value="<?=$car_search?>">
                <button class="btn btn-info">ค้นหา</button>
            </div>
        </div>
    </div>
</form>
<br/>
<div id="print-area">
    <div class="row">
        <div class="col-lg-12">
            <table id="table-data" class="table table-bordered">
                <thead>
                <tr>
                    <th rowspan="2" style="text-align: center;vertical-align: middle;">วันที่</th>
                    <th rowspan="2" style="text-align: center;vertical-align: middle;">ทะเบียน</th>
                    <th rowspan="2" style="text-align: center;vertical-align: middle;">ชื่อพนักงานขับรถ</th>
                    <th rowspan="2" style="text-align: center;vertical-align: middle;">ชื่อลูกค้า</th>
                    <th colspan="2" style="text-align: center;">ปั้มใน</th>
                    <th colspan="2" style="text-align: center;">ปั๊มนอก</th>
                    <th colspan="2" style="text-align: center;">รวมน้ำมันทั้งหมด</th>
                </tr>
                <tr>
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

                $lint_total_lite_all = 0;
                $lint_total_price_amount = 0;

                ?>
                <?php if ($model): ?>

                    <?php foreach ($model as $value): ?>
                        <?php
                        $line_litre = ($value->total_lite + $value->total_out_lite);
                        $line_oil_amount = (($value->total_lite * $value->oil_daily_price) + ($value->total_out_lite * $value->oil_out_price));

                        $total_lite_all += $value->total_lite;
                        $total_lite_amount += ($value->total_lite * $value->oil_daily_price);
                        $total_out_lite_all += $value->total_out_lite;
                        $total_out_lite_amount += ($value->total_out_lite * $value->oil_out_price);

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
                            <td style="text-align: right;"><?= number_format($line_litre, 2) ?></td>
                            <td style="text-align: right;"><?= number_format($line_oil_amount, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
                <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right;"><b><?= number_format($total_lite_all, 2) ?></b></td>
                    <td style="text-align: right;"><b><?= number_format($total_lite_amount, 2) ?></b></td>
                    <td style="text-align: right;"><b><?= number_format($total_out_lite_all, 2) ?></b></td>
                    <td style="text-align: right;"><b><?= number_format($total_out_lite_amount, 2) ?></b></td>
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

