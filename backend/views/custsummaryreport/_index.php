<?php
$year = [];

for ($i = 2022; $i <= date('Y'); $i++) {
    array_push($year, $i);
}

$car_type_data = \backend\models\CarType::find()->where(['status' => 1])->all();

$customer_data = [];

$sql = "SELECT t1.customer_id";
$sql .= " FROM work_queue as t1 inner join car as t2 on t1.car_id = t2.id";
$sql .= " WHERE t1.id > 0";
if ($find_year != null) {
    $sql .= " AND year(t1.work_queue_date)=" . $find_year;
}
if ($car_type_id != null) {
    $sql .= " AND t2.car_type_id=" . $car_type_id;
}

$sql .= " GROUP BY t1.customer_id";

$query = \Yii::$app->db->createCommand($sql);
$model = $query->queryAll();
if ($model) {
    for ($i = 0; $i <= count($model) - 1; $i++) {
        array_push($customer_data, $model[$i]['customer_id']);
    }
}

?>

<div class="row">
    <div class="col-lg-12" style="text-align: center;">
        <h3>รายงานเที่ยววิ่งลูกค้า</h3>
    </div>
</div>
<br/>
<form action="index.php?r=custsummaryreport" method="post">
    <div class="row">
        <div class="col-lg-3">
            <label for="">ประจำปี</label>
            <select class="form-control" id="find_year" name="find_year">
                <option value="">ทั้งหมด</option>
                <?php foreach ($year as $y): ?>
                    <option value="<?= $y ?>" <?= ($y == $find_year) ? 'selected' : '' ?>><?= $y ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-lg-3">
            <label for="">ประเภทรถ</label>
            <select class="form-control" id="find_car_type_id" name="find_car_type_id">
                <option value="">ทั้งหมด</option>
                <?php foreach ($car_type_data as $x): ?>
                    <option value="<?= $x->id ?>" <?= ($x->id == $car_type_id) ? 'selected' : '' ?>><?= $x->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-lg-3">
            <button class="btn btn-primary" style="margin-top: 32px;">ค้นหา</button>
        </div>
    </div>
