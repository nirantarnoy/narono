<?php

use kartik\date\DatePicker;

$display_date = date('d-m-Y');
$find_date = date('Y-m-d');
if ($search_date != null) {
    $find_date = date('Y-m-d', strtotime($search_date));
    $display_date = date('d-m-Y', strtotime($search_date));
}

//$model = \backend\models\Workqueue::find()->where(['date(work_queue_date)' => $find_date,'work_option_type_id'=>[1,2]])->all();
$model = \backend\models\Workqueue::find()->where(['date(work_queue_date)' => $find_date])->all();
?>
    <form action="<?= \yii\helpers\Url::to(['report/workqueuedaily'], true) ?>" method="post">
        <div class="row">
            <div class="col-lg-3">
                <label class="form-label">เลือกวันที่</label>
                <div class="input-group">
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
                    ?>
                    <button class="btn btn-primary">ค้นหา</button>
                </div>
            </div>
        </div>
    </form>
    <div id="print-area">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center;"><h3><b>รายงานประจำวัน</b></h3></td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>วันที่ <?= date('d/m/Y', strtotime($find_date)); ?></b></td>
            </tr>
        </table>
        <br>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 8%;text-align: center;">ลำดับที่</th>
                <th style="width: 10%;text-align: center;">วันที่</th>
                <th style="width: 10%;text-align: center;">ทะเบียนหัว</th>
                <th style="width: 10%;text-align: center;">ทะเบียนหาง</th>
                <th style="width: 10%;text-align: center;">ชื่อ พขร.</th>
                <th style="width: 10%;text-align: center;">ประเภทงาน</th>
                <th style="width: 10%;text-align: center;">ลูกค้า</th>
                <th style="width: 10%;text-align: center;">ประเภทรถ</th>
                <th style="width: 10%;text-align: right;">น้ำหนัก(ตัน)</th>
            </tr>
            </thead>
            <tbody>
            <?php $line_num = 0;
            $total_weight = 0; ?>
            <?php if ($model): ?>
                <?php foreach ($model as $value): ?>
                    <?php
                    $line_num += 1;
                    $total_weight += ($value->weight_on_go);
                    ?>
                    <tr>
                        <td style="width: 8%;text-align: center;"><?= $line_num ?></td>
                        <td style="width: 10%;text-align: center;"><?= date('d/m/Y', strtotime($value->work_queue_date)) ?></td>
                        <td style="width: 10%;text-align: center;"><?= \backend\models\Car::findName($value->car_id) ?></td>
                        <td style="width: 10%;text-align: center;"><?= \backend\models\Car::findName($value->tail_id) ?></td>
                        <td style="width: 10%;text-align: center;"><?= \backend\models\Employee::findFullName($value->emp_assign) ?></td>
                        <td style="width: 10%;text-align: center;"><?= \backend\models\Workqueue::findWorkType($value->id) ?></td>
                        <td style="width: 10%;text-align: center;"><?= \backend\models\Customer::findCusName($value->customer_id) ?></td>
                        <td style="width: 10%;text-align: center;"><?= \backend\models\Car::getCartype($value->car_id) ?></td>
                        <td style="width: 10%;text-align: right;"><?= number_format($value->weight_on_go, 3) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="8" style="width: 8%;text-align: right;"><b>รวม</b></td>
                <td style="width: 10%;text-align: right;"><b><?= number_format($total_weight, 3) ?></b></td>
            </tr>
            </tfoot>

        </table>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="btn btn-warning btn-print" onclick="printContent('print-area')">พิมพ์</div>
        </div>
    </div>
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