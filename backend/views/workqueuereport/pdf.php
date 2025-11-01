<?php
/* @var $searchModel backend\models\WorkQueueReportSearch */
/* @var $reportData array */
/* @var $summaryData array */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: "Sarabun", "TH SarabunPSK", sans-serif;
            font-size: 12px;
        }
        .report-header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .report-header h2 {
            margin: 5px 0;
            font-size: 18px;
            font-weight: bold;
        }
        .oil-info {
            text-align: right;
            font-size: 14px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th {
            background-color: #e8f4f8;
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
        }
        td {
            border: 1px solid #000;
            padding: 4px 6px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .grand-total {
            background-color: #d9edf7;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="report-header">
    <h2>บริษัท โตรีลิสต์</h2>
    <div>
        <strong>รายงานวันที่ <?= Yii::$app->formatter->asDate($searchModel->start_date, 'php:d/m/Y') ?>
            - <?= Yii::$app->formatter->asDate($searchModel->end_date, 'php:d/m/Y') ?></strong>
    </div>
</div>

<div class="oil-info">
    oil: <?= number_format($summaryData['grand_total_weight'] / 1000, 2) ?>
</div>

<table>
    <thead>
    <tr>
        <th rowspan="2" style="width: 30px;">ลำดับ</th>
        <th rowspan="2" style="width: 180px;">รายการวัตถุดิบ</th>
        <th colspan="2">ประจำเดือน</th>
        <th rowspan="2" style="width: 60px;">Unit</th>
        <th rowspan="2" style="width: 70px;">อัตรา</th>
        <th rowspan="2" style="width: 100px;">จำนวนเงิน</th>
        <th rowspan="2" style="width: 120px;">PO.</th>
        <th rowspan="2" style="width: 120px;">GR</th>
    </tr>
    <tr>
        <th style="width: 70px;">น้ำหนัก</th>
        <th style="width: 70px;">จำนวน</th>
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
            $price = $item['total_price'];

            $customerTotalWeight += $weight;
            $customerTotalQty += $qty;
            $customerTotalPrice += $price;

            $totalWeight += $weight;
            $totalQty += $qty;
            $totalPrice += $price;
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= htmlspecialchars($customerData['customer_name']) ?></td>
                <td class="text-right"><?= number_format($weight, 2) ?></td>
                <td class="text-right"><?= number_format($qty) ?></td>
                <td class="text-center">ชิ้น</td>
                <td class="text-right"><?= $item['total_price_per_ton'] ? number_format($item['total_price_per_ton'], 2) : '-' ?></td>
                <td class="text-right"><?= number_format($price, 2) ?></td>
                <td class="text-center"><?= htmlspecialchars($item['work_queue_no']) ?></td>
                <td class="text-center"><?= htmlspecialchars($item['id']) ?></td>
            </tr>
        <?php
        endforeach;

        // Customer subtotal
        if (count($customerData['items']) > 1):
            ?>
            <tr class="total-row">
                <td colspan="2" class="text-right">รวม <?= htmlspecialchars($customerData['customer_name']) ?></td>
                <td class="text-right"><?= number_format($customerTotalWeight, 2) ?></td>
                <td class="text-right"><?= number_format($customerTotalQty) ?></td>
                <td colspan="2"></td>
                <td class="text-right"><?= number_format($customerTotalPrice, 2) ?></td>
                <td colspan="2"></td>
            </tr>
        <?php
        endif;
    endforeach;
    ?>
    </tbody>
    <tfoot>
    <tr class="grand-total">
        <td colspan="2" class="text-right">รวมทั้งหมด</td>
        <td class="text-right"><?= number_format($summaryData['grand_total_weight'], 2) ?></td>
        <td class="text-right"><?= number_format($summaryData['grand_total_qty']) ?></td>
        <td colspan="2"></td>
        <td class="text-right"><?= number_format($summaryData['grand_total_price'], 2) ?></td>
        <td colspan="2"></td>
    </tr>
    </tfoot>
</table>

<div style="margin-top: 20px; font-size: 11px;">
    <p><strong>หมายเหตุ:</strong></p>
    <p>- รายงานนี้แสดงข้อมูลวันที่ <?= Yii::$app->formatter->asDate($searchModel->start_date, 'php:d/m/Y') ?>
        ถึง <?= Yii::$app->formatter->asDate($searchModel->end_date, 'php:d/m/Y') ?></p>
    <p>- จำนวนรายการทั้งหมด: <?= count($reportData) ?> รายการ</p>
    <p style="text-align: right;">วันที่พิมพ์: <?= date('d/m/Y H:i:s') ?></p>
</div>
</body>
</html>