<?php

namespace backend\controllers;

use backend\models\CarbrandSearch;
use backend\models\Employeefine;
use backend\models\EmployeefineSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EmployeefineController implements the CRUD actions for Employeefine model.
 */
class EmployeefineController extends Controller
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
     * Lists all Employeefine models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $pageSize = \Yii::$app->request->post("perpage");

        $searchModel = new EmployeefineSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->pagination->pageSize = $pageSize;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'perpage' => $pageSize,

        ]);
    }

    /**
     * Displays a single Employeefine model.
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
     * Creates a new Employeefine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Employeefine();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $district_id = \Yii::$app->request->post('district_id');
                $city_id = \Yii::$app->request->post('city_id');
                $province_id = \yii::$app->request->post('province_id');

                $model->district_id = $district_id;
                $model->city_id = $city_id;
                $model->province_id = $province_id;
              //  $model->trans_date = date('Y-m-d');


                $model->case_no = $model::getLastNo();
                if($model->save(false)){
                    return $this->redirect(['index']);
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
     * Updates an existing Employeefine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $district_id = \Yii::$app->request->post('district_id');
            $city_id = \Yii::$app->request->post('city_id');
            $province_id = \yii::$app->request->post('province_id');

            $model->district_id = $district_id;
            $model->city_id = $city_id;
            $model->province_id = $province_id;
            if($model->save(false)){
                return $this->redirect(['index']);
            }

        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Employeefine model.
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
     * Finds the Employeefine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Employeefine the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employeefine::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetemployee(){
        $html = '';
        $car_id = \Yii::$app->request->post('id');
        if($car_id){
           $model = \backend\models\Car::find()->where(['id'=>$car_id])->one();
           if($model){
               $model_driver = \backend\models\Employee::findFullName($model->driver_id);

               $html.='<option value="'.$model->driver_id.'">'.$model_driver.'</option>';
           }

        }
        echo $html;
    }
    public function actionReport()
    {
        $search_year = date('Y');
        $search_company_id = \Yii::$app->request->post("search_company_id");
        $find_month = \Yii::$app->request->post("find_month");

        return $this->render('_report', [
            'search_year' => $search_year,
            'search_company_id' => $search_company_id,
            'find_month' => $find_month,
        ]);
    }
}
