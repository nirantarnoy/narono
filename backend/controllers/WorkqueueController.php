<?php

namespace backend\controllers;

use backend\models\Workqueue;
use backend\models\WorkqueueSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        $pageSize = \Yii::$app->request->post("perpage");

        $searchModel = new WorkqueueSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

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
                $model->work_queue_date = date('Y-m-d',strtotime($model->work_queue_date));

                $model->work_queue_no = $model->getLastNo();
                if ($model->save(false)){

                }
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
     * Updates an existing Workqueue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) ) {
            $model->work_queue_date = date('Y-m-d',strtotime($model->work_queue_date));
            if ($model->save()){

            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
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

    public function actionApprovejob($id,$approve_id){
//        $work_id = \Yii::$app->request->post('work_id');
//        $user_approve = \Yii::$app->request->post('user_approve_id');
        $work_id = $id;
        $user_approve = $approve_id;
        $res = 0;
        if($work_id && $user_approve){
            $model = \backend\models\Workqueue::find()->where(['id'=>$work_id])->one();
            if($model){
                $model->approve_status = 1;
                $model->approve_by = $user_approve;
                if($model->save(false)){
                    $res = 1;
                }
            }

        }
        if($res > 0){
            $this->redirect(['workqueue/index']);
        }else{
            $this->redirect(['workqueue/index']);
        }
    }
}
