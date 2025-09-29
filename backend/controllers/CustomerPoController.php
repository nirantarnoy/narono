<?php

namespace backend\controllers;

use Yii;
use backend\models\CustomerPo;
use backend\models\CustomerPoSearch;
use backend\models\CustomerPoInvoice;
use backend\models\CustomerInvoice;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

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
        $linkedInvoices = CustomerInvoice::find()
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
    public function actionCreate()
    {
        $model = new CustomerPo();
        $model->status = CustomerPo::STATUS_ACTIVE;
        $model->po_date = date('Y-m-d');

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
              //  print_r($this->request->post());return;
                $model->po_file_upload = UploadedFile::getInstance($model, 'po_file_upload');
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'สร้าง PO เรียบร้อยแล้ว');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CustomerPo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->po_file_upload = UploadedFile::getInstance($model, 'po_file_upload');

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'แก้ไข PO เรียบร้อยแล้ว');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CustomerPo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Check if PO has linked invoices
        if (CustomerPoInvoice::find()->where(['po_id' => $id])->exists()) {
            Yii::$app->session->setFlash('error', 'ไม่สามารถลบ PO ที่มีการเชื่อมโยงกับใบวางบิลได้');
            return $this->redirect(['index']);
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'ลบ PO เรียบร้อยแล้ว');

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