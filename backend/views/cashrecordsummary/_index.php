<?php

use miloschuman\highcharts\Highcharts;

$m_data_gharp = [];
$m_data = [['id' => 1, 'name' => 'มกราคม'], ['id' => 2, 'name' => 'กุมภาพันธ์'], ['id' => 3, 'name' => 'มีนาคม'], ['id' => 4, 'name' => 'เมษายน'], ['id' => 5, 'name' => 'พฤษภาคม'], ['id' => 6, 'name' => 'มิถุนายน'], ['id' => 7, 'name' => 'กรกฎาคม'], ['id' => 8, 'name' => 'สิงหาคม'], ['id' => 9, 'name' => 'กันยายน'], ['id' => 10, 'name' => 'ตุลาคม'], ['id' => 11, 'name' => 'พฤศจิกายน'], ['id' => 12, 'name' => 'ธันวาคม']];
$y_data = [];
$total = [];
$total_for_gharp = [];

$sql = "SELECT year(trans_date) as year  from cash_record";
$sql .= " GROUP BY year(trans_date)";
$sql .= " ORDER BY year(trans_date) asc";
$sql .= " LIMIT 2";
$query = \Yii::$app->db->createCommand($sql);
$model = $query->queryAll();
if ($model) {
    for ($i = 0; $i <= count($model) - 1; $i++) {
        array_push($y_data, $model[$i]['year']);
    }
}

for ($k = 0; $k <= count($m_data) - 1; $k++) {
    array_push($m_data_gharp, $m_data[$k]['name']);
}
for ($xx = 0; $xx <= count($y_data) - 1; $xx++) {
    $m1 = [];

    for ($ix = 0; $ix <= count($m_data) - 1; $ix++) {
        $line_x = getAmount($y_data[$xx], $m_data[$ix]['id'],$search_company_id,$search_office_id);
        //echo $line_x;return;
        array_push($m1, (float)$line_x);
    }
//    print_r($m1);
    array_push($total_for_gharp, ['name' => $y_data[$xx], 'data' => $m1]);
}
$data_series = $total_for_gharp;
//$data_series = [
//    ['name' => '2023', 'data' => [10, 5, 4,5,9,5,8,9]],
//    ['name' => '2024', 'data' => [5, 7, 3,6,5,8,7,9]],
//];
?>
    <div class="row">
        <div class="col-lg-12">
            <form action="<?= \yii\helpers\Url::to(['cashrecordsummary/index'], true) ?>" method="post">
                <div class="row">
                    <div class="col-lg-2">
                        <label class="form-label">บริษัท</label>
                        <?php
                        echo \kartik\select2\Select2::widget([
                            'name' => 'search_company_id',
                            'data' => \yii\helpers\ArrayHelper::map(\common\models\Company::find()->where(['status' => 1])->all(), 'id', 'name'),
                            'value' => $search_company_id,
                            'options' => [
                                'placeholder' => '---เลือกบริษัท---'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ]
                        ]);
                        ?>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label">สำนักงาน</label>
                        <?php
                        echo \kartik\select2\Select2::widget([
                            'name' => 'search_office_id',
                            'data' => \yii\helpers\ArrayHelper::map(\backend\helpers\OfficeType::asArrayObject(), 'id', 'name'),
                            'value' => $search_office_id,
                            'options' => [
                                'placeholder' => '---เลือกสำนักงาน---'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ]
                        ]);
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <div style="height: 35px;"></div>
                        <button class="btn btn-sm btn-primary">ค้นหา</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-lg-12" style="text-align: center;">
            <h4>รายงานสรุปยอดเงินสดย่อย</h4>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-12">
            <table class="table" style="width: 100%">
                <tr>
                    <td style="border: 1px solid grey;background-color: cornflowerblue"></td>
                    <?php for ($x = 0; $x <= count($y_data) - 1; $x++): ?>
                        <td style="border: 1px solid grey;text-align: center;background-color: cornflowerblue">
                            <b><?= $y_data[$x] ?></b></td>
                    <?php endfor; ?>
                </tr>
                <?php for ($i = 0; $i <= count($m_data) - 1; $i++): ?>
                    <tr>
                        <td style="border: 1px solid grey;"><?= $m_data[$i]['name'] ?></td>
                        <?php for ($x = 0; $x <= count($y_data) - 1; $x++): ?>
                            <?php
                            $line_amount = getAmount($y_data[$x], $m_data[$i]['id'],$search_company_id,$search_office_id);
                            array_push($total, ['year' => $y_data[$x], 'amount' => $line_amount]);
                            ?>
                            <td style="border: 1px solid grey;text-align: center;"><?php echo number_format($line_amount, 2) ?></td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
                <tr>
                    <td style="border: 1px solid grey;text-align: right;"><b>รวม</b></td>
                    <?php for ($x = 0; $x <= count($y_data) - 1; $x++): ?>
                        <?php
                        $total_amt = 0;
                        for ($a = 0; $a <= count($total) - 1; $a++) {
                            if ($y_data[$x] == $total[$a]['year']) {
                                $total_amt += (float)$total[$a]['amount'];
                            }
                        }
                        ?>
                        <td style="border: 1px solid grey;text-align: center;">
                            <b><?php echo number_format($total_amt, 2) ?></b></td>
                    <?php endfor; ?>
                </tr>

            </table>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-12">
            <?php
            echo Highcharts::widget([
                'options' => [
                    'title' => ['text' => 'กราฟแสดงจำนวนเงินเปรียบเทียบตามปีเดือน'],
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

<?php
function getAmount($year, $month,$search_company_id,$search_office_id)
{
    $amount = 0;
    $sql = "SELECT sum(t2.amount) as amount from cash_record as t1 inner join cash_record_line as t2 on t2.car_record_id = t1.id";
    $sql .= " WHERE year(t1.trans_date)=" . $year;
    $sql .= " AND month(t1.trans_date)=" . $month;

    if($search_company_id != null){
        $sql .= " AND company_id=" . $search_company_id;
    }
    if($search_office_id != null){
        $sql .= " AND office_id =" . $search_office_id;
    }

    $sql .= " GROUP BY year(trans_date), month(trans_date)";
    $query = \Yii::$app->db->createCommand($sql);
    $model = $query->queryAll();
    if ($model) {
        for ($i = 0; $i <= count($model) - 1; $i++) {
            $amount = $model[$i]['amount'];
        }
    }
    return $amount;
}

?>