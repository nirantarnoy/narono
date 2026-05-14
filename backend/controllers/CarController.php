<?php

namespace backend\controllers;

use backend\models\Car;
use backend\models\CarSearch;
use backend\models\Fueldailyprice;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CarController implements the CRUD actions for Car model.
 */
class CarController extends Controller
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
     * Lists all Car models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $pageSize = \Yii::$app->request->post("perpage");

        $searchModel = new CarSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->pagination->pageSize = $pageSize;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'perpage' => $pageSize,
        ]);
    }

    /**
     * Displays a single Car model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model_doc = \common\models\CarDoc::find()->where(['car_id'=>$id])->all();
        $model_loan = \backend\models\Carloantrans::find()->where(['car_loan_id'=>$id])->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'model_doc' => $model_doc,
            'model_loan' => $model_loan,
        ]);
    }

    /**
     * Creates a new Car model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Car();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $uploaded = UploadedFile::getInstance($model, 'doc');

                $file_doc_type_id_0 = \Yii::$app->request->post('file_doc_type_id_0');
                $file_doc_type_id_1 = \Yii::$app->request->post('file_doc_type_id_1');
                $file_doc_type_id_2 = \Yii::$app->request->post('file_doc_type_id_2');
                $file_doc_type_id_3 = \Yii::$app->request->post('file_doc_type_id_3');

                $car_loan_doc_no = \Yii::$app->request->post('car_loan_doc_no');
                $car_loan_period_total = \Yii::$app->request->post('car_loan_period_total');
                $car_loan_period_amount = \Yii::$app->request->post('car_loan_period_amount');
                $car_loan_all_amount = \Yii::$app->request->post('car_loan_all_amount');

                $file_doc_0 = UploadedFile::getInstanceByName('file_doc_0');
                $file_doc_1 = UploadedFile::getInstanceByName('file_doc_1');
                $file_doc_2 = UploadedFile::getInstanceByName('file_doc_2');
                $file_doc_3 = UploadedFile::getInstanceByName('file_doc_3');

                if (!empty($uploaded)) {
                    $upfiles = time() . "." . $uploaded->getExtension();
                    // if ($uploaded->saveAs(Yii::$app->request->baseUrl . '/uploads/files/' . $upfiles)) {
                    if ($uploaded->saveAs('../web/uploads/car_doc/' . $upfiles)) {
                        $model->doc = $upfiles;
                    }
                }
                if ($model->save(false)) {
                    if (!empty($file_doc_0)) {
                        $upfiles = time() + 1 . "." . $file_doc_0->getExtension();
                        // if ($uploaded->saveAs(Yii::$app->request->baseUrl . '/uploads/files/' . $upfiles)) {
                        if ($file_doc_0->saveAs('../web/uploads/car_doc/' . $upfiles)) {
                            $model_car_doc = new \common\models\CarDoc();
                            $model_car_doc->car_id = $model->id;
                            $model_car_doc->doc_type_id = $file_doc_type_id_0;
                            $model_car_doc->docname = $upfiles;
                            $model_car_doc->save(false);
                        }
                    }
                    if (!empty($file_doc_1)) {
                        $upfiles1 = time() +2 . "." . $file_doc_1->getExtension();
                        // if ($uploaded->saveAs(Yii::$app->request->baseUrl . '/uploads/files/' . $upfiles)) {
                        if ($file_doc_1->saveAs('../web/uploads/car_doc/' . $upfiles1)) {
                            $model_car_doc = new \common\models\CarDoc();
                            $model_car_doc->car_id = $model->id;
                            $model_car_doc->doc_type_id = $file_doc_type_id_1;
                            $model_car_doc->docname = $upfiles1;
                            $model_car_doc->save(false);
                        }
                    }
                    if (!empty($file_doc_2)) {
                        $upfiles2 = time() + 3 . "." . $file_doc_2->getExtension();
                        // if ($uploaded->saveAs(Yii::$app->request->baseUrl . '/uploads/files/' . $upfiles)) {
                        if ($file_doc_2->saveAs('../web/uploads/car_doc/' . $upfiles2)) {
                            $model_car_doc = new \common\models\CarDoc();
                            $model_car_doc->car_id = $model->id;
                            $model_car_doc->doc_type_id = $file_doc_type_id_2;
                            $model_car_doc->docname = $upfiles2;
                            $model_car_doc->save(false);
                        }
                    }
                    if (!empty($file_doc_3)) {
                        $upfiles3 = time() + 4 . "." . $file_doc_3->getExtension();
                        // if ($uploaded->saveAs(Yii::$app->request->baseUrl . '/uploads/files/' . $upfiles)) {
                        if ($file_doc_3->saveAs('../web/uploads/car_doc/' . $upfiles3)) {
                            $model_car_doc = new \common\models\CarDoc();
                            $model_car_doc->car_id = $model->id;
                            $model_car_doc->doc_type_id = $file_doc_type_id_3;
                            $model_car_doc->docname = $upfiles3;
                            $model_car_doc->save(false);
                        }
                    }


                    // save car loan data

                    $model_loan = new \common\models\CarLoan();
                    $model_loan->doc_no = $car_loan_doc_no;
                    $model_loan->loan_amount = $car_loan_all_amount;
                    $model_loan->car_id = $model->id;
                    $model_loan->period_amount = $car_loan_period_amount;
                    $model_loan->total_period = $car_loan_period_total;
                    $model_loan->save(false);

                    return $this->redirect(['view', 'id' => $model->id]);
                }

            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'model_doc' => null,
        ]);
    }

    /**
     * Updates an existing Car model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_doc = \common\models\CarDoc::find()->where(['car_id'=>$id])->all();
        $model_car_loan = \common\models\CarLoan::find()->where(['car_id'=>$id])->one();

        if ($this->request->isPost && $model->load($this->request->post())) {

            $uploaded = UploadedFile::getInstance($model, 'doc');

            $file_doc_type_id_0 = \Yii::$app->request->post('file_doc_type_id_0');
            $file_doc_type_id_1 = \Yii::$app->request->post('file_doc_type_id_1');
            $file_doc_type_id_2 = \Yii::$app->request->post('file_doc_type_id_2');
            $file_doc_type_id_3 = \Yii::$app->request->post('file_doc_type_id_3');

            $car_loan_doc_no = \Yii::$app->request->post('car_loan_doc_no');
            $car_loan_period_total = \Yii::$app->request->post('car_loan_period_total');
            $car_loan_period_amount = \Yii::$app->request->post('car_loan_period_amount');
            $car_loan_all_amount = \Yii::$app->request->post('car_loan_all_amount');

            $file_doc_0 = UploadedFile::getInstanceByName('file_doc_0');
            $file_doc_1 = UploadedFile::getInstanceByName('file_doc_1');
            $file_doc_2 = UploadedFile::getInstanceByName('file_doc_2');
            $file_doc_3 = UploadedFile::getInstanceByName('file_doc_3');

            if (!empty($uploaded)) {
                $upfiles = time() . "." . $uploaded->getExtension();
                // if ($uploaded->saveAs(Yii::$app->request->baseUrl . '/uploads/files/' . $upfiles)) {
                if ($uploaded->saveAs('../web/uploads/car_doc/' . $upfiles)) {
                    $model->doc = $upfiles;
                }
            }
            if ($model->save()) {
                if (!empty($file_doc_0)) {
                    $upfiles = time() + 1 . "." . $file_doc_0->getExtension();
                    // if ($uploaded->saveAs(Yii::$app->request->baseUrl . '/uploads/files/' . $upfiles)) {
                    if ($file_doc_0->saveAs('../web/uploads/car_doc/' . $upfiles)) {
                        $check_old = \common\models\CarDoc::find()->where(['car_id'=>$model->id,'doc_type_id'=>1])->one();
                        if($check_old){
                            $check_old->docname = $upfiles;
                            $check_old->save(false);
                        }else{
                            $model_car_doc = new \common\models\CarDoc();
                            $model_car_doc->car_id = $model->id;
                            $model_car_doc->doc_type_id = $file_doc_type_id_0;
                            $model_car_doc->docname = $upfiles;
                            $model_car_doc->save(false);
                        }

                    }
                }
                if (!empty($file_doc_1)) {
                    $upfiles1 = time() +2 . "." . $file_doc_1->getExtension();
                    // if ($uploaded->saveAs(Yii::$app->request->baseUrl . '/uploads/files/' . $upfiles)) {
                    if ($file_doc_1->saveAs('../web/uploads/car_doc/' . $upfiles1)) {
                        $check_old = \common\models\CarDoc::find()->where(['car_id'=>$model->id,'doc_type_id'=>2])->one();
                        if($check_old){
                            $check_old->docname = $upfiles1;
                            $check_old->save(false);
                        }else {
                            $model_car_doc = new \common\models\CarDoc();
                            $model_car_doc->car_id = $model->id;
                            $model_car_doc->doc_type_id = $file_doc_type_id_1;
                            $model_car_doc->docname = $upfiles1;
                            $model_car_doc->save(false);
                        }
                    }
                }
                if (!empty($file_doc_2)) {
                    $upfiles2 = time() + 3 . "." . $file_doc_2->getExtension();
                    // if ($uploaded->saveAs(Yii::$app->request->baseUrl . '/uploads/files/' . $upfiles)) {
                    if ($file_doc_2->saveAs('../web/uploads/car_doc/' . $upfiles2)) {
                        $check_old = \common\models\CarDoc::find()->where(['car_id'=>$model->id,'doc_type_id'=>3])->one();
                        if($check_old){
                            $check_old->docname = $upfiles2;
                            $check_old->save(false);
                        }else {
                            $model_car_doc = new \common\models\CarDoc();
                            $model_car_doc->car_id = $model->id;
                            $model_car_doc->doc_type_id = $file_doc_type_id_2;
                            $model_car_doc->docname = $upfiles2;
                            $model_car_doc->save(false);
                        }
                    }
                }
                if (!empty($file_doc_3)) {
                    $upfiles3 = time() + 4 . "." . $file_doc_3->getExtension();
                    // if ($uploaded->saveAs(Yii::$app->request->baseUrl . '/uploads/files/' . $upfiles)) {
                    if ($file_doc_3->saveAs('../web/uploads/car_doc/' . $upfiles3)) {
                        $check_old = \common\models\CarDoc::find()->where(['car_id'=>$model->id,'doc_type_id'=>4])->one();
                        if($check_old){
                            $check_old->docname = $upfiles3;
                            $check_old->save(false);
                        }else {
                            $model_car_doc = new \common\models\CarDoc();
                            $model_car_doc->car_id = $model->id;
                            $model_car_doc->doc_type_id = $file_doc_type_id_3;
                            $model_car_doc->docname = $upfiles3;
                            $model_car_doc->save(false);
                        }
                    }
                }

                // save car loan data
                $model_loan_check = \common\models\CarLoan::find()->where(['car_id'=>$id])->one();
                if($model_loan_check){
                    $model_loan_check->doc_no = $car_loan_doc_no;
                    $model_loan_check->loan_amount = $car_loan_all_amount;
                    $model_loan_check->period_amount = $car_loan_period_amount;
                    $model_loan_check->total_period = $car_loan_period_total;
                    $model_loan_check->save(false);
                }else{
                    $model_loan = new \common\models\CarLoan();
                    $model_loan->doc_no = $car_loan_doc_no;
                    $model_loan->car_id = $model->id;
                    $model_loan->loan_amount = $car_loan_all_amount;
                    $model_loan->period_amount = $car_loan_period_amount;
                    $model_loan->total_period = $car_loan_period_total;
                    $model_loan->save(false);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }


        }

        return $this->render('update', [
            'model' => $model,
            'model_doc'=> $model_doc,
            'model_car_loan' =>$model_car_loan,
        ]);
    }

    /**
     * Deletes an existing Car model.
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
     * Finds the Car model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Car the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Car::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetcarinfo()
    {
        $id = \Yii::$app->request->post('car_id');
        $data = [];
        if ($id) {
            $plate_no = \backend\models\Car::getPlateno($id);
            $hp = \backend\models\Car::getHp($id);
            $car_type = \backend\models\Car::getCartype($id);
            $car_type_id = \backend\models\Car::getCartypeId($id);
            $driver_id = \backend\models\Car::getDriver($id);
            $driver_name = \backend\models\Employee::findFullName($driver_id);

            $fuel_price = \backend\models\Fueldailyprice::findPrice($id);

            array_push($data, [
                'plate_no' => $plate_no,
                'hp' => $hp,
                'car_type' => $car_type,
                'driver_id' => $driver_id,
                'driver_name' => $driver_name,
                'car_type_id' => $car_type_id,
                'fuel_price' => $fuel_price,
            ]);
        }
        echo json_encode($data);
    }

    public function actionGetrouteplan()
    {
        $id = \Yii::$app->request->post('route_plan_id');
        $customer_id = \Yii::$app->request->post('customer_id');
        $car_type_id = \Yii::$app->request->post('car_type_id');
        $data = [];
        if ( $customer_id && $car_type_id) {
            $distance = 10;
            $total_rate_qty = 0;
            $total_dropoff_qty = 0;
            $labour_price = 0;
            $express_road_price = 0;
            $cover_sheet_price = 0;
            $overnight_price = 0;
            $warehouse_plus_price = 0;
            $other_price = 0;

            $model = \common\models\RoutePlan::find()->where(['customer_id' => $customer_id])->one();
            if ($model) {
                $distance = $model->total_distanct;
                $total_rate_qty = $model->oil_rate_qty;


                $model_plan_price = \common\models\RoutePlanPrice::find()->where(['route_plan_id' => $model->id, 'car_type_id' => $car_type_id])->all();
                if ($model_plan_price) {
                    foreach ($model_plan_price as $value) {
                        $labour_price = $value->labour_price;
                        $express_road_price = $value->express_road_price;
                        $cover_sheet_price = $value->cover_sheet_price;
                        $overnight_price = $value->overnight_price;
                        $warehouse_plus_price = $value->warehouse_plus_price;
                        $other_price = $value->other_price;
                        $trail_labour_price = $value->trail_labour_price;
                    }
                }
                $model_line_qty = \common\models\RoutePlanLine::find()->where(['route_plan_id' => $model->id])->sum('dropoff_qty');
                if ($model_line_qty) {
                    $total_dropoff_qty = $model_line_qty;
                }


            }

            array_push($data, [
                'total_distance' => $distance,
                'total_rate_qty' => $total_rate_qty,
                'total_dropoff_rate_qty' => $total_dropoff_qty,
                'labour_price' => $labour_price,
                'express_road_price' => $express_road_price,
                'cover_sheet_price' => $cover_sheet_price,
                'overnight_price' => $overnight_price,
                'warehouse_plus_price' => $warehouse_plus_price,
                'other_price' => $other_price,
                'trail_labour_price' => isset($trail_labour_price)?$trail_labour_price:0,
            ]);
        }
        echo json_encode($data);
    }

    public function actionRemovedoc()
    {
        $car_id = \Yii::$app->request->post('car_id');
        $doc_name = \Yii::$app->request->post('doc_name');

        echo $car_id . ' = ' . $doc_name;

        if ($car_id && $doc_name != '') {
            if (file_exists(\Yii::getAlias('@backend') . '/web/uploads/car_doc/' . $doc_name)) {
                if (unlink(\Yii::getAlias('@backend') . '/web/uploads/car_doc/' . $doc_name)) {
                    $model = \backend\models\Car::find()->where(['id' => $car_id])->one();
                    if ($model) {
                        $model->doc = '';
                        $model->save(false);
                    }
                }
            }
        } else {
            echo "no";
            return;
        }
        return $this->redirect(['car/update', 'id' => $car_id]);
    }
    public function actionPaymentloan(){

    }
}
