<?php

namespace backend\controllers;

use backend\models\CustomerPoLine;
use Yii;
use backend\models\CustomerPo;
use backend\models\CustomerPoSearch;
use backend\models\CustomerPoInvoice;
use backend\models\Customerinvoice;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use backend\helpers\Model;

/**
 * CustomerPoController implements the CRUD actions for CustomerPo model.
 */
class CustomerPoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                        'link-invoice' => ['POST'],
                        'unlink-invoice' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all CustomerPo models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CustomerPoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CustomerPo model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // Get linked invoices
        $linkedInvoices = Customerinvoice::find()
            ->joinWith('poInvoices')
            ->where(['customer_po_invoices.po_id' => $id])
            ->all();

        return $this->render('view', [
            'model' => $model,
            'linkedInvoices' => $linkedInvoices,
        ]);
    }

    /**
     * Creates a new CustomerPo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
//    public function actionCreate()
//    {
//        $model = new CustomerPo();
//        $model->status = CustomerPo::STATUS_ACTIVE;
//        $model->po_date = date('Y-m-d');
//
//        if ($this->request->isPost) {
//            if ($model->load($this->request->post())) {
//              //  print_r($this->request->post());return;
//                $model->po_file_upload = UploadedFile::getInstance($model, 'po_file_upload');
//                if ($model->save(false)) {
//                    Yii::$app->session->setFlash('success', 'สร้าง PO เรียบร้อยแล้ว');
//                    return $this->redirect(['view', 'id' => $model->id]);
//                }
//            }
//        }
//
//        return $this->render('create', [
//            'model' => $model,
//        ]);
//    }
//
//    /**
//     * Updates an existing CustomerPo model.
//     * If update is successful, the browser will be redirected to the 'view' page.
//     * @param int $id ID
//     * @return string|\yii\web\Response
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($this->request->isPost) {
//            if ($model->load($this->request->post())) {
//                $model->po_file_upload = UploadedFile::getInstance($model, 'po_file_upload');
//
//                if ($model->save()) {
//                    Yii::$app->session->setFlash('success', 'แก้ไข PO เรียบร้อยแล้ว');
//                    return $this->redirect(['view', 'id' => $model->id]);
//                }
//            }
//        }
//
//        return $this->render('update', [
//            'model' => $model,
//        ]);
//    }
//
//    /**
//     * Deletes an existing CustomerPo model.
//     * If deletion is successful, the browser will be redirected to the 'index' page.
//     * @param int $id ID
//     * @return \yii\web\Response
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    public function actionDelete($id)
//    {
//        $model = $this->findModel($id);
//
//        // Check if PO has linked invoices
//        if (CustomerPoInvoice::find()->where(['po_id' => $id])->exists()) {
//            Yii::$app->session->setFlash('error', 'ไม่สามารถลบ PO ที่มีการเชื่อมโยงกับใบวางบิลได้');
//            return $this->redirect(['index']);
//        }
//
//        $model->delete();
//        Yii::$app->session->setFlash('success', 'ลบ PO เรียบร้อยแล้ว');
//
//        return $this->redirect(['index']);
//    }

    /**
     * Creates a new CustomerPo model with lines.
     */
    public function actionCreate()
    {
        $model = new CustomerPo();
        $model->status = CustomerPo::STATUS_ACTIVE;
        $model->po_date = date('Y-m-d');
        $modelsLine = [new CustomerPoLine()];

        if ($model->load(Yii::$app->request->post())) {

            $modelsLine = Model::createMultiple(CustomerPoLine::class);
            Model::loadMultiple($modelsLine, Yii::$app->request->post());

            // รับไฟล์
            $model->po_file_upload = UploadedFile::getInstance($model, 'po_file_upload');

            // ตรวจสอบ
            $valid = $model->validate();
        //    $valid = Model::validateMultiple($modelsLine) && $valid;

            if ($valid) {
               // echo "ok";return;
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $total_po_amount = 0;
                    if ($flag = $model->save(false)) {

                        // บันทึกไฟล์ (ถ้ามี)
                        if ($model->po_file_upload) {
                            $filename = 'PO_' . $model->id . '_' . time() . '.' . $model->po_file_upload->extension;
                            $uploadPath = Yii::getAlias('@webroot/uploads/po/' . $filename);
                            if ($model->po_file_upload->saveAs($uploadPath)) {
                                $model->po_file_upload = $filename;
                                $model->save(false, ['po_file_upload']);
                            }
                        }

                        // บันทึก PO Line
                        foreach ($modelsLine as $i => $modelLine) {
                            $modelLine->po_id = $model->id;
                            $modelLine->sort_order = $i + 1;
                            $modelLine->status = 0;
                            $modelLine->line_total = ($modelLine->qty * $modelLine->price);
                            if (!($flag = $modelLine->save(false))) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาด: ' . print_r($modelLine->getErrors(), true));
                                break;
                            }
                            $total_po_amount += $modelLine->line_total;
                        }
                    }

                    if ($flag) {
                        //echo "no";return;
//                        $model->updatePoAmountFromLines();
                        $model->po_amount = $total_po_amount;
                        $model->save(false);
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'สร้าง PO พร้อมรายละเอียดเรียบร้อยแล้ว');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }else{
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาด1: ' . $model->getMessage());
                    }
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    Yii::error($e->getMessage(), __METHOD__);
                    Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาด2: ' . $e->getMessage());
                }
            }else{
                Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาด3: ' . print_r($model->getErrors(), true));
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelsLine' => (empty($modelsLine)) ? [new CustomerPoLine] : $modelsLine
        ]);
    }


    /**
     * Updates an existing CustomerPo model with lines.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsLine = $model->customerPoLines;

        if (empty($modelsLine)) {
            $modelsLine = [new CustomerPoLine];
        }

        if ($model->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($modelsLine, 'id', 'id');
            $modelsLine = Model::createMultiple(CustomerPoLine::class, $modelsLine);
            Model::loadMultiple($modelsLine, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsLine, 'id', 'id')));

            // รับไฟล์ใหม่
            $model->po_file_upload = UploadedFile::getInstance($model, 'po_file_upload');

            // ตรวจสอบ
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsLine) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $total_po_amount = 0;
                    if ($flag = $model->save(false)) {

                        // ลบรายการที่ถูกลบ
                        if (!empty($deletedIDs)) {
                            CustomerPoLine::deleteAll(['id' => $deletedIDs]);
                        }

                        // บันทึกไฟล์ใหม่ (ถ้ามี)
                        if ($model->po_file_upload) {
                            // ลบไฟล์เก่า
                            if ($model->po_file) {
                                $oldFile = Yii::getAlias('@webroot/uploads/po/' . $model->po_file);
                                if (file_exists($oldFile)) {
                                    unlink($oldFile);
                                }
                            }

                            // สร้างโฟลเดอร์ถ้ายังไม่มี
                            $uploadDir = Yii::getAlias('@webroot/uploads/po');
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0777, true);
                            }

                            $filename = 'PO_' . $model->id . '_' . time() . '.' . $model->po_file_upload->extension;
                            $uploadPath = $uploadDir . '/' . $filename;

                            if ($model->po_file_upload->saveAs($uploadPath)) {
                                $model->po_file = $filename;
                                $model->save(false, ['po_file']);
                            }
                        }

                        // บันทึก PO Line
                        foreach ($modelsLine as $i => $modelLine) {
                           // echo 'xx'.($modelLine->qty) * ($modelLine->price);return;
                            $modelLine->po_id = $model->id;
                            $modelLine->sort_order = $i + 1;
                            $modelLine->status = 0;
                            $modelLine->line_total = ($modelLine->qty) * ($modelLine->price);

                            if (!($flag = $modelLine->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                            $total_po_amount += ($modelLine->line_total);
                        }
                    }

                    if ($flag) {
                       // $model->updatePoAmountFromLines();
                       // echo $total_po_amount;return;
                        $model->po_amount = $total_po_amount;
                        $model->save(false);
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'อัพเดต PO พร้อมรายละเอียดเรียบร้อยแล้ว');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::error($e->getMessage(), __METHOD__);
                    Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelsLine' => (empty($modelsLine)) ? [new CustomerPoLine] : $modelsLine
        ]);
    }

    /**
     * Deletes an existing CustomerPo model.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Check if PO has linked invoices
        if (CustomerPoInvoice::find()->where(['po_id' => $id])->exists()) {
            Yii::$app->session->setFlash('error', 'ไม่สามารถลบ PO ที่มีการเชื่อมโยงกับใบวางบิลได้');
            return $this->redirect(['index']);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Delete lines first
            CustomerPoLine::deleteAll(['po_id' => $id]);
            // Delete PO
            $model->delete();

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'ลบ PO เรียบร้อยแล้ว');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Link invoice to PO
     */
    public function actionLinkInvoice($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            $invoiceId = $this->request->post('invoice_id');
            $amount = $this->request->post('amount');

            if ($invoiceId && $amount > 0) {
                // Check if invoice exists and belongs to same customer
                $invoice = CustomerInvoice::findOne($invoiceId);
                if (!$invoice || $invoice->customer_id != $model->customer_id) {
                    return Json::encode([
                        'success' => false,
                        'message' => 'ใบวางบิลไม่ถูกต้องหรือไม่ตรงกับลูกค้า'
                    ]);
                }

                // Check if amount doesn't exceed remaining amount
                if ($amount > $model->remaining_amount) {
                    return Json::encode([
                        'success' => false,
                        'message' => 'จำนวนเงินเกินยอดคงเหลือของ PO'
                    ]);
                }

                if ($model->linkInvoice($invoiceId, $amount)) {
                    return Json::encode([
                        'success' => true,
                        'message' => 'เชื่อมโยงใบวางบิลเรียบร้อยแล้ว'
                    ]);
                }
            }

            return Json::encode([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด'
            ]);
        }

        // Get available invoices for this customer
        $availableInvoices = CustomerInvoice::find()
            ->where(['customer_id' => $model->customer_id])
            ->andWhere(['NOT IN', 'id',
                CustomerPoInvoice::find()->select('invoice_id')->where(['po_id' => $id])
            ])
            ->all();

        return $this->renderAjax('_link_invoice', [
            'model' => $model,
            'availableInvoices' => $availableInvoices,
        ]);
    }

    /**
     * Unlink invoice from PO
     */
    public function actionUnlinkInvoice($id, $invoiceId)
    {
        $model = $this->findModel($id);

        $model->unlinkInvoice($invoiceId);

        Yii::$app->session->setFlash('success', 'ยกเลิกการเชื่อมโยงใบวางบิลเรียบร้อยแล้ว');

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Get invoice details via AJAX
     */
    public function actionGetInvoiceDetails($invoiceId)
    {
        $invoice = CustomerInvoice::findOne($invoiceId);

        if ($invoice) {
            return Json::encode([
                'success' => true,
                'data' => [
                    'invoice_no' => $invoice->invoice_no,
                    'total_amount' => $invoice->total_amount,
                    'invoice_date' => $invoice->invoice_date,
                ]
            ]);
        }

        return Json::encode(['success' => false]);
    }

    /**
     * Download PO file
     */
    public function actionDownload($id)
    {
        $model = $this->findModel($id);

        if (!$model->po_file) {
            throw new NotFoundHttpException('ไม่พบไฟล์');
        }

        $filePath = Yii::getAlias('@backend/web/uploads/po/') . $model->po_file;

        if (!file_exists($filePath)) {
            throw new NotFoundHttpException('ไม่พบไฟล์');
        }

        return Yii::$app->response->sendFile($filePath, $model->po_file);
    }

    /**
     * Export PO list to Excel
     */
    public function actionExport()
    {
        $searchModel = new CustomerPoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->pagination = false; // Get all records

        $models = $dataProvider->getModels();

        // Create CSV content
        $csv = "เลขที่ PO,วันที่สร้าง,วันที่หมดอายุ,ลูกค้า,งาน,มูลค่างาน,ยอดวางบิล,คงเหลือ,สถานะ\n";

        foreach ($models as $model) {
            $csv .= sprintf('"%s","%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                $model->po_number,
                $model->po_date,
                $model->po_target_date,
                $model->customer ? $model->customer->name : '',
                str_replace('"', '""', $model->work_name),
                number_format($model->po_amount, 2),
                number_format($model->billed_amount, 2),
                number_format($model->remaining_amount, 2),
                $model->getStatusLabel()
            );
        }

        // Send file
        $filename = 'po_report_' . date('Y-m-d_H-i-s') . '.csv';

        return Yii::$app->response->sendContentAsFile(
            $csv,
            $filename,
            ['mimeType' => 'text/csv']
        );
    }

    /**
     * Get PO summary statistics
     */
    public function actionStats()
    {
        $stats = [
            'total_po' => CustomerPo::find()->count(),
            'active_po' => CustomerPo::find()->where(['status' => CustomerPo::STATUS_ACTIVE])->count(),
            'completed_po' => CustomerPo::find()->where(['status' => CustomerPo::STATUS_COMPLETED])->count(),
            'total_amount' => CustomerPo::find()->sum('po_amount') ?? 0,
            'billed_amount' => CustomerPo::find()->sum('billed_amount') ?? 0,
            'remaining_amount' => CustomerPo::find()->sum('remaining_amount') ?? 0,
        ];

        return Json::encode($stats);
    }

    /**
     * Finds the CustomerPo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CustomerPo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CustomerPo::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}