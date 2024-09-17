<?php

use kartik\date\DatePicker;
use miloschuman\highcharts\Highcharts;

$display_date = date('d-m-Y');
$find_date = date('Y-m-d');


$m_name = [['id' => 1, 'name' => 'มกราคม'], ['id' => 2, 'name' => 'กุมภาพันธ์'], ['id' => 3, 'name' => 'มีนาคม'], ['id' => 4, 'name' => 'เมษายน'], ['id' => 5, 'name' => 'พฤษภาคม'], ['id' => 6, 'name' => 'มิถุนายน'], ['id' => 7, 'name' => 'กรกฎาคม'], ['id' => 8, 'name' => 'สิงหาคม'], ['id' => 9, 'name' => 'กันยายน'], ['id' => 10, 'name' => 'ตุลาคม'], ['id' => 11, 'name' => 'พฤศจิกายน'], ['id' => 12, 'name' => 'ธันวาคม']];
$model_title = null;

if ($find_year != null) {
    $model_title = \common\models\Alert::find()->where(['year(trans_date)' => $find_year])->groupBy(['emp_id'])->orderBy(['id' => SORT_ASC])->all();
} else {
    $model_title = \common\models\Alert::find()->groupBy(['emp_id'])->orderBy(['id' => SORT_ASC])->all();
}


$y_name = [];
for ($x = 0; $x <= 2; $x++) {
    $new_year = date('Y') - ($x);
    $y_name[] = ['id' => $new_year, 'name' => $new_year];
}
?>
    <form action="<?= \yii\helpers\Url::to(['alert/reportyear'], true) ?>" method="post">
        <div class="row">

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
                <td style="text-align: center;"><b>ประจำปี <?= $find_year; ?></b></td>
            </tr>
        </table>
        <br>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 10%;text-align: center;">หัวข้อ</th>
                <?php for ($k=0;$k<=count($m_name)-1;$k++): ?>
                    <th style="text-align: center;"><?= $m_name[$k]['name'] ?></th>
                <?php endfor; ?>
                <th style="text-align: center;background-color: #f0ad4e">รวม</th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="text-align: left"><b>จำนวน Alert ในเส้นทางปกติ</b></td>
                <?php $line_cnt_total = 0?>
                <?php for ($k=0;$k<=count($m_name)-1;$k++): ?>
                <?php $line_cnt = getDataAlertYear(1,$find_year,$m_name[$k]['id']);?>
                    <td style="text-align: center;"><?= number_format($line_cnt,0) ?></td>
                    <?php $line_cnt_total += $line_cnt?>
                <?php endfor; ?>

                <td style="text-align: center;background-color: #f0ad4e"><?= number_format($line_cnt_total,0) ?></td>

            </tr>
            <tr>
                <td style="text-align: left"><b>จำนวน Alert ในเส้นทางชุมชน</b></td>
                <?php $line_cnt_total = 0?>
                <?php for ($k=0;$k<=count($m_name)-1;$k++): ?>
                    <?php $line_cnt = getDataAlertYear(2,$find_year,$m_name[$k]['id']);?>
                    <td style="text-align: center;"><?= number_format($line_cnt,0) ?></td>
                    <?php $line_cnt_total += $line_cnt?>
                <?php endfor; ?>
                <td style="text-align: center;background-color: #f0ad4e"><?= number_format($line_cnt_total,0) ?></td>
            </tr>
            <tr>
                <td style="text-align: left"><b>จำนวน Alert 4 ชั่วโมง</b></td>
                <?php $line_cnt_total = 0?>
                <?php for ($k=0;$k<=count($m_name)-1;$k++): ?>
                    <?php $line_cnt = getDataAlertYear(3,$find_year,$m_name[$k]['id']);?>
                    <td style="text-align: center;"><?= number_format($line_cnt,0) ?></td>
                    <?php $line_cnt_total += $line_cnt?>
                <?php endfor; ?>

                <td style="text-align: center;background-color: #f0ad4e"><?= number_format($line_cnt_total,0) ?></td>
            </tr>
            <tr>
                <td style="text-align: left"><b>จำนวน Alert 10 ชั่วโมง</b></td>
                <?php $line_cnt_total = 0?>
                <?php for ($k=0;$k<=count($m_name)-1;$k++): ?>
                    <?php $line_cnt = getDataAlertYear(4,$find_year,$m_name[$k]['id']);?>
                    <td style="text-align: center;"><?= number_format($line_cnt,0) ?></td>
                    <?php $line_cnt_total += $line_cnt?>
                <?php endfor; ?>

                <td style="text-align: center;background-color: #f0ad4e"><?= number_format($line_cnt_total,0) ?></td>
            </tr>

            </tbody>
            <tfoot>

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
function getDataAlertYear($title_id, $year,$month)
{
    $cnt = 0;
    if ($title_id==1) {
        $model = \backend\models\Alert::find()->select(['SUM(normal_alert) as normal_alert'])->where(['year(trans_date)' => $year,'month(trans_date)' => $month])->one();
        if ($model) {
            $cnt = $model->normal_alert;
        }
    }else if($title_id==2){
        $model = \backend\models\Alert::find()->select(['SUM(city_alert) as city_alert'])->where(['year(trans_date)' => $year,'month(trans_date)' => $month])->one();
        if ($model) {
            $cnt = $model->city_alert;
        }
    }else if($title_id==3){
        $model = \backend\models\Alert::find()->select(['SUM(four_hour_alert) as four_hour_alert'])->where(['year(trans_date)' => $year,'month(trans_date)' => $month])->one();
        if ($model) {
            $cnt = $model->four_hour_alert;
        }
    }else if($title_id==4){
        $model = \backend\models\Alert::find()->select(['SUM(ten_hour_alert) as ten_hour_alert'])->where(['year(trans_date)' => $year,'month(trans_date)' => $month])->one();
        if ($model) {
            $cnt = $model->ten_hour_alert;
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