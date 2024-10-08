<?php

use kartik\date\DatePicker;

$model = null;

//if ($from_date!=null && $to_date != null) {
if($search_company_id == null && $search_office_id ==null){
    $model = \common\models\QueryCashCompareReceipt::find()->where(['>=','date(trans_date)',date('Y-m-d',strtotime($from_date))])
        ->andFilterWhere(['<=','date(trans_date)',date('Y-m-d',strtotime($to_date))])->all();
}else if($search_company_id != null && $search_office_id != null){
    $model = \common\models\QueryCashCompareReceipt::find()->where(['>=','date(trans_date)',date('Y-m-d',strtotime($from_date))])->andFilterWhere(['<=','date(trans_date)',date('Y-m-d',strtotime($to_date))])->andfilterWhere(['or',['company_id' => $search_company_id],['company_id_2' => $search_company_id]])->andFilterWhere(['or',['office_id' => $search_office_id],['office_id_2' => $search_office_id]])->all();
}

if($search_company_id != null && $search_office_id ==null){
    echo $search_company_id;
    $model = \common\models\QueryCashCompareReceipt::find()->where(['>=','date(trans_date)',date('Y-m-d',strtotime($from_date))])->andFilterWhere(['<=','date(trans_date)',date('Y-m-d',strtotime($to_date))])->andFilterWhere(['or',['company_id' => $search_company_id],['company_id_2' => $search_company_id]])->all();
}
if($search_company_id == null && $search_office_id != null){
    $model = \common\models\QueryCashCompareReceipt::find()->where(['>=','date(trans_date)',date('Y-m-d',strtotime($from_date))])->andFilterWhere(['or',['office_id' => $search_office_id],['office_id_2' => $search_office_id]])->all();
}

$location_data = \common\models\Location::find()->where(['company_id' => $search_company_id])->all();
//}

?>
    <div class="row">
        <div class="col-lg-12">
            <form action="<?= \yii\helpers\Url::to(['cashrecordreportdaily/index'], true) ?>" method="post">
                <div class="row">
                    <div class="col-lg-2">
                        <label class="form-label">ตั้งแต่วันที่</label>

                        <?php
                        echo DatePicker::widget([
                            'name' => 'search_from_date',
                            'type' => DatePicker::TYPE_INPUT,
                            'value' => date('d-m-Y',strtotime($from_date)),
                            'pluginOptions' => [
                                'autoClose' => true,
                                'format' => 'dd-mm-yyyy'
                            ]
                        ]);
                        ?>

                    </div>
                    <div class="col-lg-2">

                        <label class="form-label">ถึงวันที่</label>
                        <?php
                        echo DatePicker::widget([
                            'name' => 'search_to_date',
                            'type' => DatePicker::TYPE_INPUT,
                            'value' => date('d-m-Y',strtotime($to_date)),
                            'pluginOptions' => [
                                'autoClose' => true,
                                'format' => 'dd-mm-yyyy'
                            ]
                        ]);
                        ?>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label">บริษัท</label>
                        <?php
                        echo \kartik\select2\Select2::widget([
                            'name' => 'search_company_id',
                            'data' => \yii\helpers\ArrayHelper::map(\common\models\Company::find()->where(['status' => 1])->all(), 'id', 'name'),
                            'value' => $search_company_id,
                            'options' => [
                                'placeholder' => '---เลือกบริษัท---',
                                'onchange' => 'getOfficeList(this);'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ]
                        ]);
                        ?>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label">สำนักงาน</label>
                        <select name="search_office_id" id="office-list" class="form-control">
                            <?php
                            for($a=0;$a <=count($location_data)-1;$a++){
                                $selected = '';
                                if($location_data[$a]->id == $search_office_id){
                                    $selected = 'selected';
                                }
                                echo '<option value="'.$location_data[$a]->id.'" '.$selected.'>'.$location_data[$a]->name.'</option>';
                            }
                            ?>
                        </select>
<!--                        <label class="form-label">สำนักงาน</label>-->
                        <?php
