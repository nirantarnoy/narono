<?php

use kartik\date\DatePicker;

$display_date = date('d-m-Y');
$display_to_date = date('d-m-Y');
$find_date = date('Y-m-d');
$find_to_date = date('Y-m-d');
if ($search_date != null) {
    $find_date = date('Y-m-d', strtotime($search_date));
    $display_date = date('d-m-Y', strtotime($search_date));
}
if ($search_to_date != null) {
    $find_to_date = date('Y-m-d', strtotime($search_to_date));
    $display_to_date = date('d-m-Y', strtotime($search_to_date));
}
$model = null;

//$model = \backend\models\Workqueue::find()->where(['date(work_queue_date)' => $find_date,'work_option_type_id'=>[1,2]])->all();
if ($search_car_type != null) {
    $model_car = \backend\models\Car::find()->select(['id'])->where(['car_type_id' => $search_car_type])->all();
    $car_list = [];
    if ($model_car) {
        foreach ($model_car as $value) {
            array_push($car_list, $value->id);
        }
        //$model = \backend\models\Workqueue::find()->where(['date(work_queue_date)' => $find_date, 'car_id' => $car_list])->all();
        if($search_company_id != null){
            $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list,'company_id'=>$search_company_id])->andFilterWhere(['>=','date(work_queue_date)',$find_date])->andFilterWhere(['<=','date(work_queue_date)',$find_to_date])->all();
        }else{
            $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list])->andFilterWhere(['>=','date(work_queue_date)',$find_date])->andFilterWhere(['<=','date(work_queue_date)',$find_to_date])->all();
        }

    }

} else {
    if($search_company_id!=null){
        $model = \backend\models\Workqueue::find()->where(['company_id' => $search_company_id,])->andFilterWhere(['>=','date(work_queue_date)',$find_date])->andFilterWhere(['<=','date(work_queue_date)',$find_to_date])->all();
    }else{
        $model = \backend\models\Workqueue::find()->where(['company_id' => $search_company_id])->andFilterWhere(['>=','date(work_queue_date)',$find_date])->andFilterWhere(['<=','date(work_queue_date)',$find_to_date])->all();
    }

}

?>
    <form action="<?= \yii\helpers\Url::to(['report/workqueuedaily'], true) ?>" method="post">
        <div class="row">
            <div class="col-lg-12">
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
                    echo DatePicker::widget([
                        'name' => 'search_to_date',
                        'type' => DatePicker::TYPE_INPUT,
                        'value' => $display_to_date,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy'
                        ]
                    ]);
                    ?>
                    <?php
                    echo \kartik\select2\Select2::widget([
                        'name' => 'search_car_type',
                        'data' => \yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->all(), 'id', 'name'),
                        'value' => $search_car_type,
                        'options' => [
                            'placeholder'=>'---พนักงาน---'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'multiple'  => true,
                        ]
                    ]);
                    echo \kartik\select2\Select2::widget([
                        'name' => 'search_company_id',
                        'data' => \yii\helpers\ArrayHelper::map(\backend\models\Company::find()->all(), 'id', 'name'),
                        'value' => $search_company_id,
                        'options' => [
                            'placeholder'=>'---เลือกบริษัท---'
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
    <br />
    <div id="print-area">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center;"><h3><b>รายงานประจำวัน</b></h3></td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>ตั้งแต่วันที่ <?= date('d/m/Y', strtotime($find_date)); ?> ถึง <?= date('d/m/Y', strtotime($find_to_date)); ?></b></td>
            </tr>
        </table>
        <br>
        <table class="table table-bordered" id="table-data">
            <thead>
            <tr>
                <th style="width: 10%;text-align: center;">ชื่อพนักงาน</th>
                <th style="width: 10%;text-align: center;">วันที่</th>
                <th style="width: 10%;text-align: center;">ลูกค้า</th>
                <th style="width: 10%;text-align: center;">ค่าเที่ยว</th>
                <th style="width: 10%;text-align: right;">จำนวนเงิน</th>
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