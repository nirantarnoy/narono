<?php


$date_day = date('d', strtotime($model->trans_date));
$date_month = \backend\helpers\Thaimonth::getTypeById((int)(date('m', strtotime($model->trans_date))));
$date_year = date('Y', strtotime($model->trans_date)) + 543;
?>
<style media="print">
    /*body {*/
    /*    font-family: sarabun;*/
    /*    !*font-family: garuda;*!*/
    /*    font-size: 18px;*/
    /*    width: 350px;*/
    /*}*/

    /*table.table-header {*/
    /*    border: 0px;*/
    /*    border-spacing: 1px;*/
    /*}*/

    /*table.table-qrcode {*/
    /*    border: 0px;*/
    /*    border-spacing: 1px;*/
    /*}*/

    /*table.table-qrcode td, th {*/
    /*    border: 0px solid #dddddd;*/
    /*    text-align: left;*/
    /*    padding-top: 2px;*/
    /*    padding-bottom: 2px;*/
    /*}*/

    /*table.table-footer {*/
    /*    border: 0px;*/
    /*    border-spacing: 0px;*/
    /*}*/

    /*table.table-header td, th {*/
    /*    border: 0px solid #dddddd;*/
    /*    text-align: left;*/
    /*    padding-top: 2px;*/
    /*    padding-bottom: 2px;*/
    /*}*/

    /*table.table-title {*/
    /*    border: 0px;*/
    /*    border-spacing: 0px;*/
    /*}*/

    /*table.table-title td, th {*/
    /*    border: 0px solid #dddddd;*/
    /*    text-align: left;*/
    /*    padding-top: 2px;*/
    /*    padding-bottom: 2px;*/
    /*}*/

    /*table {*/
    /*    border-collapse: collapse;*/
    /*    width: 100%;*/
    /*}*/

    /*td, th {*/
    /*    border: 1px solid #dddddd;*/
    /*    text-align: left;*/
    /*    padding: 8px;*/
    /*}*/

    /*tr:nth-child(even) {*/
    /*    !*background-color: #dddddd;*!*/
    /*}*/

    /*table.table-detail {*/
    /*    border-collapse: collapse;*/
    /*    width: 100%;*/
    /*}*/

    /*table.table-detail td, th {*/
    /*    border: 1px solid #dddddd;*/
    /*    text-align: left;*/
    /*    padding: 2px;*/
    /*}*/




    /*@media print {*/
    /*    html, body {*/
    /*        width: 80mm;*/
    /*        height:100%;*/
    /*        position:absolute;*/
    /*    }*/
    /*}*/

</style>
<div class="row">
    <div class="col-lg-12">
        <div class="btn btn-default btn-print" onclick="printContent('print-area')">พิมพ์</div>
    </div>
