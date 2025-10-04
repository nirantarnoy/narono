<?php

namespace backend\controllers;
date_default_timezone_set('Asia/Bangkok');

use backend\models\Workqueue;
use backend\models\WorkqueueSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * WorkqueueController implements the CRUD actions for Workqueue model.
 */
class WorkqueueController extends Controller
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
     * Lists all Workqueue models.
     *
     * @return string
     */
    public function actionIndex()
    {
        //echo date('d-m-Y H:i:s');
        $pageSize = \Yii::$app->request->post("perpage");

        $searchModel = new WorkqueueSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->orderBy(['id' => SORT_DESC]);
        $dataProvider->pagination->pageSize = $pageSize;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'perpage' => $pageSize,
        ]);
    }

    /**
     * Displays a single Workqueue model.
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
     * Creates a new Workqueue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Workqueue();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $new_date = $model->work_queue_date . ' ' . date('H:i:s');

                $model->work_queue_date = date('Y-m-d H:i:s', strtotime($new_date));
                $model->work_queue_no = $model->getLastNo();

                $line_doc_name = \Yii::$app->request->post('line_doc_name');
                // $line_file_name = \Yii::$app->request->post('line_file_name');
                $uploaded = UploadedFile::getInstancesByName('line_file_name');

                $item_id = \Yii::$app->request->post('line_work_queue_item_id');
                $description = \Yii::$app->request->post('line_work_queue_description');


                $oil_daily_price = \Yii::$app->request->post('oil_daily_price');
                $oil_out_price = \Yii::$app->request->post('oil_out_price');
                $total_distance = \Yii::$app->request->post('total_distance');
                $total_lite = \Yii::$app->request->post('total_lite');
                $total_out_lite = \Yii::$app->request->post('total_out_lite');
                $total_amount = \Yii::$app->request->post('total_amount');
                $total_amount2 = \Yii::$app->request->post('total_amount2');

                $dropoff_id = \Yii::$app->request->post('dropoff_id');
                $dropoff_no = \Yii::$app->request->post('dropoff_no');
                $qty = \Yii::$app->request->post('qty');
                $weight = \Yii::$app->request->post('weight');
                $price_per_ton = \Yii::$app->request->post('price_per_ton');
                $price_line_total = \Yii::$app->request->post('price_line_total');
                $is_charter = \Yii::$app->request->post('is_charter');
                $route_no = \Yii::$app->request->post('line_route_no');

                // add new

                $oil_out_price_2 = \Yii::$app->request->post('oil_out_price_2');
                $total_out_lite_2 = \Yii::$app->request->post('total_out_lite_2');
                $total_amount3 = \Yii::$app->request->post('total_amount3');
                $oil_out_price_3 = \Yii::$app->request->post('oil_out_price_3');
                $total_out_lite_3 = \Yii::$app->request->post('total_out_lite_3');
                $total_amount4 = \Yii::$app->request->post('total_amount4');

                $company_id = \backend\models\Customer::findCompanyByCustomer($model->customer_id);

                //   print_r($weight); return ;
                $model->is_invoice = 0;
                $model->oil_daily_price = $oil_daily_price;
                $model->oil_out_price = $oil_out_price;
                $model->total_distance = $total_distance;
                $model->total_lite = $total_lite;
                $model->total_out_lite = $total_out_lite;
                $model->total_amount = $total_amount;
                $model->total_amount2 = $total_amount2;
                $model->company_id = $company_id;
                $model->oil_out_price_2 = $oil_out_price_2;
                $model->total_out_lite_2 = $total_out_lite_2;
                $model->total_amount3 = $total_amount3;
                $model->oil_out_price_3 = $oil_out_price_3;
                $model->total_out_lite_3 = $total_out_lite_3;
                $model->total_amount4 = $total_amount4;
                if ($model->save(false)) {

//                    echo '123'; return ;
                    if ($line_doc_name != null) {
                        for ($i = 0; $i <= count($line_doc_name) - 1; $i++) {

                            foreach ($uploaded as $key => $value) {
                                if ($key == $i) {
//                                    echo '123'; return ;
                                    if (!empty($value)) {
                                        $upfiles = time() . "." . $value->getExtension();
                                        // if ($uploaded->saveAs(Yii::$app->request->baseUrl . '/uploads/files/' . $upfiles)) {
                                        if ($value->saveAs('../web/uploads/workqueue_doc/' . $upfiles)) {
                                            $model_doc = new \common\models\WorkQueueLine();
                                            $model_doc->work_queue_id = $model->id;
                                            $model_doc->doc = $upfiles;
                                            $model_doc->description = $line_doc_name[$i];
                                            $model_doc->save(false);
                                        }
                                    }
                                }
                            }


                        }
                    }

                    if ($dropoff_id != null) {
                        for ($a = 0; $a <= count($dropoff_id) - 1; $a++) {
                            $model_df = new \common\models\WorkQueueDropoff();
                            $model_df->work_queue_id = $model->id;
                            $model_df->dropoff_id = $dropoff_id[$a];
                            $model_df->quotation_route_no = $route_no[$a];
                            $model_df->dropoff_no = $dropoff_no[$a];
                            $model_df->qty = $qty[$a];
                            $model_df->weight = $weight[$a];
                            $model_df->price_per_ton = $price_per_ton[$a];
                            $model_df->price_line_total = $price_line_total[$a];
                            $model_df->is_charter = $is_charter[$a];
                            $model_df->save(false);
                        }
                    }

//                    if ($model->route_plan_id != null) {
//                        if (count($model->route_plan_id) > 0) {
//                            for ($x = 0; $x <= count($model->route_plan_id) - 1; $x++) {
//                                $w_dropoff = new \common\models\WorkQueueDropoff();
//                                $w_dropoff->work_queue_id = $model->id;
//                                $w_dropoff->dropoff_id = $model->route_plan_id[$x];
//                                $w_dropoff->save(false);
//                            }
//                        }
//                    }
                    if ($model->item_back_id != null) {
                        if (count($model->item_back_id) > 0) {
                            for ($x = 0; $x <= count($model->item_back_id) - 1; $x++) {
                                $w_itemback = new \common\models\WorkQueueItemback();
                                $w_itemback->work_queue_id = $model->id;
                                $w_itemback->item_back_id = $model->item_back_id[$x];
                                $w_itemback->work_queue_type = 1;
                                $w_itemback->save(false);
                            }
                        }
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                }

            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Workqueue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model_line_doc = \common\models\WorkQueueLine::find()->where(['work_queue_id' => $id])->all();
        $w_dropoff = \common\models\WorkQueueDropoff::find()->where(['work_queue_id' => $id])->all();
        $w_itemback = \common\models\WorkQueueItemback::find()->where(['work_queue_id' => $id,'work_queue_type' => 1])->all();


        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->work_queue_date = date('Y-m-d', strtotime($model->work_queue_date));
            $removelist = \Yii::$app->request->post('remove_list');
            $line_doc_name = \Yii::$app->request->post('line_doc_name');
            // $line_file_name = \Yii::$app->request->post('line_file_name');
            $uploaded = UploadedFile::getInstancesByName('line_file_name');
            $line_id = \Yii::$app->request->post('rec_id');

            $dropoff_id = \Yii::$app->request->post('dropoff_id');
            $dropoff_no = \Yii::$app->request->post('dropoff_no');
            $qty = \Yii::$app->request->post('qty');
            $weight = \Yii::$app->request->post('weight');
            $price_per_ton = \Yii::$app->request->post('price_per_ton');
            $price_line_total = \Yii::$app->request->post('price_line_total');
            $route_no = \Yii::$app->request->post('line_route_no');

            $oil_daily_price = \Yii::$app->request->post('oil_daily_price');
            $oil_out_price = \Yii::$app->request->post('oil_out_price');
            $total_distance = \Yii::$app->request->post('total_distance');
            $total_lite = \Yii::$app->request->post('total_lite');
            $total_out_lite = \Yii::$app->request->post('total_out_lite');
            $total_amount = \Yii::$app->request->post('total_amount');
            $total_amount2 = \Yii::$app->request->post('total_amount2');

            $removelist2 = \Yii::$app->request->post('remove_list2');

            $is_charter = \Yii::$app->request->post('is_charter');

            // add new

            $oil_out_price_2 = \Yii::$app->request->post('oil_out_price_2');
            $total_out_lite_2 = \Yii::$app->request->post('total_out_lite_2');
            $total_amount3 = \Yii::$app->request->post('total_amount3');
            $oil_out_price_3 = \Yii::$app->request->post('oil_out_price_3');
            $total_out_lite_3 = \Yii::$app->request->post('total_out_lite_3');
            $total_amount4 = \Yii::$app->request->post('total_amount4');


//            print_r($dropoff_id);
//            print_r($weight);return;
            $model->oil_daily_price = $oil_daily_price;
            $model->oil_out_price = $oil_out_price;
            $model->total_distance = $total_distance;
            $model->total_lite = $total_lite;
            $model->total_out_lite = $total_out_lite;
            $model->total_amount = $total_amount;
            $model->total_amount2 = $total_amount2;
            $model->oil_out_price_2 = $oil_out_price_2;
            $model->total_out_lite_2 = $total_out_lite_2;
            $model->total_amount3 = $total_amount3;
            $model->oil_out_price_3 = $oil_out_price_3;
            $model->total_out_lite_3 = $total_out_lite_3;
            $model->total_amount4 = $total_amount4;

            if ($model->save(false)) {
                if ($line_id != null) {
                    // echo count($uploaded);return;
                    for ($i = 0; $i <= count($line_id) - 1; $i++) {
                        $model_check = \common\models\WorkQueueLine::find()->where(['id' => $line_id[$i]])->one();
                        if ($model_check) {
                            $model_check->description = $line_doc_name[$i];
                            $model_check->save(false);
                        } else {
                            foreach ($uploaded as $key => $value) {

                                if (!empty($value)) {
                                    $upfiles = time() + 2 . "." . $value->getExtension();
                                    // if ($uploaded->saveAs(Yii::$app->request->baseUrl . '/uploads/files/' . $upfiles)) {
                                    if ($value->saveAs('../web/uploads/workqueue_doc/' . $upfiles)) {
                                        $model_doc = new \common\models\WorkQueueLine();
                                        $model_doc->work_queue_id = $model->id;
                                        $model_doc->doc = $upfiles;
                                        $model_doc->description = $line_doc_name[$i];
                                        $model_doc->save(false);
                                    }
                                }
                            }
                        }
                    }
                }

                if ($dropoff_id != null) {
                    \common\models\WorkQueueDropoff::deleteAll(['work_queue_id' => $model->id]);
                    for ($a = 0; $a <= count($dropoff_id) - 1; $a++) {
                        $model_test = \common\models\WorkQueueDropoff::find()->where(['work_queue_id' => $model->id, 'dropoff_id' => $dropoff_id[$a], 'dropoff_no' => $dropoff_no[$a]])->one();
                        if ($model_test) {
                            $model_test->dropoff_id = $dropoff_id[$a];
                            $model_test->quotation_route_no = $route_no[$a];
                            $model_test->dropoff_no = $dropoff_no[$a];
                            $model_test->qty = (float)$qty[$a];
                            $model_test->weight = (float)$weight[$a];
                            $model_test->price_per_ton = (float)$price_per_ton[$a];
                            $model_test->price_line_total = (float)$price_line_total[$a];
                            $model_test->is_charter = $is_charter[$a];
                            $model_test->save(false);
                        } else {
                            $model_do = new \common\models\WorkQueueDropoff();
                            $model_do->work_queue_id = $model->id;
                            $model_do->dropoff_id = $dropoff_id[$a];
                            $model_do->quotation_route_no = $route_no[$a];
                            $model_do->dropoff_no = $dropoff_no[$a];
                            $model_do->qty = (float)$qty[$a];
                            $model_do->weight = (float)$weight[$a];
                            $model_do->price_per_ton = (float)$price_per_ton[$a];
                            $model_do->price_line_total = (float)$price_line_total[$a];
                            $model_do->is_charter = $is_charter[$a];
                            $model_do->save(false);
                        }
                    }
                }

                $delete_rec = explode(",", $removelist);
                if (count($delete_rec)) {
                    $model_find_doc_delete = \common\models\WorkQueueLine::find()->where(['id' => $delete_rec])->one();
                    if ($model_find_doc_delete) {
                        if (file_exists(\Yii::getAlias('@backend') . '/web/uploads/workqueue_doc/' . $model_find_doc_delete->doc)) {
                            if (unlink(\Yii::getAlias('@backend') . '/web/uploads/workqueue_doc/' . $model_find_doc_delete->doc)) {
                                \common\models\WorkQueueLine::deleteAll(['id' => $delete_rec]);
                            }
                        }
                    }

                }

                $delete_rec2 = explode(",", $removelist2);
                if (count($delete_rec)) {
                    \common\models\WorkQueueDropoff::deleteAll(['id' => $delete_rec2]);

                }

//                if ($model->route_plan_id != null) {
//                    if (count($model->route_plan_id) > 0) {
//                        \common\models\WorkQueueDropoff::deleteAll(['work_queue_id' => $model->id]);
//                        for ($x = 0; $x <= count($model->route_plan_id) - 1; $x++) {
//                            $w_dropoff_new = new \common\models\WorkQueueDropoff();
//                            $w_dropoff_new->work_queue_id = $model->id;
//                            $w_dropoff_new->dropoff_id = $model->route_plan_id[$x];
//                            $w_dropoff_new->save(false);
//                        }
//                    }
//                }
                if ($model->item_back_id != null) {
                    if (count($model->item_back_id) > 0) {
                        \common\models\WorkQueueItemback::deleteAll(['work_queue_id' => $model->id,'work_queue_type'=>1]);
                        for ($x = 0; $x <= count($model->item_back_id) - 1; $x++) {
                            $w_itemback_new = new \common\models\WorkQueueItemback();
                            $w_itemback_new->work_queue_id = $model->id;
                            $w_itemback_new->item_back_id = $model->item_back_id[$x];
                            $w_itemback_new->work_queue_type = 1;
                            $w_itemback_new->save(false);
                        }
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'model_line_doc' => $model_line_doc,
            'w_dropoff' => $w_dropoff,
            'w_itemback' => $w_itemback,
        ]);
    }

    /**
     * Deletes an existing Workqueue model.
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
     * Finds the Workqueue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Workqueue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Workqueue::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPrintdocx($id)
    {
        if ($id) {
            $model = \backend\models\Workqueue::find()->where(['id' => $id])->one();
            $modelline = \common\models\WorkQueueLine::find()->where(['work_queue_id' => $id])->all();
            return $this->render('_printdocx', [
                'model' => $model,
                'modelline' => $modelline,
            ]);
        }

    }

    public function actionExportdoc($id)
    {
        if ($id) {
            $model = \backend\models\Workqueue::find()->where(['id' => $id])->one();
            $modelline = \common\models\WorkQueueLine::find()->where(['work_queue_id' => $id])->all();
            return $this->render('_printdocx', [
                'model' => $model,
                'modelline' => $modelline,
            ]);
        }
    }

    public function actionApprovejob($id, $approve_id)
    {
//        $work_id = \Yii::$app->request->post('work_id');
//        $user_approve = \Yii::$app->request->post('user_approve_id');
        $work_id = $id;
        $user_approve = $approve_id;
        $res = 0;
        if ($work_id && $user_approve) {
            $model = \backend\models\Workqueue::find()->where(['id' => $work_id])->one();
            if ($model) {
                $model->approve_status = 1;
                $model->approve_by = $user_approve;
                if ($model->save(false)) {
                    $res = 1;
                }
            }

        }
        if ($res > 0) {
            $this->redirect(['workqueue/index']);
        } else {
            $this->redirect(['workqueue/index']);
        }
    }

    public function actionRemovedoc()
    {
        $workqueue_id = \Yii::$app->request->post('work_queue_id');
        $doc_name = \Yii::$app->request->post('doc_name');

        echo $workqueue_id . ' = ' . $doc_name;

        if ($workqueue_id && $doc_name != '') {
            if (file_exists(\Yii::getAlias('@backend') . '/web/uploads/workqueue_doc/' . $doc_name)) {
                if (unlink(\Yii::getAlias('@backend') . '/web/uploads/workqueue_doc/' . $doc_name)) {
//                    $model = \backend\models\Workqueue::find()->where(['id' => $workqueue_id])->one();
//                    if ($model) {
//                        $model->doc = '';
//                        $model->save(false);
//                    }
                }
            }
        } else {
            echo "no";
            return;
        }
        return $this->redirect(['workqueue/update', 'id' => $workqueue_id]);
    }

    public function actionCalupdatecompay()
    {
        $model = \backend\models\Workqueue::find()->where(['company_id' => 0])->all();
        foreach ($model as $value) {
            $model_car = \backend\models\Car::find()->where(['id' => $value->car_id])->one();
            if ($model_car) {
                \backend\models\Workqueue::updateAll(['company_id' => $model_car->company_id], ['id' => $value->id]);
            }
        }
    }

    public function actionGetpricefromquotation()
    {
        $dropoff_id = \Yii::$app->request->post('dropoff_id');
        $car_id = \Yii::$app->request->post('car_id');
        $route_no = \Yii::$app->request->post('route_no');
        $data = [];
        if ($dropoff_id && $car_id) {
            $model_car = \backend\models\Car::find()->select(['car_type_id'])->where(['id' => $car_id])->one();
            $model = \common\models\QueryQuotationPricePerTon::find()->where(['dropoff_id' => $dropoff_id, 'car_type_id' => $model_car->car_type_id, 'route_no' => $route_no])->one();
            if ($model) {
                array_push($data, ['price' => $model->price_current_rate]);
            }else{
                array_push($data, ['price' => 0]);
            }
        }
        return json_encode($data);
    }

    public function actionGetquotationrouote()
    {
        $dropoff_id = \Yii::$app->request->post('dropoff_id');
        $car_id = \Yii::$app->request->post('car_id');
        $html = '';
        if ($dropoff_id && $car_id) {
            $model_car = \backend\models\Car::find()->select(['car_type_id'])->where(['id' => $car_id])->one();
            $model = \common\models\QueryQuotationPricePerTon::find()->where(['dropoff_id' => $dropoff_id, 'car_type_id' => $model_car->car_type_id])->all();
            foreach ($model as $value) {
                $html .= '<option value="' . $value->route_no . '">' . $value->route_no . '</option>';
            }
        }
        echo $html;
    }

    public function actionGetcuspoitem()
    {
        $id = \Yii::$app->request->post('id');
        $html = '';
        if ($id) {
            $model = \backend\models\CustomerPoLine::find()->where(['po_id' => $id])->all();
            if ($model) {
                foreach($model as $value){
                    $html .= '<option value="' . $value->id . '">' . $value->item_name . '</option>';
                }

            }
        }
        echo $html;
    }
}
