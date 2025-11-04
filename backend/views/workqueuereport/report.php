<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WorkQueueReportSearch */
/* @var $reportData array */
/* @var $summaryData array */
/* @var $customers array */

$this->title = 'รายงานตรวจสอบสรุปรอบขนส่ง';
$this->registerCssFile('https://fonts.cdnfonts.com/css/calibri-light', [
    'depends' => [\yii\web\YiiAsset::class],
]);
?>
<link href="https://fonts.cdnfonts.com/css/calibri-light" rel="stylesheet">
<div class="work-queue-report">

    <style>
        /*body {*/
        /*    font-family: "Calibri Light", sans-serif;*/
        /*    font-size: 14px;*/
        /*}*/

        .report-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            font-family: "Calibri Light", sans-serif;
            font-size: 14px;
        }

        .report-header h2 {
            margin: 5px 0;
            font-size: 20px;
            font-weight: bold;
        }

        .report-header .date-range {
            font-size: 14px;
            color: #666;
        }

        .report-filter {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-family: "Calibri Light", sans-serif;
            font-size: 14px;
        }

        .report-table th {
            background-color: #e8f4f8;
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: center;
            font-weight: bold;
        }

        .report-table td {
            border: 1px solid #000;
            padding: 5px 8px;
        }

        .report-table .text-right {
            text-align: right;
        }

        .report-table .text-center {
            text-align: center;
        }

        .report-table .total-row {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .oil-label {
            float: right;
            margin-right: 20px;
            padding: 5px 10px;
            background-color: #fff;
            border: 1px solid #000;
        }

        .summary-box {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #000;
            font-family: "Calibri Light", sans-serif;
            font-size: 14px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                font-size: 12px;
            }
        }
    </style>

    <div class="report-filter no-print">
        <?php $form = ActiveForm::begin([
            'action' => ['report'],
            'method' => 'get',
            'options' => ['class' => ''],
        ]); ?>
        <table style="width: 100%">
            <tr>
                <td style="width: 15%">
                    <label>วันที่เริ่มต้น: </label>
                    <?= $form->field($searchModel, 'start_date')->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => 'วันที่เริ่มต้น'],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ])->label(false) ?>
                </td>
                <td style="width: 15%">
                    <label>วันที่สิ้นสุด: </label>
                    <?= $form->field($searchModel, 'end_date')->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => 'วันที่สิ้นสุด'],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ])->label(false) ?>
                </td>
                <td style="width: 20%">
                    <label>บริษัท: </label>
                    <?= $form->field($searchModel, 'company_id')->widget(\kartik\select2\Select2::classname(), [
                        'data'=>ArrayHelper::map(\backend\models\Company::find()->all(),'id','name'),
                        'options'=>[
                            'placeholder'=>'--เลือกบริษัท--',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ])->label(false) ?>
                </td>
                <td style="width: 20%">
                    <label>ประเภทงาน: </label>
                    <?= $form->field($searchModel, 'work_queue_type')->widget(\kartik\select2\Select2::classname(), [
                        'data'=>ArrayHelper::map(\backend\models\Customer::find()->all(),'id','name'),
                        'options'=>[
                            'placeholder'=>'--เลือกประเภทงาน--',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ])->label(false) ?>
                </td>
                <td style="width: 20%">
                    <label>ลูกค้า: </label>
                    <?= $form->field($searchModel, 'customer_id')->widget(\kartik\select2\Select2::classname(), [
                        'data'=>ArrayHelper::map(\backend\models\Customer::find()->all(),'id','name'),
                        'options'=>[
                            'placeholder'=>'--เลือกลูกค้า--',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ])->label(false) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= Html::submitButton('ค้นหา', ['class' => 'btn btn-primary']) ?>
                    <?= Html::button('พิมพ์', ['class' => 'btn btn-default', 'onclick' => 'window.print()']) ?>
                    <?= Html::a('Export Excel',['excel'],['class'=>'btn btn-success'])?>
                </td>
                <td>

                </td>
            </tr>
        </table>


        <!--        --><?php //= Html::a('PDF', ['pdf', 'WorkQueueReportSearch' => [
        //            'start_date' => $searchModel->start_date,
        //            'end_date' => $searchModel->end_date,
        //            'customer_id' => $searchModel->customer_id,
        //        ]], ['class' => 'btn btn-danger', 'target' => '_blank']) ?>
        <!--        --><?php //= Html::a('Excel', ['excel', 'WorkQueueReportSearch' => [
        //            'start_date' => $searchModel->start_date,
        //            'end_date' => $searchModel->end_date,
        //            'customer_id' => $searchModel->customer_id,
        //        ]], ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
    </div>

    <div class="report-header">
        <h2><?=\backend\models\Company::findCompanyName($searchModel->company_id)?></h2>
        <div class="date-range">
            <strong>รายงานวันที่ <?= Yii::$app->formatter->asDate($searchModel->start_date, 'php:d/m/Y') ?>
                - <?= Yii::$app->formatter->asDate($searchModel->end_date, 'php:d/m/Y') ?></strong>
        </div>
        <!--        <div class="oil-label">-->
        <!--            oil: --><?php //= number_format($summaryData['grand_total_weight'] / 1000, 2) ?>
        <!--        </div>-->
    </div>

    <table class="report-table">
        <thead>
        <tr>
            <th style="width: 200px;">รายการการขนส่ง</th>
            <th style="width: 50px;">ปลายทาง</th>
            <th style="width: 20px;">น้ำหนัก</th>
            <th style="width: 80px;">Unit</th>
            <th style="width: 80px;">อัตรา</th>
            <th style="width: 120px;">จำนวนเงิน</th>
            <th style="width: 150px;">PO.</th>
            <th style="width: 150px;">GR</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $totalWeight = 0;
        $totalQty = 0;
        $totalPrice = 0;
        $no = 1;

        // Group data by customer
        $groupedData = [];
        foreach ($reportData as $data) {
            $customerKey = $data['customer_id'];
            if (!isset($groupedData[$customerKey])) {
                $groupedData[$customerKey] = [
                    'customer_name' => $data['customer_name'],
                    'items' => []
                ];
            }
            $groupedData[$customerKey]['items'][] = $data;
        }

        foreach ($groupedData as $customerData):
            $customerTotalWeight = 0;
            $customerTotalQty = 0;
            $customerTotalPrice = 0;

            foreach ($customerData['items'] as $item):
                $weight = $item['total_weight'];
                $qty = $item['total_qty'];
                $price = ($weight * $qty);
                //  $price = $item['total_price'];

                $customerTotalWeight += $weight;
                $customerTotalQty += $qty;
                $customerTotalPrice += $price;

                $totalWeight += $weight;
                $totalQty += $qty;
                $totalPrice += $price;

                $dest_name = '';
                $x=explode('-',\backend\models\DropoffPlace::findName($item['dropoff_id']));
                if(isset($x[1])){
                    $dest_name = $x[1];
                }
                ?>
                <tr>
                    <td><?= Html::encode($customerData['customer_name']) ?></td>
                    <td class="text-center"><?=$dest_name?></td>
                    <td class="text-right"><?= number_format($weight, 3) ?></td>
                    <td class="text-center">ตัน</td>
                    <td class="text-right"><?= $item['total_price_per_ton'] ? number_format($item['total_price_per_ton'], 2) : 0 ?></td>
                    <td class="text-right"><?= number_format($price, 2) ?></td>
                    <td class="text-center"><?= Html::encode($item['po_number']) ?></td>
                    <td class="text-center"></td>
                </tr>
            <?php
            endforeach;

            // Customer subtotal row
            if (count($customerData['items']) > 1):
                ?>
                <!--                <tr class="total-row">-->
                <!--                    <td class="text-right">รวม --><?php //= Html::encode($customerData['customer_name'])
                ?><!--</td>-->
                <!--                    <td class="text-right">--><?php //= number_format($customerTotalWeight, 2)
                ?><!--</td>-->
                <!--                    <td class="text-right">--><?php //= number_format($customerTotalQty)
                ?><!--</td>-->
                <!--                    <td colspan="2"></td>-->
                <!--                    <td class="text-right">--><?php //= number_format($customerTotalPrice, 2)
                ?><!--</td>-->
                <!--                    <td colspan="2"></td>-->
                <!--                </tr>-->
            <?php
            endif;
        endforeach;
        ?>
        </tbody>
        <tfoot>
        <tr class="total-row" style="background-color: #d9edf7;">
            <td class="text-right"><strong>รวมทั้งหมด</strong></td>
            <td class="text-right"><strong></td>
            <td class="text-right"><strong><?= number_format($summaryData['grand_total_weight'], 3) ?></strong></td>
            <td></td>
            <td></td>
            <td class="text-right"><strong><?= number_format($summaryData['grand_total_price'], 2) ?></strong></td>
            <td></td>
            <td></td>
        </tr>
        </tfoot>
    </table>

</div>