//                        echo \kartik\select2\Select2::widget([
//                            'name' => 'search_office_id',
//                            'data' => \yii\helpers\ArrayHelper::map(\backend\helpers\OfficeType::asArrayObject(), 'id', 'name'),
//                            'value' => $search_office_id,
//                            'options' => [
//                                'placeholder' => '---เลือกสำนักงาน---'
//                            ],
//                            'pluginOptions' => [
//                                'allowClear' => true,
//                            ]
//                        ]);
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
    <div style="height: 20px;"></div>
    <div id="print-area">
        <div class="row">
            <div class="col-lg-12">
                <table style="width: 100%;">
                    <tr>
                        <td></td>
                        <td style="text-align: center;"><h5><b>รายละเอียดเงินสดย่อย</b></h5></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <table style="width: 100%;border: 1px solid grey;">
                    <tr>
                        <td style="border: 1px solid grey;width: 10%;text-align: center;padding: 10px;">วันที่</td>
                        <td style="border: 1px solid grey;width: 15%;text-align: center;">รายการ</td>
                        <td style="border: 1px solid grey;width: 10%;text-align: center;">ทะเบียน</td>
                        <td style="border: 1px solid grey;width: 10%;text-align: center;">รับ</td>
                        <td style="border: 1px solid grey;width: 10%;text-align: center;">จ่าย</td>
                        <td style="border: 1px solid grey;width: 10%;text-align: center;">ยอดรวม/วัน</td>
                        <td style="border: 1px solid grey;width: 10%;text-align: center;">คงเหลือ</td>
                        <td style="border: 1px solid grey;width: 15%;text-align: center;">ลูกค้า</td>
                        <td style="border: 1px solid grey;width: 10%;text-align: center;">หัก ณ ที่จ่าย1%</td>
                    </tr>
                    <?php if ($model != null): ?>
                    <?php
                        $is_last_trans_balance  =0;
                        $last_check_date = null;
                        $line_cash_rec_total_amount = 0;
                        $line_reciept_total_amount = 0;

                        if($is_last_trans_balance ==0){
                            $line_reciept_total_amount = getMonthbalance(2024,4);
                            $is_last_trans_balance = 1;
                        }

                        ?>
                        <?php foreach ($model as $value): ?>
                            <?php
                            $line_title = '';
                            $line_carplateno = 0;
                            $line_cash_rec_amount = 0;
                            $line_reciept_amount = 0;
                            $line_last_cash_rec_row = 0;
                            $xdata = null;
                            $xrecdata = null;
                            $xdata = getDetail($value->trans_ref_id);

                            if ($value->activity_type_id == 5) {
                                if ($xdata != null) {
                                    $line_title = $xdata[0]['title_name'];
                                    $line_carplateno = $xdata[0]['car_plateno'];
                                    $line_cash_rec_amount = $xdata[0]['amount'];

                                    $line_cash_rec_total_amount += $line_cash_rec_amount;
                                }
                            }


                            if ($value->activity_type_id == 6) {
                                $xdata = getRecieveDetail($value->trans_ref_id);
                                if ($xdata != null) {
                                    $line_title = $xdata[0]['title_name'];
                                    $line_reciept_amount = $xdata[0]['amount'];

                                    $line_reciept_total_amount += $line_reciept_amount;
                                }
                            }

                            ?>
                            <tr>
                                <td style="border: 1px solid grey;text-align: center"><?= date('d-m-Y H:i', strtotime($value->trans_date)) ?></td>
                                <td style="border: 1px solid grey;padding: 3px;"><?= $line_title; ?></td>
                                <td style="border: 1px solid grey;text-align: center;padding: 3px;"><?= \backend\models\Car::findName($line_carplateno); ?></td>
                                <td style="border: 1px solid grey;text-align: right;padding: 3px;color: green;"><?= $line_reciept_amount == 0 ? '' : number_format($line_reciept_amount, 2) ?></td>
                                <td style="border: 1px solid grey;text-align: right;padding: 3px;color: red;"><?= $line_cash_rec_amount == 0 ? '' : number_format($line_cash_rec_amount, 2) ?></td>
                                <td style="border: 1px solid grey;text-align: right;padding: 3px;"><?php echo $line_cash_rec_total_amount == 0 ? '':number_format(($line_reciept_total_amount -$line_cash_rec_total_amount),2)?></td>
                                <td style="border: 1px solid grey;text-align: right;padding: 3px;"></td>
                                <td style="border: 1px solid grey;padding: 3px;"></td>
                                <td style="border: 1px solid grey;text-align: right;padding: 3px;"></td>
                            </tr>
                        <?php
                            $last_check_date = date('d-m-Y',strtotime($value->trans_date));

                            ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <div class="btn btn-default btn-print" onclick="printContent('print-area')">พิมพ์</div>
        </div>
    </div>
