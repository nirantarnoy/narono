<?php

use miloschuman\highcharts\Highcharts;

$year = [];


for ($i = 2022; $i <= date('Y'); $i++) {
    array_push($year, ['id' => $i, 'name' => $i]);
}

$car_type_data = \backend\models\CarType::find()->where(['status' => 1])->all();
$model_customer = \backend\models\Customer::find()->where(['status' => 1])->all();

$table_data = [];
$model = null;

$find_year_data = '';
if($find_year != null){
    $loop_num = 0;
    for($i = 0; $i <= count($find_year)-1; $i++){
        if($loop_num == count($find_year)-1){
            $find_year_data.=$find_year[$i];
        }else{
            $find_year_data.=$find_year[$i].',';
        }
        $loop_num+=1;
    }
}

if ($find_customer_id != null) {
    $sql = "SELECT t1.work_queue_no,t1.customer_id,t4.name as dropoff_name,t1.work_queue_date,t2.plate_no,t5.name as customer_name";
    $sql .= " FROM work_queue as t1 inner join car as t2 on t1.car_id = t2.id inner join work_queue_dropoff as t3 on t1.id = t3.work_queue_id inner join dropoff_place as t4 on t3.dropoff_id = t4.id inner join customer as t5 on t1.customer_id = t5.id";
    $sql .= " WHERE t1.customer_id =" . $find_customer_id;
    if ($find_year_data != '') {
        $sql .= " AND year(t1.work_queue_date) in(" . $find_year_data.")";
    }
    if ($car_type_id != null) {
        $sql .= " AND t2.car_type_id=" . $car_type_id;
    }

    $sql .= " GROUP BY t1.customer_id,t1.work_queue_no";

    $query = \Yii::$app->db->createCommand($sql);
    $model = $query->queryAll();
    if ($model) {
        for ($i = 0; $i <= count($model) - 1; $i++) {
            array_push($table_data, [
                'work_queue_no' => $model[$i]['work_queue_no'],
                'customer_id' => $model[$i]['customer_id'],
                'dropoff_name' => $model[$i]['dropoff_name'],
                'plate_no' => $model[$i]['plate_no'],
                'customer_name' => $model[$i]['customer_name'],
                'trans_date' => date('d-m-Y', strtotime($model[$i]['work_queue_date']))]);
        }
    }
}

$m_data_gharp = [];
$m_data = [['id' => 1, 'name' => 'มกราคม'], ['id' => 2, 'name' => 'กุมภาพันธ์'], ['id' => 3, 'name' => 'มีนาคม'], ['id' => 4, 'name' => 'เมษายน'], ['id' => 5, 'name' => 'พฤษภาคม'], ['id' => 6, 'name' => 'มิถุนายน'], ['id' => 7, 'name' => 'กรกฎาคม'], ['id' => 8, 'name' => 'สิงหาคม'], ['id' => 9, 'name' => 'กันยายน'], ['id' => 10, 'name' => 'ตุลาคม'], ['id' => 11, 'name' => 'พฤศจิกายน'], ['id' => 12, 'name' => 'ธันวาคม']];
$y_data = [];
$total = [];
$total_for_gharp = [];


for ($k = 0; $k <= count($m_data) - 1; $k++) {
    array_push($m_data_gharp, $m_data[$k]['name']);
}
$m1 = [];

for ($ix = 0; $ix <= count($m_data) - 1; $ix++) {
    $line_x = getAmount($m_data[$ix]['id'], $find_customer_id, $find_year, $car_type_id);
    //echo $line_x;return;
    array_push($m1, (float)$line_x);
}
//    print_r($m1);
array_push($total_for_gharp, ['name' => $find_year, 'data' => $m1]);

$data_series = $total_for_gharp;


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
            <?php
            echo \kartik\select2\Select2::widget([
                'name' => 'find_year',
                'data' => \yii\helpers\ArrayHelper::map($year, 'id', 'name'),
                'options' => [
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ])
            ?>
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
                    <th style="text-align: center;">เลขที่</th>
                    <th style="text-align: center;">วันที่</th>
                    <th style="text-align: center;">ทะเบียน</th>
                    <th style="text-align: center;">ลูกค้า</th>

                </tr>
                </thead>
                <tbody>
                <?php if ($table_data != null): ?>
                    <?php for ($k = 0; $k <= count($table_data) - 1; $k++): ?>
                        <tr>
                            <td style="text-align: left;width: 20%;"><?= $table_data[$k]['dropoff_name'] ?></td>
                            <td style="text-align: center;"><?= $table_data[$k]['work_queue_no'] ?></td>
                            <td style="text-align: center;"><?= $table_data[$k]['trans_date'] ?></td>
                            <td style="text-align: center;"><?= $table_data[$k]['plate_no'] ?></td>
                            <td style="text-align: left;"><?= $table_data[$k]['customer_name'] ?></td>
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
    <div class="col-lg-12">
        <?php
        echo Highcharts::widget([
            'options' => [
                'title' => ['text' => 'กราฟ'],
                'xAxis' => [
                    'categories' => $m_data_gharp
                ],
                'yAxis' => [
                    'title' => ['text' => 'เที่ยว']
                ],
                'series' => $data_series
            ]
        ]);
        ?>
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

function getAmount($m, $find_customer_id, $find_year, $car_type_id)
{
    $cnt = 0;
    $sql = "SELECT count(t1.customer_id) as cnt";
    $sql .= " FROM work_queue as t1 inner join car as t2 on t1.car_id = t2.id";
    $sql .= " WHERE t1.id > 0";
    if ($find_customer_id != null) {
        $sql .= " AND t1.customer_id =" . $find_customer_id;
    }
//    if ($find_year != null) {
//        $sql .= " AND year(t1.work_queue_date)=" . $find_year;
//    }
    if ($m != null) {
        $sql .= " AND month(t1.work_queue_date)=" . $m;
    }
    if ($car_type_id != null) {
        $sql .= " AND t2.car_type_id=" . $car_type_id;
    }

    $sql .= " GROUP BY t1.customer_id, month(t1.work_queue_date)";
    $sql .= " ORDER BY month(t1.work_queue_date) asc";

    $query = \Yii::$app->db->createCommand($sql);
    $model = $query->queryAll();
    if ($model) {
        for ($i = 0; $i <= count($model) - 1; $i++) {
            $cnt = $model[$i]['cnt'];
        }
    }
    return $cnt;
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