</div>
<div id="print-area" style="width: 100%;">
    <table style="width: 100%">
        <tr>
            <td style="text-align: right;width: 33%"></td>
            <td style="text-align: center;width: 33%"><h4>
                    <b><?= \backend\models\Company::findCompanyName($model->company_id) ?></b></h4></td>
            <td style="text-align: right;width: 33%"></td>
        </tr>
        <tr>
            <td style="text-align: right;width: 33%"></td>
            <td style="text-align: center;width: 33%">
                <h6><?= \backend\models\Company::findAddress($model->company_id) ?></h6></td>
            <td style="text-align: right;width: 33%"></td>
        </tr>
        <tr>
            <td style="text-align: right;width: 33%"></td>
            <td style="text-align: center;width: 33%">
                <h6>เลขที่ผู้เสียภาษี <?= \backend\models\Company::findTaxid($model->company_id) ?></h6></td>
            <td style="text-align: right;width: 33%"></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%">
        <tr>
            <td style="padding: 5px;width: 33%"></td>
            <td style="text-align: center;width: 33%"><h5><b>ใบสำคัญจ่าย</b></h5></td>
            <td style="text-align: right;width: 33%">เลขที่ <b><?= $model->journal_no ?></b></td>
        </tr>
    </table>
    <?php
      $pay_for_name = '';
      if($model->pay_for_type_id == 1){
          $pay_for_name = \backend\models\Car::findDrivername($model->car_id).','.\backend\models\Car::getPlateno($model->car_id);
      }else{
          $pay_for_name = $model->pay_for;
      }
      //$pay_for_name = $model->pay_for_type_id;
    ?>
    <table style="width: 100%">
        <tr>
            <td style="width:50%;padding: 5px;">จ่ายให้ <b><?=$pay_for_name ?></b>
            </td>
            <!--            <td><input type="text" class="form-control"></td>-->
            <td style="width:50%;padding: 5px;"> วันที่ <b><?= date('d-m-Y', strtotime($model->trans_date)) ?></b></td>
            <!--            <td><input type="text" class="form-control"></td>-->
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <td style="width:15%;padding: 5px;">จ่ายโดย
                <b><?= \backend\models\Paymentmethod::findName($model->payment_method_id) ?> <?= $model->bank_account ?></b></td>
            <td style="width:15%;padding: 5px;">เลขที่เช็ค <b><?= $model->check_no ?></b></td>
            <td style="width:15%;padding: 5px;">อ้างถึง <?=$model->ref_no?></td>

        </tr>
    </table>
    <br>
    <table style="width: 100%;border: 1px solid grey;">
        <td style="width: 70%;vertical-align: top;">
            <table style="width: 100%">
                <tr>
                    <td style="width: 80%;border: 1px solid grey;text-align: center;padding: 5px;"><b>รายการ</b></td>
                    <td style="width: 20%;border: 1px solid grey;text-align: right;padding: 5px;"><b>จำนวนเงิน</b></td>
                </tr>
                <?php if ($model_line != null): ?>
                    <?php
                    $line_diff = 6 - count($model_line);
                    $all_total = 0;
                    ?>
                    <?php foreach ($model_line as $value): ?>
                        <?php $all_total = ($all_total + $value->amount); ?>
                        <tr>
                            <td style="width: 30%;border: 1px solid grey;text-align: left;padding: 6px;"><?= \backend\models\CostTitle::findName($value->cost_title_id) . (trim($value->remark) != '' ? ' (' . $value->remark . ')' : '') ?></td>
                            <td style="width: 30%;border: 1px solid grey;text-align: right;padding: 5px;"><?= number_format($value->amount, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($line_diff > 0): ?>
                        <?php for ($x = 0; $x <= $line_diff - 1; $x++): ?>
                            <tr>
                                <td style="width: 30%;border: 1px solid grey;text-align: left;padding: 6px;color: transparent;"><?= $x ?></td>
                                <td style="width: 30%;border: 1px solid grey;text-align: right;padding: 5px;">
                                    <b><?= $x == ($line_diff - 1) ? number_format($all_total, 2) : '' ?></b></td>
                            </tr>
                        <?php endfor; ?>
                    <?php endif; ?>
                <?php else: ?>

                    <tr>
                        <td style="width: 30%;border: 1px solid grey;text-align: left;padding: 5px;"></td>
                        <td style="width: 30%;border: 1px solid grey;text-align: right;"></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border: 1px solid grey;text-align: left;padding: 5px;"></td>
                        <td style="width: 30%;border: 1px solid grey;text-align: right;"></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border: 1px solid grey;text-align: left;padding: 5px;"></td>
                        <td style="width: 30%;border: 1px solid grey;text-align: right;"></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border: 1px solid grey;text-align: left;padding: 5px;"></td>
                        <td style="width: 30%;border: 1px solid grey;text-align: right;"></td>
                    </tr>
                    <tr>
                        <td style="width: 30%;border: 1px solid grey;text-align: left;padding: 5px;"></td>
                        <td style="width: 30%;border: 1px solid grey;text-align: right;"></td>
                    </tr>

                    <tr>
                        <td style="width: 30%;border: 1px solid grey;text-align: left;padding: 5px;"><b>รวม</b></td>
                        <td style="width: 30%;border: 1px solid grey;text-align: right;"><b>0</b></td>
                    </tr>
                <?php endif; ?>

            </table>
        </td>
        <td style="border-left: 1px solid grey;">
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 20px;font-size: 12px;">ผู้อนุมัติการจ่ายเงิน
                        ...........................................................
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;text-align: center;">............/................/...............</td>
                </tr>
                <tr>
                    <td style="padding: 20px;border-top: 1px solid grey;font-size: 12px;">ผู้จ่ายเงิน
                        ..................................................................
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;text-align: center;">............/................/...............</td>
                </tr>
                <tr>
                    <td style="padding: 20px;border-top: 1px solid grey;font-size: 12px;">ผู้รับเงิน
                        ...................................................................
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;text-align: center;">............/................/...............</td>
                </tr>
            </table>
        </td>
    </table>
    <table style="width: 100%;border: 1px solid grey;">
        <tr>
            <td colspan="6" style="text-align: center;"><b>สำหรับฝ่ายบัญชี</b></td>
        </tr>
        <tr>
            <td rowspan="2" style="width: 50%;border: 1px solid grey;text-align: center;">ชื่อบัญชี</td>
            <td rowspan="2" style="width: 10%;border: 1px solid grey;text-align: center;">รหัสบัญชีแยกประเภท</td>
            <td colspan="4" style="width: 40%;border: 1px solid grey;text-align: center;">จำนวนเงิน</td>
        </tr>
        <tr>

            <td colspan="2" style="border: 1px solid grey;text-align: center;">เดบิต</td>

            <td colspan="2" style="border: 1px solid grey;text-align: center;">เครดิต</td>

        </tr>
        <?php 
          $model_account = \common\models\CashRecordAccount::find()->where(['cash_record_id' => $model->id])->all(); 
          $total_debit = 0;
          $total_credit = 0;
        ?>
        <?php if($model_account): ?>
            <?php foreach($model_account as $acc): ?>
                <?php 
                    $total_debit += $acc->debit;
                    $total_credit += $acc->credit;
                ?>
                <tr>
                    <td style="border: 1px solid grey;padding: 5px;"><?= $acc->credit > 0 ? '&nbsp;&nbsp;&nbsp;&nbsp;'.$acc->account_name : $acc->account_name ?></td>
                    <td style="border: 1px solid grey;text-align: center;padding: 5px;"><?= $acc->account_code ?></td>
                    <td style="border: 1px solid grey;text-align: right;padding: 5px;"><?= $acc->debit > 0 ? number_format($acc->debit, 2) : '' ?></td>
                    <td style="border: 1px solid grey;width: 5%;border-left: none;"></td>
                    <td style="border: 1px solid grey;text-align: right;padding: 5px;"><?= $acc->credit > 0 ? number_format($acc->credit, 2) : '' ?></td>
                    <td style="border: 1px solid grey;width: 5%;border-left: none;"></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <?php for($xx=0;$xx<=3;$xx++):?>
            <tr>
                <td style="border: 1px solid grey;padding: 15px;"></td>
                <td style="border: 1px solid grey;text-align: center;"></td>
                <td style="border: 1px solid grey;text-align: center;"></td>
                <td style="border: 1px solid grey;width: 5%"></td>
                <td style="border: 1px solid grey;text-align: center;"></td>
                <td style="border: 1px solid grey;width: 5%"></td>
            </tr>
            <?php endfor;?>
        <?php endif; ?>
        <tr>
            <td colspan="2" style="border: 1px solid grey;padding: 5px;text-align: right;"><b>รวม</b></td>
            <td style="border: 1px solid grey;text-align: right;padding: 5px;"><b><?= number_format($total_debit, 2) ?></b></td>
            <td style="border: 1px solid grey;width: 5%;border-left: none;"></td>
            <td style="border: 1px solid grey;text-align: right;padding: 5px;"><b><?= number_format($total_credit, 2) ?></b></td>
            <td style="border: 1px solid grey;width: 5%;border-left: none;"></td>
        </tr>
    </table>
    <table style="width: 100%;border: 1px solid grey;">
        <tr>
            <td rowspan="2" style="width: 25%;border: 1px solid grey;text-align: center;padding: 5px;"><b>ผู้จัดทำ</b>
            </td>
            <td rowspan="2" style="width: 25%;border: 1px solid grey;text-align: center;padding: 5px;"><b>ผู้ตรวจสอบและอนุมัติ</b>
            </td>
            </td>
            <td colspan="2" style="width: 50%;border: 1px solid grey;text-align: center;padding: 5px;"><b>ผู้ผ่านรายการ</b>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid grey;text-align: center;padding: 5px;"><b>สมุดรายวันจ่ายเงิน</b></td>
            <td style="border: 1px solid grey;text-align: center;padding: 5px;"><b>บัญชีแยกประเภทย่อย</b></td>
        </tr>
        <tr>
            <td style="width: 25%;border: 1px solid grey;text-align: center;padding: 25px 20px 5px 20px;">__________________________________
            </td>
            <td style="width: 25%;border: 1px solid grey;text-align: center;padding: 25px 20px 5px 20px;">__________________________________
            </td>
            </td>
            <td style="width: 25%;border: 1px solid grey;text-align: center;padding: 25px 20px 5px 20px;">__________________________________
            </td>
            <td style="width: 25%;border: 1px solid grey;text-align: center;padding: 25px 20px 5px 20px;">__________________________________
            </td>
        </tr>
        <tr>
            <td style="width: 25%;border: 1px solid grey;text-align: center;padding: 25px 20px 5px 20px;">_________/____________/_____________
            </td>
            <td style="width: 25%;border: 1px solid grey;text-align: center;padding: 25px 20px 5px 20px;">_________/____________/_____________
            </td>
            </td>
            <td style="width: 25%;border: 1px solid grey;text-align: center;padding: 25px 20px 5px 20px;">_________/____________/_____________
            </td>
            <td style="width: 25%;border: 1px solid grey;text-align: center;padding: 25px 20px 5px 20px;">_________/____________/_____________
            </td>
        </tr>
    </table>

    <br>


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
