<?php

namespace backend\controllers;

use backend\models\Cityzone;
use backend\models\CityzoneSearch;
use backend\models\FuelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CityzoneController implements the CRUD actions for Cityzone model.
 */
class CityzoneController extends Controller
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
     * Lists all Cityzone models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $pageSize = \Yii::$app->request->post("perpage");

        $searchModel = new CityzoneSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->pagination->pageSize = $pageSize;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'perpage' => $pageSize,
        ]);
    }

    /**
     * Displays a single Cityzone model.
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
     * Creates a new Cityzone model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Cityzone();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $line = $model->city_id;
                //  print_r($line);return;
                $model->city_id = 0;
                if ($model->save(false)) {
                    if ($line != null) {
                        for ($i = 0; $i <= count($line) - 1; $i++) {
                            $model_line = new \common\models\CityzoneLine();
                            $model_line->cityzone_id = $model->id;
                            $model_line->province_id = $model->province_id;
                            $model_line->city_id = $line[$i];
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
     * Updates an existing Cityzone model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $cityzone_data = [];
        $cityzone_district_data = [];
        $model_line = \common\models\CityzoneLine::find()->where(['cityzone_id' => $id])->andFilterWhere(['>','city_id',0])->all();
        if ($model_line) {
            foreach ($model_line as $value) {
                array_push($cityzone_data, $value->city_id);
            }
        }
        $model_district_line = \common\models\CityzoneDistrictLine::find()->where(['cityzone_id' => $id])->andFilterWhere(['>','district_id',0])->all();
        if ($model_district_line) {
            foreach ($model_district_line as $value) {
                array_push($cityzone_district_data, $value->district_id);
            }
        }


        if ($this->request->isPost && $model->load($this->request->post())) {

            $zone_line = $model->city_id;
            $zone_district_line = $model->district_id;
            //print_r($zone_line);return;
            $model->city_id = 0;
            if ($model->save(false)) {
                if ($zone_line != null) {
                    \common\models\CityzoneLine::deleteAll(['cityzone_id' => $id]);

                    for ($i = 0; $i <= count($zone_line) - 1; $i++) {
                        if($zone_line[$i] <=0)continue;
//                        $check = \common\models\CityzoneLine::find()->where(['city_id'=>$zone_line[$i],'province_id'=>$model->province_id])->one();
//                        if($check){
//
//                        }else{
                            $model_line = new \common\models\CityzoneLine();
                            $model_line->cityzone_id = $model->id;
                            $model_line->province_id = $model->province_id;
                            $model_line->city_id = $zone_line[$i];
                            $model_line->save(false);
//                        }
//
                    }
                }
                if ($zone_district_line != null) {
                    \common\models\CityzoneDistrictLine::deleteAll(['cityzone_id' => $id]);

                    for ($i = 0; $i <= count($zone_district_line) - 1; $i++) {
                        if($zone_district_line[$i] <=0)continue;
//                        $check = \common\models\CityzoneLine::find()->where(['city_id'=>$zone_line[$i],'province_id'=>$model->province_id])->one();
//                        if($check){
//
//                        }else{
                        $model_line = new \common\models\CityzoneDistrictLine();
                        $model_line->cityzone_id = $model->id;
                        $model_line->province_id = $model->province_id;
                        $model_line->city_id = $this->getCityid($zone_district_line[$i]);
                        $model_line->district_id = $zone_district_line[$i];
                        $model_line->save(false);
//                        }
//
                    }
                }

            }
            return $this->redirect(['view', 'id' => $model->id]);
        }


        return $this->render('update', [
            'model' => $model,
            'zone_line_data' => $cityzone_data,
            'zone_line_district_data' => $cityzone_district_data,
        ]);
    }

    public function getCityid($district_id){
        $city_id = 0;
        if($district_id){
            $model = \common\models\District::find()->where(['DISTRICT_ID'=>$district_id])->one();
            if($model){
                $city_id = $model->AMPHUR_ID;
            }
        }
        return $city_id;
    }

    /**
     * Deletes an existing Cityzone model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        \common\models\CityzoneLine::deleteAll(['cityzone_id' => $id]);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Cityzone model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Cityzone the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cityzone::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetcityzone($id)
    {
        $model = \common\models\CityzoneLine::find()->where(['cityzone_id' => $id])->all();

        if (count($model) > 0) {
            echo "<option>--- เลือกอำเภอ ---</option>";
            foreach ($model as $value) {

                echo "<option value='" . $value->AMPHUR_ID . "'>$value->AMPHUR_NAME</option>";

            }
        } else {
            echo "<option>-</option>";
        }
    }
}
