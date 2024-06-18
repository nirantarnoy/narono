<?php
$year = [];

for ($i = 2022; $i <= date('Y'); $i++) {
    array_push($year, $i);
}

$car_type_data = \backend\models\CarType::find()->where(['status' => 1])->all();
$model_customer = \backend\models\Customer::find()->where(['status' => 1])->all();

$customer_data = [];
$model = null;

if($find_customer_id != null){
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
}


?>

<div class="row">
    <div class="col-lg-12" style="text-align: center;">
        <h3>รายงานเที่ยววิ่งระบุลูกค้า</h3>
    </div>
</div>
<br/>
<form action="index.php?r=custdetailreport" method="post">
    <div class="row">
        <div class="col-lg-3">
            <label for="">ลูกค้า</label>
            <select class="form-control" id="find_customer_id" name="find_customer_id">
                <?php foreach ($model_customer as $y): ?>
                    <option value="<?= $y->id ?>" <?= ($y->id == $find_customer_id) ? 'selected' : '' ?>><?= $y->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
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
                    <th style="text-align: center;width: 20%;">สถานที่</th>
                    <th style="text-align: center;">วันที่</th>
                    <th style="text-align: center;">ทะเบียน</th>
                    <th style="text-align: center;">ลูกค้า</th>

                </tr>
                </thead>
                <tbody>
                <?php if ($customer_data != null): ?>
                    <?php for ($k = 0; $k <= count($customer_data) - 1; $k++): ?>
                        <?php if ($customer_data[$k] == null) continue; ?>
                        <?php $line_count_data = getLineData($customer_data[$k], $find_year, $car_type_id); ?>
                        <?php // print_r($line_count_data);?>
                        <tr>
                            <td style="text-align: left;width: 20%;"><?= \backend\models\Customer::findCusName($customer_data[$k]) ?></td>
                            <td style="text-align: center;"><?= number_format($line_count_data != null ? $line_count_data[0] : 0) ?></td>
                            <td style="text-align: center;"><?= number_format($line_count_data != null ? $line_count_data[1] : 0) ?></td>
                            <td style="text-align: center;"><?= number_format($line_count_data != null ? $line_count_data[2] : 0) ?></td>
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
    $sql .= " ORDER BY month(t1.work_queue_date) asc";

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
