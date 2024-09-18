<?php

use kartik\date\DatePicker;
use miloschuman\highcharts\Highcharts;

$display_date = date('d-m-Y');
$find_date = date('Y-m-d');


$m_name = [['id' => 1, 'name' => 'มกราคม'], ['id' => 2, 'name' => 'กุมภาพันธ์'], ['id' => 3, 'name' => 'มีนาคม'], ['id' => 4, 'name' => 'เมษายน'], ['id' => 5, 'name' => 'พฤษภาคม'], ['id' => 6, 'name' => 'มิถุนายน'], ['id' => 7, 'name' => 'กรกฎาคม'], ['id' => 8, 'name' => 'สิงหาคม'], ['id' => 9, 'name' => 'กันยายน'], ['id' => 10, 'name' => 'ตุลาคม'], ['id' => 11, 'name' => 'พฤศจิกายน'], ['id' => 12, 'name' => 'ธันวาคม']];
$model_title = null;

if($find_month != null && $find_year != null){
    $model_title = \common\models\Alert::find()->where(['month(trans_date)'=>$find_month,'year(trans_date)'=>$find_year])->groupBy(['emp_id'])->orderBy(['id' => SORT_ASC])->all();
}else{
    $model_title = \common\models\Alert::find()->groupBy(['emp_id'])->orderBy(['id' => SORT_ASC])->all();
}

$index_month = (int)$find_month-1;

$y_name = [];
for($x=0;$x<=2;$x++){
    $new_year = date('Y')-($x);
    $y_name[] = ['id' => $new_year, 'name' => $new_year];
}
?>
    <form action="<?= \yii\helpers\Url::to(['alert/reportmonth'], true) ?>" method="post">
        <div class="row">
            <div class="col-lg-3">
                <select name="find_month" class="form-control" id="">
                    <?php for ($i = 0; $i < count($m_name) - 1; $i++): ?>
                        <option value="<?= $m_name[$i]['id'] ?> "><?= $m_name[$i]['name'] ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-lg-3">
                <select name="find_year" class="form-control" id="">
                    <?php for ($i = 0; $i < count($y_name) - 1; $i++): ?>
                        <option value="<?= $y_name[$i]['id'] ?> "><?= $y_name[$i]['name'] ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-lg-3">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
            <div class="col-lg-3"></div>
        </div>
        <br/>
    </form>
    <br/>

    <div id="print-area">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center;"><h3><b>รายงาน Alert</b></h3></td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>ประจำเดือน <?= $find_month == null ? '': $m_name[$index_month]['name']; ?></b></td>
            </tr>
        </table>
        <br>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 10%;text-align: center;">ชื่อพนักงาน</th>
                <th style="width: 20%;text-align: center;">จำนวน Alert ในเส้นทางปกติ</th>
                <th style="width: 20%;text-align: center;">จำนวน Alert ในเส้นทางชุมชน</th>
                <th style="width: 20%;text-align: center;">จำนวน Alert 4 ชั่วโมง</th>
                <th style="width: 20%;text-align: center;">จำนวน Alert 10 ชั่วโมง</th>
                <th style="width: 10%;text-align: center;">รวม</th>

            </tr>
            </thead>
            <tbody>
            <?php
            $all_total_all_1 = 0;
            $all_total_all_2 = 0;
            $all_total_all_4 = 0;
            $all_total_all_10 = 0;
            $all_total_all = 0;
            ?>
            <?php foreach ($model_title as $title): ?>
                <?php
                $data = getDataAlert($title->emp_id, $find_date);
//            print_r($data);
                $data_alert = $data[0]['normal_alert'];
                $data_alert2 = $data[0]['city_alert'];
                $data_alert4 = $data[0]['alert_4'];
                $data_alert10 = $data[0]['alert_10'];

                $all_total_all_1 += $data_alert;
                $all_total_all_2 += $data_alert2;
                $all_total_all_4 += $data_alert4;
                $all_total_all_10 += $data_alert10;

                $line_total = $data_alert + $data_alert2 + $data_alert4 + $data_alert10;
                $all_total_all += $line_total;
                ?>
                <tr>
                    <td><?= getEmpName($title->emp_id) ?></td>
                    <td style="text-align: center"><?= number_format($data_alert,0) ?></td>
                    <td style="text-align: center"><?= number_format($data_alert2,0) ?></td>
                    <td style="text-align: center"><?= number_format($data_alert4,0) ?></td>
                    <td style="text-align: center"><?= number_format($data_alert10,0) ?></td>
                    <td style="text-align: center"><?= number_format($line_total,0) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                  <td style="text-align: center">รวมทั้งหมด</td>
                  <td style="text-align: center"><b><?= number_format($all_total_all_1) ?></b></td>
                  <td style="text-align: center"><b><?= number_format($all_total_all_2) ?></b></td>
                  <td style="text-align: center"><b><?= number_format($all_total_all_4) ?></b></td>
                  <td style="text-align: center"><b><?= number_format($all_total_all_10) ?></td>
                  <td style="text-align: center"><b><?= number_format($all_total_all) ?></b></td>
              </tr>
            </tfoot>

        </table>
    </div>

    <br/>


    <div class="row">
        <div class="col-lg-4">
            <div class="btn btn-warning btn-print" onclick="printContent('print-area')">พิมพ์</div>
        </div>
    </div>
<?php
function getEmpName($emp_id)
{
    $name = \backend\models\Employee::findFullName($emp_id);
    return $name;
}

function getDataAlert($emp_id, $month)
{
    $data = [];
    if ($emp_id) {
        $model = \backend\models\Alert::find()->select(['SUM(normal_alert) as normal_alert,SUM(city_alert) as city_alert,SUM(four_hour_alert) as four_hour_alert,SUM(ten_hour_alert) as ten_hour_alert'])->where(['emp_id' => $emp_id])->one();
        if ($model) {
            array_push($data, ['normal_alert' => $model->normal_alert, 'city_alert' => $model->city_alert, 'alert_4' => $model->four_hour_alert, 'alert_10' => $model->ten_hour_alert]);
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