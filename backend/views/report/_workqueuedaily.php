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
        if ($search_company_id != null) {
            //$model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'company_id' => $search_company_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
            if ($search_car_id != null && $search_emp_id != null) {
                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'company_id' => $search_company_id, 'car_id' => $search_car_id, 'emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
            } else if ($search_car_id != null && $search_emp_id == null) {
                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'company_id' => $search_company_id, 'car_id' => $search_car_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
            } else if ($search_car_id == null && $search_emp_id != null) {
                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'company_id' => $search_company_id, 'emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
            } else {
                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'company_id' => $search_company_id,])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
            }
        } else {
            //  $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
            if ($search_car_id != null && $search_emp_id != null) {
                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'car_id' => $search_car_id, 'emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
            } else if ($search_car_id != null && $search_emp_id == null) {
                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'car_id' => $search_car_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
            } else if ($search_car_id == null && $search_emp_id != null) {
                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list, 'emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
            } else {
                $model = \backend\models\Workqueue::find()->where(['car_id' => $car_list])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
            }

        }

    }

} else {
    if ($search_company_id != null) {
        if ($search_car_id != null && $search_emp_id != null) {
            $model = \backend\models\Workqueue::find()->where(['company_id' => $search_company_id, 'car_id' => $search_car_id, 'emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
        } else if ($search_car_id != null && $search_emp_id == null) {
            $model = \backend\models\Workqueue::find()->where(['company_id' => $search_company_id, 'car_id' => $search_car_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
        } else if ($search_car_id == null && $search_emp_id != null) {
            $model = \backend\models\Workqueue::find()->where(['company_id' => $search_company_id, 'emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
        } else {
            $model = \backend\models\Workqueue::find()->where(['company_id' => $search_company_id,])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
        }

    } else {
        //$model = \backend\models\Workqueue::find()->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
        if ($search_car_id != null && $search_emp_id != null) {
            $model = \backend\models\Workqueue::find()->where(['car_id' => $search_car_id, 'emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
        } else if ($search_car_id != null && $search_emp_id == null) {
            $model = \backend\models\Workqueue::find()->where(['car_id' => $search_car_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
        } else if ($search_car_id == null && $search_emp_id != null) {
            $model = \backend\models\Workqueue::find()->where(['emp_assign' => $search_emp_id])->andFilterWhere(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
        } else {
            $model = \backend\models\Workqueue::find()->where(['>=', 'date(work_queue_date)', $find_date])->andFilterWhere(['<=', 'date(work_queue_date)', $find_to_date])->all();
        }
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
                        'data' => \yii\helpers\ArrayHelper::map(\backend\models\CarType::find()->all(), 'id', 'name'),
                        'value' => $search_car_type,
                        'options' => [
                            'placeholder' => '---เลือกประเภทรถ---'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]);
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
                    echo \kartik\select2\Select2::widget([
                        'name' => 'search_car_id',
                        'data' => \yii\helpers\ArrayHelper::map(\backend\models\Car::find()->all(), 'id', function ($data) {
                            return $data->plate_no;
                        }),
                        'value' => $search_car_id,
                        'options' => [
                            'placeholder' => '---เลือกทะเบียน---'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]);
                    echo \kartik\select2\Select2::widget([
                        'name' => 'search_emp_id',
                        'data' => \yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->all(), 'id', function ($data) {
                            return $data->fname . ' ' . $data->lname;
                        }),
                        'value' => $search_emp_id,
                        'options' => [
                            'placeholder' => '---พขร---'
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
                <td style="text-align: center;"><h3><b>รายงานประจำวัน</b></h3></td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>ตั้งแต่วันที่ <?= date('d/m/Y', strtotime($find_date)); ?>
                        ถึง <?= date('d/m/Y', strtotime($find_to_date)); ?></b></td>
            </tr>
        </table>
        <br>
        <table class="table table-bordered" id="table-data">
            <thead>
            <tr>
                <th style="width: 5%;text-align: center;">เที่ยว</th>
                <th style="width: 5%;text-align: center;">เลขที่</th>
                <th style="width: 8%;text-align: center;">วันที่</th>
                <th style="width: 8%;text-align: center;">ทะเบียนหัว</th>
                <th style="width: 8%;text-align: center;">ทะเบียนหาง</th>
                <th style="width: 8%;text-align: center;">ชื่อ พขร.</th>
                <th style="width: 8%;text-align: center;">ประเภทงาน</th>
                <th style="width: 8%;text-align: center;">เลขDP</th>
                <th style="width: 10%;text-align: center;">ลูกค้า</th>
                <th style="width: 8%;text-align: center;">ประเภทรถ</th>
                <th style="width: 8%;text-align: center;">น้ำหนัก(ตัน)</th>
                <th style="width: 8%;text-align: center;">ราคา</th>
                <th style="width: 8%;text-align: center;">จำนวนเงิน</th>
                <th>หมายเหตุ</th>
            </tr>
            </thead>
            <tbody>
            <?php $line_num = 0;
            $total_weight = 0;
            $total_line_amount = 0; ?>
            <?php if ($model): ?>
                <?php foreach ($model as $value): ?>
                    <?php
                    $line_num += 1;

                    $line_price_per_ton = getDropoffPriceperton($value->id);
                    $line_weight_ton = getDropoffWeightton($value->id);
                    $line_dp = getDropoffDP($value->id);
                    if ($line_weight_ton != null) {
                        //if($line_weight_ton[0]['is_charter'] == 0){
                        $total_weight += $line_weight_ton[0]['is_charter'] == 1 ? 0 : ($line_weight_ton[0]['weight']);
                        $total_line_amount += ($line_weight_ton[0]['weight'] * $line_price_per_ton);
                        //  $total_line_amount += ( $line_price_per_ton);
                        // }
                    } else {
                        $total_weight += 0;
                        $total_line_amount += 10;
                    }

                    ?>
                    <?php
                    $model_line_dp = \common\models\WorkQueueDropoff::find()->where(['work_queue_id' => $value->id])->all();
                    ?>
                    <?php if ($model_line_dp != null): ?>
                        <?php foreach ($model_line_dp as $value_dp): ?>
                        <?php $line_weight_ton_new = getDropoffWeighttonNew($value_dp->id); ?>
                            <tr>
                                <td style="width: 5%;text-align: center;"><?= $line_num ?></td>
                                <td style="width: 8%;text-align: center;"><?= $value->work_queue_no ?></td>
                                <td style="width: 8%;text-align: center;"><?= date('d/m/Y', strtotime($value->work_queue_date)) ?></td>
                                <td style="width: 8%;text-align: center;"><?= \backend\models\Car::findName($value->car_id) ?></td>
                                <td style="width: 8%;text-align: center;"><?= \backend\models\Car::findName($value->tail_id) ?></td>
                                <td style="width: 8%;text-align: center;"><?= \backend\models\Employee::findFullName($value->emp_assign) ?></td>
                                <td style="width: 8%;text-align: center;"><?= \backend\models\Customer::findWorkTypeByCustomerid($value->customer_id) ?></td>
                                <td style="width: 8%;text-align: center;"><?= $value_dp->dropoff_no ?></td>
                                <td style="width: 10%;text-align: center;"><?= \backend\models\Customer::findCusName($value->customer_id) ?></td>
                                <td style="width: 8%;text-align: center;"><?= \backend\models\Car::getCartype($value->car_id) ?></td>
                                <td style="width: 8%;text-align: center;"><?= $line_weight_ton_new[0]['is_charter'] == 1 ? 'เหมา' : number_format($line_weight_ton_new[0]['weight'], 3) ?></td>
                                <td style="width: 8%;text-align: center;"><?= number_format($line_price_per_ton, 2) ?></td>
                                <td style="width: 8%;text-align: center;"><?= $line_weight_ton[0]['is_charter'] == 1 ? number_format($line_price_per_ton, 2) : number_format(($line_weight_ton[0]['weight'] * $line_price_per_ton), 2) ?></td>
                                <td><?= $value->go_deduct_reason ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="10" style="width: 8%;text-align: right;"><b>รวม</b></td>
                <td style="width: 8%;text-align: center;"><b><?= number_format($total_weight, 3) ?></b></td>
                <td style="width: 8%;text-align: center;"><b></b></td>
                <td style="width: 8%;text-align: center;"><b><?= number_format($total_line_amount, 2) ?></b></td>
                <td></td>
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

function getDropoffPriceperton($workqueue_id)
{
    $price = 0;
    $model = \common\models\WorkQueueDropoff::find()->where(['work_queue_id' => $workqueue_id])->one();
    if ($model) {
        $price = $model->price_per_ton;
    }
    return $price;
}

function getDropoffWeightton($workqueue_id)
{
//    $price = 0;
//    $model = \common\models\WorkQueueDropoff::find()->where(['work_queue_id' => $workqueue_id])->one();
//    if ($model) {
//        if($model->is_charter == 0){
//            $price = $model->weight;
//        }
//    }
//    return $price;
    $data = [];
    $model = \common\models\WorkQueueDropoff::find()->where(['work_queue_id' => $workqueue_id])->one();
    if ($model) {
        if ($model->is_charter == 0 || $model->is_charter == null) {
            array_push($data, ['is_charter' => $model->is_charter, 'weight' => $model->weight]);
        } else {
            array_push($data, ['is_charter' => 1, 'weight' => 1]);
        }
    } else {
        array_push($data, ['is_charter' => 0, 'weight' => 0]);
    }
    return $data;
}

function getDropoffWeighttonNew($id)
{
//    $price = 0;
//    $model = \common\models\WorkQueueDropoff::find()->where(['work_queue_id' => $workqueue_id])->one();
//    if ($model) {
//        if($model->is_charter == 0){
//            $price = $model->weight;
//        }
//    }
//    return $price;
    $data = [];
    $model = \common\models\WorkQueueDropoff::find()->where(['id' => $id])->one();
    if ($model) {
        if ($model->is_charter == 0 || $model->is_charter == null) {
            array_push($data, ['is_charter' => $model->is_charter, 'weight' => $model->weight]);
        } else {
            array_push($data, ['is_charter' => 1, 'weight' => 1]);
        }
    } else {
        array_push($data, ['is_charter' => 0, 'weight' => 0]);
    }
    return $data;
}

function getDropoffDP($workqueue_id)
{
    $dropoff_no = 0;
    $model = \common\models\WorkQueueDropoff::find()->where(['work_queue_id' => $workqueue_id])->one();
    if ($model) {
        $dropoff_no = $model->dropoff_no;
    }
    return $dropoff_no;
}


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