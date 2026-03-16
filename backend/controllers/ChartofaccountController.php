<?php

namespace backend\controllers;

use common\models\ChartOfAccount;
use backend\models\ChartofaccountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * ChartofaccountController implements the CRUD actions for ChartOfAccount model.
 */
class ChartofaccountController extends Controller
{
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
     * Lists all ChartOfAccount models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ChartofaccountSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ChartOfAccount model.
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
     * Creates a new ChartOfAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ChartOfAccount();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
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
     * Updates an existing ChartOfAccount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ChartOfAccount model.
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
     * Imports data from Excel.
     */
    public function actionImport()
    {
        $file = UploadedFile::getInstanceByName('import_file');
        if ($file) {
            try {
                $spreadsheet = IOFactory::load($file->tempName);
                $sheet = $spreadsheet->getActiveSheet();
                $highestRow = $sheet->getHighestRow();
                
                $successCount = 0;
                // จากรูปที่แนบมา ข้อมูลเริ่มแถวที่ 6 (ลำดับที่ 1)
                for ($row = 6; $row <= $highestRow; $row++) {
                    $account_code = trim((string)$sheet->getCell('B' . $row)->getValue());
                    $sub_account_code = trim((string)$sheet->getCell('C' . $row)->getValue());
                    $name_th = trim((string)$sheet->getCell('D' . $row)->getValue());
                    $name_en = trim((string)$sheet->getCell('E' . $row)->getValue());

                    if (empty($account_code)) continue;

                    $model = ChartOfAccount::find()->where(['account_code' => $account_code, 'sub_account_code' => $sub_account_code])->one();
                    if (!$model) {
                        $model = new ChartOfAccount();
                    }
                    $model->account_code = $account_code;
                    $model->sub_account_code = $sub_account_code;
                    $model->name = $name_th;
                    $model->name_en = $name_en;
                    if ($model->save()) {
                        $successCount++;
                    }
                }
                Yii::$app->session->setFlash('success', "นำเข้าข้อมูลผังบัญชีสำเร็จ $successCount รายการ");
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาดในการนำเข้า: ' . $e->getMessage());
            }
        } else {
            Yii::$app->session->setFlash('error', 'กรุณาเลือกไฟล์ที่ต้องการนำเข้า');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the ChartOfAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ChartOfAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ChartOfAccount::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
