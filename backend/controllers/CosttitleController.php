<?php

namespace backend\controllers;

use backend\models\CostTitle;
use backend\models\CostTitleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FixcosttitleController implements the CRUD actions for FixcostTitle model.
 */
class CosttitleController extends Controller
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
     * Lists all FixcostTitle models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $pageSize = \Yii::$app->request->post("perpage");
        $searchModel = new CostTitleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->pagination->pageSize = $pageSize;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'perpage' => $pageSize == null ? 20 : $pageSize,
        ]);
    }

    /**
     * Displays a single FixcostTitle model.
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
     * Creates a new FixcostTitle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CostTitle();

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
     * Updates an existing FixcostTitle model.
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
     * Deletes an existing FixcostTitle model.
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

    public function actionExportPattern()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'A' => 'ชื่อ',
            'B' => 'รายละเอียด',
            'C' => 'ประเภทรับจ่าย(1=จ่าย, 2=รับ)',
            'D' => 'สถานะ(1=ใช้งาน, 0=ไม่ใช้งาน)',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue($col . '1', $label);
        }

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'cost_title_import_pattern.xlsx';

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

                $successCount = 0;
                $errorCount = 0;

                for ($row = 2; $row <= $highestRow; $row++) {
                    $name = $sheet->getCell('A' . $row)->getValue();
                    $description = $sheet->getCell('B' . $row)->getValue();
                    $type_id = $sheet->getCell('C' . $row)->getValue();
                    $status = $sheet->getCell('D' . $row)->getValue();

                    if ($name != '') {
                        $model = new CostTitle();
                        $model->name = (string)$name;
                        $model->description = (string)$description;
                        $model->type_id = (int)$type_id;
                        $model->status = ($status != null) ? (int)$status : 1;

                        if ($model->save()) {
                            $successCount++;
                        } else {
                            $errorCount++;
                        }
                    }
                }

                if ($successCount > 0) {
                    \Yii::$app->session->setFlash('success', "นำเข้าข้อมูลสำเร็จ $successCount รายการ");
                }
                if ($errorCount > 0) {
                    \Yii::$app->session->setFlash('error', "พบข้อผิดพลาด $errorCount รายการ");
                }

            } catch (\Exception $e) {
                \Yii::$app->session->setFlash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
            }
        } else {
            \Yii::$app->session->setFlash('error', 'กรุณาเลือกไฟล์');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the FixcostTitle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return FixcostTitle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CostTitle::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
