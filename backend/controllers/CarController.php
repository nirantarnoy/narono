<?php

namespace backend\controllers;

use backend\models\Car;
use backend\models\CarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        return $this->render('view', [
            'model' => $this->findModel($id),
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
     * Updates an existing Car model.
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

            array_push($data, ['plate_no' => $plate_no, 'hp' => $hp, 'car_type' => $car_type]);
        }
        echo json_encode($data);
    }
    public function actionGetrouteplan()
    {
        $id = \Yii::$app->request->post('route_plan_id');
        $data = [];
        if ($id) {
            $distance = 0;
            $total_rate_qty = 0;
            $total_dropoff_qty = 0;

            $model = \common\models\RoutePlan::find()->where(['id'=>$id])->one();
            if($model){
                $distance = $model->total_distanct;
                $total_rate_qty = $model->oil_rate_qty;
            }
            $model_line_qty = \common\models\RoutePlanLine::find()->where(['route_plan_id'=>$id])->sum('dropoff_qty');
            if($model_line_qty){
                $total_rate_qty = $model_line_qty;
            }

            array_push($data, ['total_distance' => $distance, 'total_rate_qty' => $total_rate_qty,'total_dropoff_rate_qty'=>$total_dropoff_qty]);
        }
        echo json_encode($data);
    }
}
