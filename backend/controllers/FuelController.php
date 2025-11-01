<?php

namespace backend\controllers;

use backend\models\Fuel;
use backend\models\FuelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FuelController implements the CRUD actions for Fuel model.
 */
class FuelController extends Controller
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
     * Lists all Fuel models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $pageSize = \Yii::$app->request->post("perpage");

        $searchModel = new FuelSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->pagination->pageSize = $pageSize;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'perpage' => $pageSize,
        ]);
    }

    /**
     * Displays a single Fuel model.
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
     * Creates a new Fuel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Fuel();

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
     * Updates an existing Fuel model.
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
     * Deletes an existing Fuel model.
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
     * Finds the Fuel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Fuel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fuel::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionActiveprice()
    {
        $fuel_name = \Yii::$app->request->post('line_name');
        $fuel_price = \Yii::$app->request->post('line_price');

        if ($fuel_name != null && $fuel_price != null) {
            for ($i = 0; $i <= count($fuel_name) - 1; $i++) {
                $fuel_id = \backend\models\Fuel::findId($fuel_name[$i]);
                if($fuel_id){
                    $model_check = \common\models\FuelPrice::find()->where(['fuel_idx'=>$fuel_id])->andFilterWhere(['date(price_date)'=>date('Y-m-d')])->one();
                    if($model_check)continue;
                    $model = new \common\models\FuelPrice();
                    $model->fuel_id = $fuel_id;
                    $model->price = $fuel_price[$i];
                    $model->price_date = date('Y-m-d H:i:s');
                    $model->status = 1;
                    if ($model->save(false)) {
                        $model_update = \backend\models\Fuel::find()->where(['id' => $fuel_id])->one();
                        if ($model_update) {
                            $model_update->active_price = $fuel_price[$i];
                            $model_update->active_price_date = date('Y-m-d H:i:s');
                            $model_update->save(false);
                        }
                    }
                }

            }
        }

        \Yii::$app->session->setFlash('success',1);

        return $this->redirect(['site/index']);
    }
}