</form>
<br/>
<div id="print-area">
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="text-align: center;width: 20%;">ลูกค้า</th>
                    <th style="text-align: center;">ม.ค.</th>
                    <th style="text-align: center;">ก.พ.</th>
                    <th style="text-align: center;">มี.ค.</th>
                    <th style="text-align: center;">เม.ย.</th>
                    <th style="text-align: center;">พ.ค.</th>
                    <th style="text-align: center;">มิ.ย.</th>
                    <th style="text-align: center;">ก.ค.</th>
                    <th style="text-align: center;">ส.ค.</th>
                    <th style="text-align: center;">ก.ย.</th>
                    <th style="text-align: center;">ต.ค.</th>
                    <th style="text-align: center;">พ.ค.</th>
                    <th style="text-align: center;">ธ.ค.</th>
                    <th style="text-align: center;">รวม</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $line_m1 = 0;
                $line_m2 = 0;
                $line_m3 = 0;
                $line_m4 = 0;
                $line_m5 = 0;
                $line_m6 = 0;
                $line_m7 = 0;
                $line_m8 = 0;
                $line_m9 = 0;
                $line_m10 = 0;
                $line_m11 = 0;
                $line_m12 = 0;

                $total_m1 = 0;
                $total_m2 = 0;
                $total_m3 = 0;
                $total_m4 =0;
                $total_m5 = 0;
                $total_m6 =0;
                $total_m7 =0;
                $total_m8 = 0;
                $total_m9 = 0;
                $total_m10 = 0;
                $total_m11 =0;
                $total_m12 = 0;
                ?>
                <?php if ($customer_data != null): ?>

                    <?php for ($k = 0; $k <= count($customer_data) - 1; $k++): ?>
                        <?php if ($customer_data[$k] == null) continue; ?>
                        <?php $line_all_total = 0; ?>
                        <?php $line_count_data = getLineData($customer_data[$k], $find_year, $car_type_id); ?>
                        <?php // print_r($line_count_data);?>
                         <?php

                        $line_m1 = $line_count_data != null ? (int)$line_count_data[0] : 0;
                        $line_m2 = $line_count_data != null ? (int)$line_count_data[1] : 0;
                        $line_m3 = $line_count_data != null ? (int)$line_count_data[2] : 0;
                        $line_m4 = $line_count_data != null ? (int)$line_count_data[3] : 0;
                        $line_m5 = $line_count_data != null ? (int)$line_count_data[4] : 0;
                        $line_m6 = $line_count_data != null ? (int)$line_count_data[5] : 0;
                        $line_m7 = $line_count_data != null ? (int)$line_count_data[6] : 0;
                        $line_m8 = $line_count_data != null ? (int)$line_count_data[7] : 0;
                        $line_m9 = $line_count_data != null ? (int)$line_count_data[8] : 0;
                        $line_m10 = $line_count_data != null ? (int)$line_count_data[9] : 0;
                        $line_m11 = $line_count_data != null ? (int)$line_count_data[10] : 0;
                        $line_m12 = $line_count_data != null ? (int)$line_count_data[11] : 0;

                        $total_m1 += $line_m1;
                        $total_m2 += $line_m2;
                        $total_m3 += $line_m3;
                        $total_m4 += $line_m4;
                        $total_m5 += $line_m5;
                        $total_m6 += $line_m6;
                        $total_m7 += $line_m7;
                        $total_m8 += $line_m8;
                        $total_m9 += $line_m9;
                        $total_m10 += $line_m10;
                        $total_m11 += $line_m11;
                        $total_m12 += $line_m12;

                        $line_all_total = ($line_m1 + $line_m2 + $line_m3 + $line_m4 +$line_m5 + $line_m6 + $line_m7 + $line_m8 + $line_m9 + $line_m10 + $line_m11 + $line_m12);
                        ?>


                        <tr>
                            <td style="text-align: left;width: 20%;"><?= \backend\models\Customer::findCusName($customer_data[$k]) ?></td>
                            <td style="text-align: center;"><?= number_format($line_m1) ?></td>
                            <td style="text-align: center;"><?= number_format($line_m2) ?></td>
                            <td style="text-align: center;"><?= number_format($line_m3) ?></td>
                            <td style="text-align: center;"><?= number_format($line_m4) ?></td>
                            <td style="text-align: center;"><?= number_format($line_m5) ?></td>
                            <td style="text-align: center;"><?= number_format($line_m6) ?></td>
                            <td style="text-align: center;"><?= number_format($line_m7) ?></td>
                            <td style="text-align: center;"><?= number_format($line_m8) ?></td>
                            <td style="text-align: center;"><?= number_format($line_m9) ?></td>
                            <td style="text-align: center;"><?= number_format($line_m10) ?></td>
                            <td style="text-align: center;"><?= number_format($line_m11) ?></td>
                            <td style="text-align: center;"><?= number_format($line_m12) ?></td>
                            <td style="text-align: center;"><b><?= number_format($line_all_total) ?></b></td>
                        </tr>
                    <?php endfor; ?>
                <?php endif; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td style="text-align: right;width: 20%;"><b>รวม</b></td>
                    <td style="text-align: center;"><b><?= number_format($total_m1) ?></b></td>
                    <td style="text-align: center;"><b><?= number_format($total_m2) ?></b></td>
                    <td style="text-align: center;"><b><?= number_format($total_m3) ?></b></td>
                    <td style="text-align: center;"><b><?= number_format($total_m4) ?></b></td>
                    <td style="text-align: center;"><b><?= number_format($total_m5) ?></b></td>
                    <td style="text-align: center;"><b><?= number_format($total_m6) ?></b></td>
                    <td style="text-align: center;"><b><?= number_format($total_m7) ?></b></td>
                    <td style="text-align: center;"><b><?= number_format($total_m8) ?></b></td>
                    <td style="text-align: center;"><b><?= number_format($total_m9) ?></b></td>
                    <td style="text-align: center;"><b><?= number_format($total_m10) ?></b></td>
                    <td style="text-align: center;"><b><?= number_format($total_m11) ?></b></td>
                    <td style="text-align: center;"><b><?= number_format($total_m12) ?></b></td>
                    <td style="text-align: center;">
                        <b><?= number_format($total_m1 + $total_m2 + $total_m3 + $total_m4 + $total_m5 + $total_m6 + $total_m7 + $total_m8 + $total_m9 + $total_m10 + $total_m11 + $total_m12) ?></b>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-lg-4">
        <div class="btn btn-warning btn-print" onclick="printContent('print-area')">พิมพ์</div>
    </div>
</div>
<?php
function getLineData($customer_id, $find_year, $car_type_id)
{
    $data = [];
    $sql = "SELECT month(t1.work_queue_date) as month,count(t1.customer_id) as cnt";
    $sql .= " FROM work_queue as t1 inner join car as t2 on t1.car_id = t2.id";
    $sql .= " WHERE t1.customer_id =" . $customer_id;
    if ($find_year != null) {
        $sql .= " AND year(t1.work_queue_date)=" . $find_year;
    }
    if ($car_type_id != null) {
        $sql .= " AND t2.car_type_id=" . $car_type_id;
    }

    $sql .= " GROUP BY t1.customer_id, month(t1.work_queue_date)";
    $sql .= " ORDER BY month(t1.work_queue_date) asc ,count(t1.customer_id) desc";
    //$sql .= " ORDER BY count(t1.customer_id) desc";

    $query = \Yii::$app->db->createCommand($sql);
    $model = $query->queryAll();
    if ($model) {
        for ($m = 0; $m <= 11; $m++) {
            $has_data = false;
            for ($i = 0; $i <= count($model) - 1; $i++) {
                if ($m + 1 == (int)$model[$i]['month']) {
                    $data[$m] = $model[$i]['cnt'];
                    $has_data = true;
                }
            }

            if (!$has_data) {
                $data[$m] = 0;
            }
        }

    }

    return $data;
}

?>

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
