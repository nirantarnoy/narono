<?php

namespace backend\controllers;
date_default_timezone_set('Asia/Bangkok');

use backend\models\Cashrecord;
use backend\models\CashrecordSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * CashrecordController implements the CRUD actions for Cashrecord model.
 */
class CashrecordController extends Controller
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
                        'delete' => ['POST', 'GET'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Cashrecord models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $pageSize = \Yii::$app->request->post("perpage");

        $searchModel = new CashrecordSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->pagination->pageSize = $pageSize;
        $dataProvider->setSort(['defaultOrder' => ['id' => SORT_DESC]]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'perpage' => $pageSize,
        ]);
    }

    /**
     * Displays a single Cashrecord model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cashrecord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Cashrecord();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $trans_date = date('Y-m-d');
                $x = explode('-', $model->trans_date);
                if (count($x) > 1) {
                    $trans_date = $x[2] . '/' . $x[1] . '/' . $x[0];
                }

                $model->trans_date = date('Y-m-d', strtotime($trans_date));
                $model->journal_no = $model->getLastNo();


                $cost_title_id = \Yii::$app->request->post('cost_title_id');
                $amount = \Yii::$app->request->post('price_line');
                $vat_amount = \Yii::$app->request->post('vat_per_line');
                $remark = \Yii::$app->request->post('remark_line');
                $status = \Yii::$app->request->post('status');

                $model->status = $status;
                if ($model->save(false)) {
                    if ($cost_title_id != null) {
                        for ($i = 0; $i <= count($cost_title_id) - 1; $i++) {
                            $model_line = new \common\models\CashRecordLine();
                            $model_line->car_record_id = $model->id;
                            $model_line->cost_title_id = $cost_title_id[$i];
                            $model_line->amount = $amount[$i];
                            $model_line->vat_amount = $vat_amount[$i];
                            $model_line->remark = $remark[$i];
                            $model_line->status = 1;
                            $model_line->save(false);

                        }
                    }

                    $acc_name = \Yii::$app->request->post('account_name_line');
                    $acc_code = \Yii::$app->request->post('account_code_line');
                    $debit = \Yii::$app->request->post('debit_line');
                    $credit = \Yii::$app->request->post('credit_line');
                    if ($acc_name != null) {
                        for ($i = 0; $i <= count($acc_name) - 1; $i++) {
                            if ($acc_name[$i] == '') continue;
                            $model_acc = new \common\models\CashRecordAccount();
                            $model_acc->cash_record_id = $model->id;
                            $model_acc->account_name = $acc_name[$i];
                            $model_acc->account_code = $acc_code[$i];
                            $model_acc->debit = $debit[$i];
                            $model_acc->credit = $credit[$i];
                            $model_acc->save(false);
                        }
                    }

                    // create transaction

                    $model_trans = new \backend\models\Stocktrans();
                    $model_trans->trans_date = date('Y-m-d H:i:s');
                    $model_trans->activity_type_id = 5; // cash record
                    $model_trans->trans_ref_id = $model->id;
                    $model_trans->save(false);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cashrecord model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model_line = \common\models\CashRecordLine::find()->where(['car_record_id' => $id])->all();

        if ($this->request->isPost && $model->load($this->request->post())) {

            $cost_title_id = \Yii::$app->request->post('cost_title_id');
            $amount = \Yii::$app->request->post('price_line');
            $vat_amount = \Yii::$app->request->post('vat_per_line');
            $remark = \Yii::$app->request->post('remark_line');
            $line_id = \Yii::$app->request->post('rec_id');

            $removelist = \Yii::$app->request->post('remove_list');
            $removelist_account = \Yii::$app->request->post('remove_account_list');

            $trans_date = date('Y-m-d');
            $x = explode('-', $model->trans_date);
            if (count($x) > 1) {
                $trans_date = $x[2] . '/' . $x[1] . '/' . $x[0];
            }

            $model->trans_date = date('Y-m-d', strtotime($trans_date));

            if ($model->save(false)) {
                if ($line_id != null) {
                    for ($i = 0; $i <= count($line_id) - 1; $i++) {
                        $model_chk = \common\models\CashRecordLine::find()->where(['id' => $line_id[$i]])->one();
                        if ($model_chk) {
                            $model_chk->cost_title_id = $cost_title_id[$i];
                            $model_chk->amount = $amount[$i];
                            $model_chk->vat_amount = $vat_amount[$i];
                            $model_chk->remark = $remark[$i];
                            $model_chk->save(false);
                        } else {
                            $model_rec = new \common\models\CashRecordLine();
                            $model_rec->car_record_id = $model->id;
                            $model_rec->cost_title_id = $cost_title_id[$i];
                            $model_rec->amount = $amount[$i];
                            $model_rec->vat_amount = $vat_amount[$i];
                            $model_rec->remark = $remark[$i];
                            $model_rec->status = 1;
                            $model_rec->save(false);
                        }
                    }
                }

                $acc_name = \Yii::$app->request->post('account_name_line');
                $acc_code = \Yii::$app->request->post('account_code_line');
                $debit = \Yii::$app->request->post('debit_line');
                $credit = \Yii::$app->request->post('credit_line');
                $acc_rec_id = \Yii::$app->request->post('acc_rec_id');

                if ($acc_name != null) {
                    for ($i = 0; $i <= count($acc_name) - 1; $i++) {
                        if ($acc_name[$i] == '') continue;
                        $model_acc_chk = null;
                        if (isset($acc_rec_id[$i])) {
                            $model_acc_chk = \common\models\CashRecordAccount::findOne($acc_rec_id[$i]);
                        }

                        if ($model_acc_chk) {
                            $model_acc_chk->account_name = $acc_name[$i];
                            $model_acc_chk->account_code = $acc_code[$i];
                            $model_acc_chk->debit = $debit[$i];
                            $model_acc_chk->credit = $credit[$i];
                            $model_acc_chk->save(false);
                        } else {
                            $model_acc = new \common\models\CashRecordAccount();
                            $model_acc->cash_record_id = $model->id;
                            $model_acc->account_name = $acc_name[$i];
                            $model_acc->account_code = $acc_code[$i];
                            $model_acc->debit = $debit[$i];
                            $model_acc->credit = $credit[$i];
                            $model_acc->save(false);
                        }
                    }
                }

                $delete_rec = explode(",", $removelist);
                if (count($delete_rec) && $removelist != '') {
                    \common\models\CashRecordLine::deleteAll(['id' => $delete_rec]);
                }
                $delete_acc = explode(",", $removelist_account);
                if (count($delete_acc) && $removelist_account != '') {
                    \common\models\CashRecordAccount::deleteAll(['id' => $delete_acc]);
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'model_line' => $model_line,
        ]);
    }

    /**
     * Deletes an existing Cashrecord model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Cashrecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Cashrecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cashrecord::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionApprove($id)
    {
        if ($id != null) {
            \backend\models\Cashrecord::updateAll(['status' => 2], ['id' => $id]);

        }
        return $this->redirect(['index']);
    }

    function actionPrint($id)
    {
        $model = \backend\models\Cashrecord::find()->where(['id' => $id])->one();
        $model_line = \common\models\CashRecordLine::find()->where(['car_record_id' => $id])->all();
        return $this->render('_print', [
            'model' => $model,
            'model_line' => $model_line,
        ]);
    }

    public function actionExportPattern()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'A' => 'วันที่(Y-m-d)',
            'B' => 'จ่ายให้',
            'C' => 'ทะเบียนรถ(ระบุรายการเดียวถ้ามี)',
            'D' => 'จ่ายโดย(ธนาคาร/เบอร์เช็ค)',
            'E' => 'เลขที่อ้างอิง',
            'F' => 'รายการ(ชื่อรายการค่าใช้จ่าย)',
            'G' => 'จำนวนเงิน',
            'H' => 'ภาษี %',
            'I' => 'จำนวนภาษี',
            'J' => 'หมายเหตุ',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue($col . '1', $label);
        }

        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'cashrecord_import_pattern.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function actionImport()
    {
        $file = \yii\web\UploadedFile::getInstanceByName('import_file');
        if ($file) {
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->tempName);
                $sheet = $spreadsheet->getActiveSheet();
                $highestRow = $sheet->getHighestRow();

                $successCount = 0; // Count Master Records
                $errorCount = 0;
                $current_master_id = null;

                for ($row = 2; $row <= $highestRow; $row++) {
                    $trans_date = $sheet->getCell('A' . $row)->getValue();
                    $pay_for = $sheet->getCell('B' . $row)->getValue();
                    $car_plate = $sheet->getCell('C' . $row)->getValue();
                    $bank_account = $sheet->getCell('D' . $row)->getValue();
                    $ref_no = $sheet->getCell('E' . $row)->getValue();
                    $cost_title_name = $sheet->getCell('F' . $row)->getValue();
                    $amount = $sheet->getCell('G' . $row)->getValue();
                    $vat_per = $sheet->getCell('H' . $row)->getValue();
                    $vat_amount = $sheet->getCell('I' . $row)->getValue();
                    $remark = $sheet->getCell('J' . $row)->getValue();

                    // If has Master Info (Date or Payee), Create New Master
                    if ($trans_date != '' || $pay_for != '') {
                        $model = new Cashrecord();
                        if (is_numeric($trans_date)) {
                            $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($trans_date);
                            $model->trans_date = $date->format('Y-m-d');
                        } else {
                            $model->trans_date = date('Y-m-d', strtotime($trans_date));
                        }
                        $model->pay_for = $pay_for;
                        $model->bank_account = $bank_account;
                        $model->ref_no = $ref_no;
                        $model->vat_per = $vat_per;
                        $model->status = 1;
                        $model->journal_no = $model->getLastNo();

                        // find car_id
                        if ($car_plate != '') {
                            $car = \backend\models\Car::find()->where(['plate_no' => $car_plate])->one();
                            if ($car) {
                                $model->car_id = $car->id;
                            }
                        }

                        if ($model->save(false)) {
                            $current_master_id = $model->id;
                            $successCount++;

                            // create stock transaction once per master
                            $model_trans = new \backend\models\Stocktrans();
                            $model_trans->trans_date = date('Y-m-d H:i:s');
                            $model_trans->activity_type_id = 5; // cash record
                            $model_trans->trans_ref_id = $model->id;
                            $model_trans->save(false);
                        } else {
                            $current_master_id = null;
                            $errorCount++;
                        }
                    }

                    // Create Detail Line if we have a current master
                    if ($current_master_id != null && $cost_title_name != '') {
                        $model_line = new \common\models\CashRecordLine();
                        $model_line->car_record_id = $current_master_id;
                        $model_line->amount = $amount;
                        $model_line->vat_amount = $vat_amount;
                        $model_line->remark = $remark;
                        $model_line->status = 1;

                        // find cost_title_id
                        $cost_title = \backend\models\CostTitle::find()->where(['name' => $cost_title_name])->one();
                        if ($cost_title) {
                            $model_line->cost_title_id = $cost_title->id;
                        }
                        $model_line->save(false);
                    }
                }

                if ($successCount > 0) {
                    \Yii::$app->session->setFlash('success', "นำเข้าข้อมูลสำเร็จ $successCount ใบ");
                }
                if ($errorCount > 0) {
                    \Yii::$app->session->setFlash('error', "พบข้อผิดพลาด $errorCount ใบ");
                }

            } catch (\Exception $e) {
                \Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
            }
        } else {
            \Yii::$app->session->setFlash('error', 'กรุณาเลือกไฟล์');
        }

        return $this->redirect(['index']);
    }
}
