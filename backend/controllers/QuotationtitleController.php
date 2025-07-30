<?php

namespace backend\controllers;

use backend\models\ItemSearch;
use backend\models\Quotationtitle;
use backend\models\QuotationtitleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * QuotationtitleController implements the CRUD actions for Quotationtitle model.
 */
class QuotationtitleController extends Controller
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
     * Lists all Quotationtitle models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $viewstatus = 1;
        $pageSize = \Yii::$app->request->post("perpage");

        if (\Yii::$app->request->get('viewstatus') != null) {
            $viewstatus = \Yii::$app->request->get('viewstatus');
        }


        $searchModel = new QuotationtitleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        if ($viewstatus == 1) {
            $dataProvider->query->andFilterWhere(['status' => $viewstatus]);
        }
        if ($viewstatus == 2) {
            $dataProvider->query->andFilterWhere(['status' => 0]);
        }

        $dataProvider->pagination->pageSize = $pageSize;


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'perpage' => $pageSize,
            'viewstatus' => $viewstatus,
        ]);
    }

    /**
     * Displays a single Quotationtitle model.
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
     * Creates a new Quotationtitle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Quotationtitle();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $line_warehouse_id = \Yii::$app->request->post('line_warehouse_id');
                $line_route = \Yii::$app->request->post('line_route');
                $line_zone_id = \Yii::$app->request->post('line_zone_id');
                $line_distance = \Yii::$app->request->post('line_distance');
                $line_average = \Yii::$app->request->post('line_average');
                $line_quotation_price = \Yii::$app->request->post('line_quotation_price');
                $line_quotation_price_type_id = \Yii::$app->request->post('line_quotation_price_type_id');
                $line_drop_off_id = \Yii::$app->request->post('line_drop_off_id');


                $model->status = 1;
                if ($model->save(false)) {
                    if ($line_warehouse_id != null) {
                        for ($i = 0; $i <= count($line_warehouse_id) - 1; $i++) {
                            $model_line = new \common\models\QuotationRate();
                            $model_line->quotation_title_id = $model->id;
                            $model_line->province_id = $line_warehouse_id[$i];
                            $model_line->car_type_id = $model->car_type_id;
                            $model_line->distance = $line_distance[$i];
                            $model_line->route_code = $line_route[$i];
                            $model_line->price_current_rate = $line_quotation_price[$i];
                            $model_line->load_qty = $line_average[$i];
                            $model_line->zone_id = $line_zone_id[$i];
//                            $model_line->price_type_id = $line_quotation_price_type_id[$i];
//                            $model_line->drop_off_id = $line_drop_off_id[$i];
                            $model_line->save(false);
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
     * Updates an existing Quotationtitle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_line = \common\models\QuotationRate::find()->where(['quotation_title_id' => $id])->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $line_warehouse_id = \Yii::$app->request->post('line_warehouse_id');
            $line_route = \Yii::$app->request->post('line_route');
            $line_zone_id = \Yii::$app->request->post('line_zone_id');
            $line_distance = \Yii::$app->request->post('line_distance');
            $line_average = \Yii::$app->request->post('line_average');
            $line_quotation_price = \Yii::$app->request->post('line_quotation_price');
            $line_quotation_price_type_id = \Yii::$app->request->post('line_quotation_price_type_id');
            // $line_drop_off_id = \Yii::$app->request->post('line_drop_off_id');
            $line_rec_id = \Yii::$app->request->post('line_rec_id');

            $drop_off_id = \Yii::$app->request->post('drop_off_id');

            //echo count($line_warehouse_id);return;
            // print_r(\Yii::$app->request->post());return;
            if ($model->save(false)) {
                if ($line_warehouse_id != null) {
                   // \common\models\QuotationRate::deleteAll(['quotation_title_id' => $id]);
                    for ($i = 0; $i <= count($line_warehouse_id) - 1; $i++) {
                        $model_old = \common\models\QuotationRate::find()->where(['id' => $line_rec_id[$i]])->one();
                        if ($model_old != null) {
                            $model_old->quotation_title_id = $model->id;
                            $model_old->province_id = $line_warehouse_id[$i];
                            $model_old->car_type_id = $model->car_type_id;
                            $model_old->distance = $line_distance[$i];
                            $model_old->price_current_rate = $line_quotation_price[$i];
                            $model_old->load_qty = $line_average[$i];
                            $model_old->route_code = $line_route[$i];
                            $model_old->zone_id = $line_zone_id[$i];
                            // $model_old->price_type_id = $line_quotation_price_type_id[$i];
                            //  $model_old->drop_off_id = $line_drop_off_id[$i];
                            $model_old->save(false);
                        }else{
                            $model_line = new \common\models\QuotationRate();
                            $model_line->quotation_title_id = $model->id;
                            $model_line->province_id = $line_warehouse_id[$i];
                            $model_line->car_type_id = $model->car_type_id;
                            $model_line->distance = $line_distance[$i];
                            $model_line->price_current_rate = $line_quotation_price[$i];
                            $model_line->load_qty = $line_average[$i];
                            $model_line->route_code = $line_route[$i];
                            $model_line->zone_id = $line_zone_id[$i];
                            // $model_line->price_type_id = $line_quotation_price_type_id[$i];
                            //  $model_line->drop_off_id = $line_drop_off_id[$i];
                            $model_line->save(false);
                        }

                    }
                }
                if ($drop_off_id != null) {
                    if ($drop_off_id != null) {
                        \common\models\QuotationDropoff::deleteAll(['quotation_rate_id' => $id]);
                        for ($i = 0; $i <= count($drop_off_id) - 1; $i++) {
                            $modelx = new \common\models\QuotationDropoff();
                            $modelx->quotation_rate_id = $id;
                            $modelx->dropoff_id = $drop_off_id[$i];
                            $modelx->save(false);
                        }
                    }
                }
                $this->calRatehistory($model->id);
                return $this->redirect(['view', 'id' => $model->id]);
            }

        }

        return $this->render('update', [
            'model' => $model,
            'model_line' => $model_line,
        ]);
    }

    public function calRatehistory($quotation_title_id){
        if($quotation_title_id) {
            $factor_up = 1.01;
            $factor_down = 0.99;
            $curren_oil_price = \common\models\FuelPrice::find()->where(['fuel_id' => 1])->orderBy(['price_date' => SORT_DESC])->one();
            if ($curren_oil_price) {
                $model_quotation_rate = \common\models\QuotationRate::find()->where(['quotation_title_id' => $quotation_title_id])->all();
                if($model_quotation_rate){
                    foreach ($model_quotation_rate as $keyx => $valuex) {
                        $model = \common\models\QuotationRateHistory::find()->where(['quotation_title_id' => $quotation_title_id,'quotation_rate_id' => $valuex->id])->orderBy(['id' => SORT_DESC])->one();
                        if ($model) { // has record
                            $new_current_rate = 0;
                            if($curren_oil_price->price > $model->oil_price){ // current oil price is higher than history
                                $new_current_rate = round($valuex->price_current_rate * $factor_up);
                            }elseif($curren_oil_price->price < $model->oil_price){ // current oil price is lower than history
                                $new_current_rate = round($valuex->price_current_rate * $factor_down);
                            }else{
                                continue;
                            }

                            $res = \common\models\QuotationRate::updateAll(['price_current_rate' => $new_current_rate, 'oil_price' => $curren_oil_price->price], ['id' => $valuex->id]); // update line price and oil price
                            if($res){
                                $model_new_history = new \common\models\QuotationRateHistory();
                                $model_new_history->quotation_title_id = $quotation_title_id;
                                $model_new_history->quotation_rate_id = $valuex->id;
                                $model_new_history->oil_price = $valuex->oil_price == null ? $curren_oil_price->price : $valuex->oil_price;
                                $model_new_history->rate_amount = round($valuex->price_current_rate);
                                $model_new_history->created_at = time();
                                $model_new_history->created_by = \Yii::$app->user->id;
                                $model_new_history->save(false);
                            }
                        }else{ // no record
                            $model_new_history = new \common\models\QuotationRateHistory();
                            $model_new_history->quotation_title_id = $quotation_title_id;
                            $model_new_history->quotation_rate_id = $valuex->id;
                            $model_new_history->oil_price = $valuex->oil_price == null ? $curren_oil_price->price : $valuex->oil_price;
                            $model_new_history->rate_amount = round($valuex->price_current_rate);
                            $model_new_history->created_at = time();
                            $model_new_history->created_by = \Yii::$app->user->id;
                            $model_new_history->save(false);
                        }
                    }
                }
                \common\models\QuotationTitle::updateAll(['fuel_rate' => $curren_oil_price->price], ['id' => $quotation_title_id]);

            }
        }
    }

    public function actionReturnhistory(){
        $model = \common\models\QuotationRateHistory::find()->where(['quotation_title_id' => 8])->all();
        print_r($model);return;
        if($model){
            foreach ($model as $key => $value) {
                \common\models\QuotationRate::updateAll(['price_current_rate' => $value->rate_amount, 'oil_price' => $value->oil_price], ['id' => $value->quotation_rate_id]);
            }
        }
    }

    /**
     * Deletes an existing Quotationtitle model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        \common\models\QuotationDropoff::deleteAll(['quotation_rate_id' => $id]);
        \common\models\QuotationRate::deleteAll(['quotation_title_id' => $id]);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionPrintquotationview()
    {
        $quotatioin_id = \Yii::$app->request->post('quotation_id');
        if ($quotatioin_id) {
            $model = $this->findModel($quotatioin_id);
            $model_line = \common\models\QuotationRate::find()->where(['quotation_title_id' => $quotatioin_id])->all();

            return $this->render('_printquotationview', [
                'model' => $model,
                'model_line' => $model_line,
            ]);
        }
    }

    /**
     * Finds the Quotationtitle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Quotationtitle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Quotationtitle::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetcityzone()
    {
        $province_id = \Yii::$app->request->post('province_id');
        $html = '<option value="-1">--เลือกโซน--</option>';
        if ($province_id > 0) {
            $model = \backend\models\Cityzone::find()->where(['province_id' => $province_id])->all();
            if ($model) {
                foreach ($model as $value) {
                    $detail = $this->getCityzonedetail($value->id);
                    $html .= '<option value="' . $value->id . '">';
                    $html .= $detail;
                    $html .= '</option>';
                }
            }
        }
        echo $html;
    }

    public function getCityzonedetail($city_zone_id)
    {
        $name = '';
        if ($city_zone_id) {
            $model = \common\models\CityzoneLine::find()->where(['cityzone_id' => $city_zone_id])->all();
            if ($model) {
                foreach ($model as $value) {
                    $name .= \backend\models\Amphur::findAmphurName($value->city_id) . ',';
                }
            }
            $model_district = \common\models\CityzoneDistrictLine::find()->where(['cityzone_id' => $city_zone_id])->all();
            if ($model_district) {
                foreach ($model_district as $valuex) {
                    $name .= \backend\models\District::findDistrictName($valuex->district_id) . ',';
                }
            }
        }
        return $name;
    }

    public function actionGetprovince()
    {
        $html = '<option value="-1">----เลือกจังหวัด---</option>';
        $model_province = \backend\models\Province::find()->all();
        if ($model_province) {
            foreach ($model_province as $value) {
                $html .= '<option value="' . $value->PROVINCE_ID . '">' . $value->PROVINCE_NAME . '</option>';
            }
        }
        echo $html;
    }

    public function actionSavedropoff()
    {
        $drop_off_id = \Yii::$app->request->post('drop_off_id');
        $quotation_rate_id = \Yii::$app->request->post('quotation_rate_id');
        $quot_id = \Yii::$app->request->post('quotation_id');

        if ($quotation_rate_id != null) {
            if ($drop_off_id != null) {
                if ($drop_off_id != null) {
                    for ($i = 0; $i <= count($drop_off_id) - 1; $i++) {
                        $model = new \common\models\QuotationDropoff();
                        $model->quotation_rate_id = $quotation_rate_id;
                        $model->dropoff_id = $drop_off_id[$i];
                        $model->save(false);
                    }
                }
            }
        }
        return $this->redirect(['update', 'id' => $quot_id]);
    }

    public function actionGetquoteratedropoff()
    {
        $quote_rate_id = \Yii::$app->request->post('quote_rate_id');
        $html = '';
        if ($quote_rate_id != null) {
            $model_dropoff = \common\models\DropoffPlace::find()->where(['status' => 1])->all();
            if ($model_dropoff) {
                $data = [];
                $model = \common\models\QuotationDropoff::find()->where(['quotation_rate_id' => $quote_rate_id])->all();
                if ($model) {
                    foreach ($model as $value) {
                        array_push($data, $value->dropoff_id);
                    }
                }
                foreach ($model_dropoff as $value) {
                    $selected = '';
                    if (in_array($value->id, $data)) {
                        $selected = 'selected';
                    }
                    $html .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->name . '</option>';
                }
            }


        }
        echo $html;
    }

    public function actionCopyquotation()
    {
        $id = \Yii::$app->request->post('quotation_id');
        if ($id) {
            $model = \common\models\QuotationTitle::find()->where(['id' => $id])->one();
            if ($model) {
                $model_new_quotation = new \backend\models\Quotationtitle();
                $model_new_quotation->attributes = $model->attributes;
                $model_new_quotation->isNewRecord = true;
                $model_new_quotation->id = null;
                if ($model_new_quotation->save(false)) {
                    $quotation_rate = \common\models\QuotationRate::find()->where(['quotation_title_id' => $id])->all();
                    if ($quotation_rate) {
                        foreach ($quotation_rate as $value) {
                            $model_new_quotation_rate = new \common\models\QuotationRate();
                            $model_new_quotation_rate->attributes = $value->attributes;
                            $model_new_quotation_rate->quotation_title_id = $model_new_quotation->id;
                            $model_new_quotation_rate->isNewRecord = true;
                            $model_new_quotation_rate->id = null;
                            $model_new_quotation_rate->save(false);
                        }
                    }
                    $quotation_dropoff = \common\models\QuotationDropoff::find()->where(['quotation_rate_id' => $id])->all();
                    if ($quotation_dropoff) {
                        foreach ($quotation_dropoff as $value) {
                            $model_new_quotation_dropoff = new \common\models\QuotationDropoff();
                            $model_new_quotation_dropoff->attributes = $value->attributes;
                            $model_new_quotation_dropoff->quotation_rate_id = $model_new_quotation->id;
                            $model_new_quotation_dropoff->isNewRecord = true;
                            $model_new_quotation_dropoff->id = null;
                            $model_new_quotation_dropoff->save(false);
                        }
                    }
                    \Yii::$app->session->setFlash('success', 'คัดลอกข้อมูลเรียบร้อยแล้ว');
                    return $this->redirect(['update', 'id' => $model_new_quotation->id]);
                }

            }
        }
        return $this->redirect(['index']);
    }
}
