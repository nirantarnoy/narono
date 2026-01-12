<?php

namespace backend\controllers;

use backend\models\Company;
use backend\models\DropoffPlace;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\WorkQueueReportSearch;
use backend\models\Customer;

/**
 * WorkQueueReportController handles work queue report actions
 */
class WorkqueuereportController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all report data
     */
    public function actionIndex()
    {
        $searchModel = new WorkQueueReportSearch();

        // Set default dates to 1-15 of current month
        if (!Yii::$app->request->get('WorkQueueReportSearch')) {
            $searchModel->start_date = date('Y-m-01');
            $searchModel->end_date = date('Y-m-15');
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Get customers for dropdown
        $customers = Customer::find()
            ->select(['id', 'name', 'code'])
            ->orderBy(['name' => SORT_ASC])
            ->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'customers' => $customers,
        ]);
    }

    /**
     * Display detailed report view
     */
    public function actionReport()
    {
        $searchModel = new WorkQueueReportSearch();

        // Set default dates to 1-15 of current month
        if (!Yii::$app->request->get('WorkQueueReportSearch')) {
            $searchModel->start_date = date('Y-m-01');
            $searchModel->end_date = date('Y-m-15');
        }

        $reportData = $searchModel->getReportData(Yii::$app->request->queryParams);
        $summaryData = $searchModel->getSummaryData(Yii::$app->request->queryParams);

        // ดึงราคาน้ำมันของเดือนก่อนหน้า (ยึดตามเดือนของวันที่เริ่มต้นรายงาน)
        $targetDate = $searchModel->start_date ? $searchModel->start_date : date('Y-m-d');
        $firstDayPrevMonth = date('Y-m-01', strtotime('first day of last month', strtotime($targetDate)));
        $lastDayPrevMonth = date('Y-m-t', strtotime('first day of last month', strtotime($targetDate)));

        $fuelModel = \common\models\FuelPrice::find()
            ->where(['between', 'price_date', $firstDayPrevMonth, $lastDayPrevMonth])
            ->orderBy(['price_date' => SORT_DESC])
            ->one();
        
        $last_month_oil_price = $fuelModel ? $fuelModel->price : 0;

        // Get customers for dropdown
        $customers = Customer::find()
            ->select(['id', 'name', 'code'])
            ->orderBy(['name' => SORT_ASC])
            ->all();

        return $this->render('report', [
            'searchModel' => $searchModel,
            'reportData' => $reportData,
            'summaryData' => $summaryData,
            'customers' => $customers,
            'last_month_oil_price' => $last_month_oil_price, // ส่งค่าไปยัง view
        ]);
    }

    /**
     * Export report to PDF
     */
