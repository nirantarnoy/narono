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
        <div class="col-lg-12" style="text-align: center;">
            <table style="border: none;width: 100%">
                <td style="text-align: right;width: 50%">ประจำปี</td>
                <td style="width: 20%;">
                    <select class="form-control" id="find_year" name="find_year">
                        <option value="">ทั้งหมด</option>
                        <?php foreach ($year as $y): ?>
                            <option value="<?= $y ?>" <?= ($y == $find_year) ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td></td>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="text-align: center;">
            <table style="border: none;width: 100%">
                <td style="text-align: right;width: 50%">ประเภทรถ</td>
                <td style="width: 20%;text-align: left">
                    <select class="form-control" id="find_car_type_id" name="find_car_type_id">
                        <option value="">ทั้งหมด</option>
                        <?php foreach ($car_type_data as $x): ?>
                            <option value="<?= $x->id ?>" <?= ($x->id == $car_type_id) ? 'selected' : '' ?>><?= $x->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td></td>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="text-align: center;">
            <table style="border: none;width: 100%">
                <td style="text-align: right;width: 50%"></td>
                <td style="width: 20%;text-align: left">
                    <button class="btn btn-primary">ค้นหา</button>
                </td>
                <td></td>
            </table>
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
                </tr>
                </thead>
                <tbody>
                <?php if ($customer_data != null): ?>
                    <?php for ($k = 0; $k <= count($customer_data) - 1; $k++): ?>
                        <?php if ($customer_data[$k] == null) continue; ?>
                        <?php $line_count_data = getLineData($customer_data[$k], 2023, 6); ?>
                        <?php // print_r($line_count_data);?>
                        <tr>
                            <td style="text-align: left;width: 20%;"><?= \backend\models\Customer::findCusName($customer_data[$k]) ?></td>
                            <td style="text-align: center;"><?= number_format($line_count_data != null ? $line_count_data[0] : 0) ?></td>
                            <td style="text-align: center;"><?= number_format($line_count_data != null ? $line_count_data[1] : 0) ?></td>
                            <td style="text-align: center;"><?= number_format($line_count_data != null ? $line_count_data[2] : 0) ?></td>
                            <td style="text-align: center;"><?= number_format($line_count_data != null ? $line_count_data[3] : 0) ?></td>
                            <td style="text-align: center;"><?= number_format($line_count_data != null ? $line_count_data[4] : 0) ?></td>
                            <td style="text-align: center;"><?= number_format($line_count_data != null ? $line_count_data[5] : 0) ?></td>
                            <td style="text-align: center;"><?= number_format($line_count_data != null ? $line_count_data[6] : 0) ?></td>
                            <td style="text-align: center;"><?= number_format($line_count_data != null ? $line_count_data[7] : 0) ?></td>
                            <td style="text-align: center;"><?= number_format($line_count_data != null ? $line_count_data[8] : 0) ?></td>
                            <td style="text-align: center;"><?= number_format($line_count_data != null ? $line_count_data[9] : 0) ?></td>
                            <td style="text-align: center;"><?= number_format($line_count_data != null ? $line_count_data[10] : 0) ?></td>
                            <td style="text-align: center;"><?= number_format($line_count_data != null ? $line_count_data[11] : 0) ?></td>
                        </tr>
                    <?php endfor; ?>
                <?php endif; ?>
                </tbody>
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

    $query = \Yii::$app->db->createCommand($sql);
    $model = $query->queryAll();
    if ($model) {
        for ($m = 0; $m <= 11; $m++) {
            for ($i = 0; $i <= count($model) - 1; $i++) {
                if ($m + 1 == $model[$i]['month']) {
                    $data[$m] = $model[$i]['cnt'];
                } else {
                    $data[$m] = 0;
                }
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
