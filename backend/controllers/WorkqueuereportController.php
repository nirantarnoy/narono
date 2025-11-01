<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\WorkQueueReportSearch;
use backend\models\Customer;

/**
 * WorkQueueReportController handles work queue report actions
 */
class WorkQueueReportController extends Controller
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
        ]);
    }

    /**
     * Export report to PDF
     */
    public function actionPdf()
    {
        $searchModel = new WorkQueueReportSearch();

        if (!Yii::$app->request->get('WorkQueueReportSearch')) {
            $searchModel->start_date = date('Y-m-01');
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
                body { font-family: "Sarabun", sans-serif; }
                .table { border-collapse: collapse; width: 100%; }
                .table th, .table td { border: 1px solid #000; padding: 5px; }
            ',
            'options' => [
                'title' => 'รายงานคิวงาน',
                'defaultfooterline' => 0,
            ],
            'methods' => [
                'SetHeader' => ['รายงานคิวงาน'],
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

        if (!Yii::$app->request->get('WorkQueueReportSearch')) {
            $searchModel->start_date = date('Y-m-01');
            $searchModel->end_date = date('Y-m-15');
        }

        $reportData = $searchModel->getReportData(Yii::$app->request->queryParams);
        $summaryData = $searchModel->getSummaryData(Yii::$app->request->queryParams);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'รายงานคิวงาน วันที่ ' .
            Yii::$app->formatter->asDate($searchModel->start_date) . ' - ' .
            Yii::$app->formatter->asDate($searchModel->end_date));

        $sheet->setCellValue('A3', 'วันที่');
        $sheet->setCellValue('B3', 'เลขที่คิว');
        $sheet->setCellValue('C3', 'ลูกค้า');
        $sheet->setCellValue('D3', 'จำนวน (ชิ้น)');
        $sheet->setCellValue('E3', 'น้ำหนัก (กก.)');
        $sheet->setCellValue('F3', 'ราคารวม (บาท)');

        $row = 4;
        foreach ($reportData as $data) {
            $sheet->setCellValue('A' . $row, $data['work_queue_date']);
            $sheet->setCellValue('B' . $row, $data['work_queue_no']);
            $sheet->setCellValue('C' . $row, $data['customer_name']);
            $sheet->setCellValue('D' . $row, $data['total_qty']);
            $sheet->setCellValue('E' . $row, number_format($data['total_weight'], 2));
            $sheet->setCellValue('F' . $row, number_format($data['total_price'], 2));
            $row++;
        }

        // Summary row
        $sheet->setCellValue('A' . $row, 'รวมทั้งหมด');
        $sheet->setCellValue('D' . $row, $summaryData['grand_total_qty']);
        $sheet->setCellValue('E' . $row, number_format($summaryData['grand_total_weight'], 2));
        $sheet->setCellValue('F' . $row, number_format($summaryData['grand_total_price'], 2));

        // Download
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="work_queue_report_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}