//    public function actionPdf()
//    {
//        $searchModel = new WorkQueueReportSearch();
//
//        if (!Yii::$app->request->get('WorkQueueReportSearch')) {
//            $searchModel->start_date = date('Y-m-01');
//            $searchModel->end_date = date('Y-m-15');
//        }
//
//        $reportData = $searchModel->getReportData(Yii::$app->request->queryParams);
//        $summaryData = $searchModel->getSummaryData(Yii::$app->request->queryParams);
//
//        $content = $this->renderPartial('_pdf', [
//            'searchModel' => $searchModel,
//            'reportData' => $reportData,
//            'summaryData' => $summaryData,
//        ]);
//
//        // Setup PDF
//        $pdf = new \kartik\mpdf\Pdf([
//            'mode' => \kartik\mpdf\Pdf::MODE_UTF8,
//            'format' => \kartik\mpdf\Pdf::FORMAT_A4,
//            'orientation' => \kartik\mpdf\Pdf::ORIENT_LANDSCAPE,
//            'destination' => \kartik\mpdf\Pdf::DEST_BROWSER,
//            'content' => $content,
//            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
//            'cssInline' => '
//                body { font-family: "Sarabun", sans-serif; }
//                .table { border-collapse: collapse; width: 100%; }
//                .table th, .table td { border: 1px solid #000; padding: 5px; }
//            ',
//            'options' => [
//                'title' => 'รายงานคิวงาน',
//                'defaultfooterline' => 0,
//            ],
//            'methods' => [
//                'SetHeader' => ['รายงานคิวงาน'],
//                'SetFooter' => ['{PAGENO}'],
//            ]
//        ]);
//
//        return $pdf->render();
//    }
//
//    /**
//     * Export report to Excel
//     */
//    public function actionExcel()
//    {
//        $searchModel = new WorkQueueReportSearch();
//
//        if (!Yii::$app->request->get('WorkQueueReportSearch')) {
//            $searchModel->start_date = date('Y-m-01');
//            $searchModel->end_date = date('Y-m-15');
//        }
//
//        $reportData = $searchModel->getReportData(Yii::$app->request->queryParams);
//        $summaryData = $searchModel->getSummaryData(Yii::$app->request->queryParams);
//
//        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
//        $sheet = $spreadsheet->getActiveSheet();
//
//        // Set headers
//        $sheet->setCellValue('A1', 'รายงานคิวงาน วันที่ ' .
//            Yii::$app->formatter->asDate($searchModel->start_date) . ' - ' .
//            Yii::$app->formatter->asDate($searchModel->end_date));
//
//        $sheet->setCellValue('A3', 'วันที่');
//        $sheet->setCellValue('B3', 'เลขที่คิว');
//        $sheet->setCellValue('C3', 'ลูกค้า');
//        $sheet->setCellValue('D3', 'จำนวน (ชิ้น)');
//        $sheet->setCellValue('E3', 'น้ำหนัก (กก.)');
//        $sheet->setCellValue('F3', 'ราคารวม (บาท)');
//
//        $row = 4;
//        foreach ($reportData as $data) {
//            $sheet->setCellValue('A' . $row, $data['work_queue_date']);
//            $sheet->setCellValue('B' . $row, $data['work_queue_no']);
//            $sheet->setCellValue('C' . $row, $data['customer_name']);
//            $sheet->setCellValue('D' . $row, $data['total_qty']);
//            $sheet->setCellValue('E' . $row, number_format($data['total_weight'], 2));
//            $sheet->setCellValue('F' . $row, number_format($data['total_price'], 2));
//            $row++;
//        }
//
//        // Summary row
//        $sheet->setCellValue('A' . $row, 'รวมทั้งหมด');
//        $sheet->setCellValue('D' . $row, $summaryData['grand_total_qty']);
//        $sheet->setCellValue('E' . $row, number_format($summaryData['grand_total_weight'], 2));
//        $sheet->setCellValue('F' . $row, number_format($summaryData['grand_total_price'], 2));
//
//        // Download
//        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
//
//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Disposition: attachment;filename="work_queue_report_' . date('Y-m-d') . '.xlsx"');
//        header('Cache-Control: max-age=0');
//
//        $writer->save('php://output');
//        exit;
//    }

    public function actionPdf()
    {
        $searchModel = new WorkQueueReportSearch();

        // Load search parameters exactly like actionReport
        $searchModel->load(Yii::$app->request->queryParams);

        // Set default dates if not provided
        if (!$searchModel->start_date) {
            $searchModel->start_date = date('Y-m-01');
        }
        if (!$searchModel->end_date) {
            $searchModel->end_date = date('Y-m-15');
        }

        $reportData = $searchModel->getReportData(Yii::$app->request->queryParams);
        $summaryData = $searchModel->getSummaryData(Yii::$app->request->queryParams);

        $content = $this->renderPartial('_pdf', [
            'searchModel' => $searchModel,
            'reportData' => $reportData,
            'summaryData' => $summaryData,
        ]);

        // Setup PDF
        $pdf = new \kartik\mpdf\Pdf([
            'mode' => \kartik\mpdf\Pdf::MODE_UTF8,
            'format' => \kartik\mpdf\Pdf::FORMAT_A4,
            'orientation' => \kartik\mpdf\Pdf::ORIENT_LANDSCAPE,
            'destination' => \kartik\mpdf\Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '
                body { font-family: "Calibri Light", "Sarabun", sans-serif; font-size: 12px; }
                .table { border-collapse: collapse; width: 100%; }
                .table th, .table td { border: 1px solid #000; padding: 5px; }
            ',
            'options' => [
                'title' => 'รายงานสรุปรายละเอียดขนส่ง',
                'defaultfooterline' => 0,
            ],
            'methods' => [
                'SetHeader' => ['รายงานสรุปรายละเอียดขนส่ง'],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }

    /**
     * Export report to Excel
     */
    public function actionExcel()
    {
        $searchModel = new WorkQueueReportSearch();

        // Load search parameters exactly like actionReport
        $searchModel->load(Yii::$app->request->queryParams);

        // Set default dates if not provided
        if (!$searchModel->start_date) {
            $searchModel->start_date = date('Y-m-01');
        }
        if (!$searchModel->end_date) {
            $searchModel->end_date = date('Y-m-15');
        }

        $reportData = $searchModel->getReportData(Yii::$app->request->queryParams);
        $summaryData = $searchModel->getSummaryData(Yii::$app->request->queryParams);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Get company name
        $companyName = Company::findCompanyName($searchModel->company_id);

        // Set title
        $sheet->setCellValue('A1', $companyName);
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Set date range
        $sheet->setCellValue('A2', 'รายงานวันที่ ' .
            Yii::$app->formatter->asDate($searchModel->start_date, 'php:d/m/Y') . ' - ' .
            Yii::$app->formatter->asDate($searchModel->end_date, 'php:d/m/Y'));
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Set headers
        $sheet->setCellValue('A4', 'รายการการขนส่ง');
        $sheet->setCellValue('B4', 'ปลายทาง');
        $sheet->setCellValue('C4', 'น้ำหนัก');
        $sheet->setCellValue('D4', 'Unit');
        $sheet->setCellValue('E4', 'อัตรา');
        $sheet->setCellValue('F4', 'จำนวนเงิน');
        $sheet->setCellValue('G4', 'PO.');
        $sheet->setCellValue('H4', 'GR');

        // Style headers
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8F4F8']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('A4:H4')->applyFromArray($headerStyle);

        $row = 5;
        $totalWeight = 0;
        $totalPrice = 0;

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

        foreach ($groupedData as $customerData) {
            foreach ($customerData['items'] as $item) {
                $weight = $item['total_weight'];
                $qty = $item['total_qty'];
                $price = ($weight * $qty);

                $totalWeight += $weight;
                $totalPrice += $price;

                // Get destination name
                $dest_name = '';
                $x = explode('-', DropoffPlace::findName($item['dropoff_id']));
                if (isset($x[1])) {
                    $dest_name = $x[1];
                }

                $sheet->setCellValue('A' . $row, $customerData['customer_name']);
                $sheet->setCellValue('B' . $row, $dest_name);
                $sheet->setCellValue('C' . $row, $weight);
                $sheet->setCellValue('D' . $row, 'ตัน');
                $sheet->setCellValue('E' . $row, $item['total_price_per_ton'] ?: 0);
                $sheet->setCellValue('F' . $row, $price);
                $sheet->setCellValue('G' . $row, $item['po_number']);
                $sheet->setCellValue('H' . $row, '');

                // Format numbers
                $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('#,##0.000');
                $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

                // Add borders
                $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $row++;
            }
        }

        // Summary row
        $sheet->setCellValue('A' . $row, 'รวมทั้งหมด');
        $sheet->setCellValue('B' . $row, '');
        $sheet->setCellValue('C' . $row, $summaryData['grand_total_weight']);
        $sheet->setCellValue('D' . $row, '');
        $sheet->setCellValue('E' . $row, '');
        $sheet->setCellValue('F' . $row, $summaryData['grand_total_price']);
        $sheet->setCellValue('G' . $row, '');
        $sheet->setCellValue('H' . $row, '');

        // Format summary
        $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('#,##0.000');
        $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

        // Style summary row
        $summaryStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9EDF7']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray($summaryStyle);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(35);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);

        // Align cells
        $sheet->getStyle('B5:B' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C5:C' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('D5:D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E5:E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('F5:F' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('G5:G' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H5:H' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Download
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $filename = 'รายงานสรุปรายละเอียดขนส่ง_' . date('Y-m-d_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}