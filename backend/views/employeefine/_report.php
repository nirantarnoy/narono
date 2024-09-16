<?php

use kartik\date\DatePicker;
use miloschuman\highcharts\Highcharts;

$display_date = date('d-m-Y');
$find_date = date('Y-m-d');


$m_name = [['id' => 1, 'name' => 'มกราคม'], ['id' => 2, 'name' => 'กุมภาพันธ์'], ['id' => 3, 'name' => 'มีนาคม'], ['id' => 4, 'name' => 'เมษายน'], ['id' => 5, 'name' => 'พฤษภาคม'], ['id' => 6, 'name' => 'มิถุนายน'], ['id' => 7, 'name' => 'กรกฎาคม'], ['id' => 8, 'name' => 'สิงหาคม'], ['id' => 9, 'name' => 'กันยายน'], ['id' => 10, 'name' => 'ตุลาคม'], ['id' => 11, 'name' => 'พฤศจิกายน'], ['id' => 12, 'name' => 'ธันวาคม']];
$model_data = \common\models\EmployeeFine::find()->where(['company_id'=>$search_company_id,'month(trans_date)'=>$find_month,'year(trans_date)'=>date('Y')])->orderBy(['id' => SORT_ASC])->all();
?>
    <form action="<?= \yii\helpers\Url::to(['employeefine/report'], true) ?>" method="post">
        <div class="row">
            <div class="col-lg-12">
                <label class="form-label">เลือกวันที่</label>
                <div class="input-group">
                    <select name="find_month" class="form-control" id="">
                        <?php for ($k = 0; $k <= count($m_name) - 1; $k++): ?>
                        <?php $selected = '';
                        if ($m_name[$k]['id'] == $find_month) {
                            $selected = 'selected';
                        }
                        ?>
                            <option value="<?= $m_name[$k]['id'] ?>" <?=$selected?>><?= $m_name[$k]['name'] ?></option>
                        <?php endfor; ?>
                    </select>
                    <?php

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
                    ?>
                    <button class="btn btn-primary">ค้นหา</button>
                </div>
            </div>
        </div>
    </form>
    <br/>
    <div id="print-area">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center;"><h3><b>รายงานใบปรับจราจร</b></h3></td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>บริษัท <?= \backend\models\Company::findCompanyName($search_company_id); ?></b></td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>ประจำเดือน <?= $m_name[$find_month - 1]['name']; ?></b></td>
            </tr>
        </table>
        <br>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 5%;text-align: center;">ลำดับที่</th>
                <th style="width: 10%;text-align: center;">ทะเบียนรถ</th>
                <th style="width: 10%;text-align: center;">พนักงานขับรถ</th>
                <th style="width: 10%;text-align: center;">วันที่เกิดเหตุ</th>
                <th style="width: 8%;text-align: center;">เวลา</th>
                <th style="width: 10%;text-align: center;">สถานที่เกิดเหตุ</th>
                <th style="width: 20%;text-align: center;">สาเหตุการเกิดเหตุ</th>
                <th style="width: 10%;text-align: center;">ค่าปรับ</th>

            </tr>
            </thead>
            <tbody>
            <?php $line_no = 0;$total_fine_amount = 0;?>
            <?php foreach ($model_data as $value): ?>
              <?php
                $line_no++;
                $total_fine_amount += $value->fine_amount;
                ?>

                <tr>
                    <td style="text-align: center;"><?= $line_no ?></td>
                    <td style="text-align: center;"><?= $value->car_id ?></td>
                    <td style="text-align: center;"><?= getEmpName($value->emp_id) ?></td>
                    <td style="text-align: center;"><?= date('d/m/Y', strtotime($value->trans_date)) ?></td>
                    <td style="text-align: center;"><?= date('H:i', strtotime($value->trans_date)) ?></td>
                    <td style="text-align: center;"><?= $value->place ?></td>
                    <td style="text-align: center;"><?= $value->cause_description ?></td>
                    <td style="text-align: center;"><?= number_format($value->fine_amount,2) ?></td>
                </tr>

            <?php endforeach; ?>
            </tbody>
            <tfoot>
               <tr>
                   <td colspan="8" style="text-align: right;"><b>รวมทั้งหมด</b></td>
                   <td style="text-align: center;"><b><?= number_format($total_fine_amount,2) ?></b></td>
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