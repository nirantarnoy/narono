<?php

namespace backend\controllers;

use Yii;
use backend\models\TaxImport;
use backend\models\TaxImportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * TaximportController implements the CRUD actions for TaxImport model.
 */
class TaximportController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all TaxImport models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TaxImportSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->pagination->pageSize = 100;
        $dataProvider->setSort([
            'defaultOrder' => [
                'sequence' => SORT_ASC,
            ],
        ]);

        $model = new TaxImport();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Creates or Updates a TaxImport model via AJAX.
     */
    public function actionSave()
    {
        try {
            $postData = Yii::$app->request->post('TaxImport');
            if (!$postData) {
                return $this->asJson(['status' => 'error', 'message' => 'ไม่พบข้อมูลที่ส่งมา']);
            }
            
            $id = $postData['id'] ?? null;
            if ($id) {
                $model = $this->findModel($id);
            } else {
                $model = new TaxImport();
            }

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->asJson(['status' => 'success', 'message' => 'บันทึกข้อมูลเรียบร้อยแล้ว']);
            }

            $errors = $model->getErrors();
            return $this->asJson(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้', 'errors' => $errors]);
        } catch (\Throwable $e) {
            return $this->asJson(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดทางเทคนิค: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine()]);
        }
    }

    /**
     * Gets data for a single record.
     */
    public function actionGetData($id)
    {
        $model = $this->findModel($id);
        return $this->asJson($model);
    }

    /**
     * Deletes an existing TaxImport model.
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Imports data from Excel.
     */
    public function actionImport()
    {
        $file = \yii\web\UploadedFile::getInstanceByName('import_file');
        if ($file) {
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->tempName);
                $sheet = $spreadsheet->getActiveSheet();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Mapping table based on the first specification file (A-U)
                $fieldMap = [
                    'A' => 'sequence',
                    'B' => 'doc_date',
                    'C' => 'reference_no',
                    'D' => 'vendor_name',
                    'E' => 'tax_id',
                    'F' => 'branch_code',
                    'G' => 'tax_invoice_no',
                    'H' => 'tax_invoice_date',
                    'I' => 'tax_record_date',
                    'J' => 'price_type',
                    'K' => 'account_code',
                    'L' => 'description',
                    'M' => 'qty',
                    'N' => 'unit_price',
                    'O' => 'tax_rate',
                    'P' => 'wht_amount',
                    'Q' => 'paid_by',
                    'R' => 'paid_amount',
                    'S' => 'pnd_type',
                    'T' => 'remarks',
                    'U' => 'group_type',
                ];

                // Get dynamic mappings from row 1
                $columnMappings = [];
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
                
                for ($i = 1; $i <= $highestColumnIndex; $i++) {
                    $col = Coordinate::stringFromColumnIndex($i);
                    $val = trim((string)$sheet->getCell($col . '1')->getValue());
                    if ($val) {
                        $parts = preg_split('/[\s&\/]+/', $val);
                        foreach ($parts as $part) {
                            $letter = strtoupper(trim($part));
                            if (strlen($letter) === 1 && isset($fieldMap[$letter])) {
                                $columnMappings[$col][] = $fieldMap[$letter];
                            }
                        }
                    }
                }

                if (empty($columnMappings)) {
                    Yii::error("Import Error: No mappings found in row 1.", 'tax_import');
                    throw new \Exception("ไม่พบตัวอักษร Mapping (A-U) ในบรรทัดที่ 1 ของไฟล์ Excel");
                }

                $successCount = 0;
                $errorCount = 0;
                $skipCount = 0;
                $errorDetails = [];
                $currentRowSequence = 1;

                for ($row = 3; $row <= $highestRow; $row++) {
                    $hasData = false;
                    foreach (array_keys($columnMappings) as $col) {
                        if (!empty($sheet->getCell($col . $row)->getValue())) {
                            $hasData = true;
                            break;
                        }
                    }
                    if (!$hasData) continue;

                    $model = new TaxImport();
                    $model->price_type = 1; 
                    $model->sequence = $currentRowSequence++; // Default sequence
                    
                    foreach ($columnMappings as $col => $fields) {
                        $cell = $sheet->getCell($col . $row);
                        $value = $cell->getValue();
                        
                        foreach ($fields as $field) {
                            if (in_array($field, ['doc_date', 'tax_invoice_date', 'tax_record_date'])) {
                                if ($value) {
                                    $formattedDate = null;
                                    if (is_numeric($value)) {
                                        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                                        $formattedDate = $date->format('Ymd');
                                    } else {
                                        $cleanValue = trim((string)$value);
                                        // Try various formats
                                        $date = \DateTime::createFromFormat('d/m/Y', $cleanValue);
                                        if (!$date) $date = \DateTime::createFromFormat('d-m-Y', $cleanValue);
                                        if (!$date) $date = \DateTime::createFromFormat('Y-m-d', $cleanValue);
                                        
                                        if ($date) {
                                            $formattedDate = $date->format('Ymd');
                                        } else {
                                            // Fallback: just keep digits
                                            $formattedDate = preg_replace('/[^0-9]/', '', $cleanValue);
                                            if (strlen($formattedDate) > 8) $formattedDate = substr($formattedDate, 0, 8);
                                        }
                                    }
                                    $model->$field = $formattedDate;
                                }
                            } elseif (in_array($field, ['qty', 'unit_price', 'paid_amount', 'sequence'])) {
                                if ($field == 'sequence' && !empty($value)) {
                                    $model->sequence = (int)$value;
                                } else {
                                    $model->$field = is_numeric($value) ? $value : 0;
                                }
                            } else {
                                $model->$field = trim((string)$value);
                            }
                        }
                    }

                    // Check for duplicate reference_no and branch_code
                    if (!empty($model->reference_no)) {
                        $existing = TaxImport::find()
                            ->where(['reference_no' => $model->reference_no, 'branch_code' => $model->branch_code])
                            ->exists();
                        if ($existing) {
                            $skipCount++;
                            continue;
                        }
                    }

                    if ($model->save()) {
                        $successCount++;
                    } else {
                        if ($model->save(false)) {
                            $successCount++;
                        } else {
                            $errorCount++;
                            $errors = $model->getErrors();
                            Yii::error("Row $row Save Error: " . json_encode($errors), 'tax_import');
                            $errorMsg = "";
                            foreach($errors as $attr => $err) $errorMsg .= $attr . ": " . $err[0] . " ";
                            $errorDetails[] = "แถวที่ $row: $errorMsg";
                            if (count($errorDetails) > 3) break;
                        }
                    }
                }

                $summary = "<b>สรุปผลการนำเข้า:</b><br>";
                $summary .= "- สำเร็จ: $successCount รายการ<br>";
                $summary .= "- ซ้ำ (ข้ามไป): $skipCount รายการ<br>";
                if ($errorCount > 0) {
                    $summary .= "- ผิดพลาด: $errorCount รายการ<br>";
                }

                Yii::$app->session->setFlash('success', $summary);

                if ($errorCount > 0) {
                    Yii::$app->session->setFlash('error', "รายละเอียดข้อผิดพลาด:<br>" . implode('<br>', $errorDetails));
                }
                
                if ($successCount == 0 && $skipCount == 0 && $errorCount == 0) {
                    Yii::$app->session->setFlash('warning', "ไม่พบข้อมูลในแถวที่ 3");
                }

            } catch (\Exception $e) {
                Yii::error("Import Exception: " . $e->getMessage(), 'tax_import');
                Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
            }
        } else {
            Yii::$app->session->setFlash('error', 'กรุณาเลือกไฟล์');
        }

        return $this->redirect(['index']);
    }

    /**
     * Exports data to Excel.
     */
    public function actionExport()
    {
        $searchModel = new TaxImportSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->pagination = false;
        $models = $dataProvider->getModels();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'A' => 'ลำดับที่',
            'B' => 'วันที่เอกสาร',
            'C' => 'อ้างอิงถึง',
            'D' => 'ผู้รับเงิน/คู่ค้า',
            'E' => 'เลขทะเบียน 13 หลัก',
            'F' => 'เลขสาขา 5 หลัก',
            'G' => 'เลขที่ใบกำกับฯ',
            'H' => 'วันที่ใบกำกับฯ',
            'I' => 'วันที่บันทึกภาษีซื้อ',
            'J' => 'ประเภทราคา',
            'K' => 'บัญชี',
            'L' => 'คำอธิบาย',
            'M' => 'จำนวน',
            'N' => 'ราคาต่อหน่วย',
            'O' => 'อัตราภาษี',
            'P' => 'หัก ณ ที่จ่าย',
            'Q' => 'ชำระโดย',
            'R' => 'จำนวนเงินที่ชำระ',
            'S' => 'ภ.ง.ด.',
            'T' => 'หมายเหตุ',
            'U' => 'กลุ่มจัดประเภท',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue($col . '1', $label);
        }

        // Style headers
        $sheet->getStyle('A1:U1')->getFont()->setBold(true);
        $sheet->getStyle('A1:U1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $row = 2;
        foreach ($models as $model) {
            $sheet->setCellValue('A' . $row, $model->sequence);
            $sheet->setCellValue('B' . $row, $model->doc_date);
            $sheet->setCellValue('C' . $row, $model->reference_no);
            $sheet->setCellValue('D' . $row, $model->vendor_name);
            $sheet->setCellValue('E' . $row, $model->tax_id);
            $sheet->setCellValue('F' . $row, $model->branch_code);
            $sheet->setCellValue('G' . $row, $model->tax_invoice_no);
            $sheet->setCellValue('H' . $row, $model->tax_invoice_date);
            $sheet->setCellValue('I' . $row, $model->tax_record_date);
            $sheet->setCellValue('J' . $row, $model->price_type);
            $sheet->setCellValue('K' . $row, $model->account_code);
            $sheet->setCellValue('L' . $row, $model->description);
            $sheet->setCellValue('M' . $row, $model->qty);
            $sheet->setCellValue('N' . $row, $model->unit_price);
            $sheet->setCellValue('O' . $row, $model->tax_rate);
            $sheet->setCellValue('P' . $row, $model->wht_amount);
            $sheet->setCellValue('Q' . $row, $model->paid_by);
            $sheet->setCellValue('R' . $row, $model->paid_amount);
            $sheet->setCellValue('S' . $row, $model->pnd_type);
            $sheet->setCellValue('T' . $row, $model->remarks);
            $sheet->setCellValue('U' . $row, $model->group_type);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'U') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'tax_import_' . date('Y-m-d_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Exports pattern for Excel import.
     */
    public function actionExportPattern()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Pattern Mapping at Row 1
        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U'];
        foreach ($letters as $col) {
            $sheet->setCellValue($col . '1', $col);
        }
        $sheet->getStyle('A1:U1')->getFont()->setBold(true);
        $sheet->getStyle('A1:U1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Headers at Row 2
        $headers = [
            'A' => 'ลำดับที่',
            'B' => 'วันที่เอกสาร',
            'C' => 'อ้างอิงถึง',
            'D' => 'ผู้รับเงิน/คู่ค้า',
            'E' => 'เลขทะเบียน 13 หลัก',
            'F' => 'เลขสาขา 5 หลัก',
            'G' => 'เลขที่ใบกำกับฯ',
            'H' => 'วันที่ใบกำกับฯ',
            'I' => 'วันที่บันทึกภาษีซื้อ',
            'J' => 'ประเภทราคา',
            'K' => 'บัญชี',
            'L' => 'คำอธิบาย',
            'M' => 'จำนวน',
            'N' => 'ราคาต่อหน่วย',
            'O' => 'อัตราภาษี',
            'P' => 'หัก ณ ที่จ่าย',
            'Q' => 'ชำระโดย',
            'R' => 'จำนวนเงินที่ชำระ',
            'S' => 'ภ.ง.ด.',
            'T' => 'หมายเหตุ',
            'U' => 'กลุ่มจัดประเภท',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue($col . '2', $label);
        }

        // Style headers
        $sheet->getStyle('A2:U2')->getFont()->setBold(true);
        $sheet->getStyle('A2:U2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Auto size columns
        foreach (range('A', 'U') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'tax_import_pattern.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Finds the TaxImport model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = TaxImport::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