<?php
function getDetail($refid)
{
    $data = [];
    $amount = 0;

    $sql = "SELECT t1.car_id,t2.car_record_id,t3.name as title_name,SUM(t2.amount) as amount FROM cash_record as t1 INNER JOIN cash_record_line as t2 on t2.car_record_id = t1.id  LEFT JOIN fixcost_title as t3 on t2.cost_title_id = t3.id
              WHERE t1.id=" . $refid;
    $sql .= " GROUP BY t1.car_id,t2.car_record_id,t3.name";
    $sql .= " ORDER BY t1.id";
    $query = \Yii::$app->db->createCommand($sql);
    $model = $query->queryAll();
    if ($model) {
        for ($i = 0; $i <= count($model) - 1; $i++) {
            array_push($data, [
                'title_name' => $model[$i]['title_name'],
                'amount' => $model[$i]['amount'],
                'car_plateno' => $model[$i]['car_id'],
            ]);
            $amount = $model[$i]['amount'];
        }
    }
    return $data;
}

function getRecieveDetail($refid)
{
    $data = [];
    $amount = 0;

    $sql = "SELECT t2.reciept_record_id,t3.name as title_name,SUM(t2.amount) as amount FROM reciept_record as t1 INNER JOIN reciept_record_line as t2 on t2.reciept_record_id = t1.id  LEFT JOIN fixcost_title as t3 on t2.receipt_title_id = t3.id
              WHERE t1.id=" . $refid;
    $sql .= " GROUP BY t1.id,t2.reciept_record_id,t3.name";
    $sql .= " ORDER BY t1.id";
    $query = \Yii::$app->db->createCommand($sql);
    $model = $query->queryAll();
    if ($model) {
        for ($i = 0; $i <= count($model) - 1; $i++) {
            array_push($data, [
                'title_name' => $model[$i]['title_name'],
                'amount' => $model[$i]['amount'],
            ]);
            $amount = $model[$i]['amount'];
        }
    }
    return $data;
}

function getMonthbalance($year,$month)
{
    $amount = 0;

    $sql = "SELECT balance_amount FROM daily_trans_close WHERE trans_year=" . $year;
    $sql .= " AND trans_month=". $month;
    $query = \Yii::$app->db->createCommand($sql);
    $model = $query->queryAll();
    if ($model) {
        for ($i = 0; $i <= count($model) - 1; $i++) {
            $amount = $model[$i]['balance_amount'];
        }
    }
    return $amount;
}

?>
<?php
$url_to_get_office = \yii\helpers\Url::to(['cashreportdaily/getoffice'], true);
$js = <<<JS
function printContent(el)
      {
         var restorepage = document.body.innerHTML;
         var printcontent = document.getElementById(el).innerHTML;
         document.body.innerHTML = printcontent;
         window.print();
         document.body.innerHTML = restorepage;
     }
function getOfficeList(el){
    var company_id = $(el).val();
    if(company_id > 0){
      //  alert(company_id);
         $.ajax({
        url: '$url_to_get_office',
        type: 'POST',
        dataType: 'html',
        data: {'company_id': company_id},
        success: function (data) {
         //   alert(data);
        
            $('#office-list').html(data);
        }
    });
    }
   
}      
JS;
$this->registerJs($js, static::POS_END);
?>