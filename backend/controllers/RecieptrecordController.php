<?php

namespace backend\controllers;
date_default_timezone_set('Asia/Bangkok');

use backend\models\Recieptrecord;
use backend\models\RecieptrecordSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RecieptrecordController implements the CRUD actions for Recieptrecord model.
 */
class RecieptrecordController extends Controller
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
     * Lists all Recieptrecord models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $pageSize = \Yii::$app->request->post("perpage");

        $searchModel = new RecieptrecordSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->pagination->pageSize = $pageSize;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'perpage' => $pageSize,
        ]);
    }

    /**
     * Displays a single Recieptrecord model.
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
     * Creates a new Recieptrecord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Recieptrecord();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

//                $new_date = $model->trans_date . ' ' . date('H:i:s');
//
//                $model->trans_date = date('Y-m-d H:i:s', strtotime($new_date));
                $model->journal_no = $model->getLastNo();


                $reciept_title_id = \Yii::$app->request->post('reciept_title_id');
                $amount = \Yii::$app->request->post('price_line');
                $ref_id = \Yii::$app->request->post('ref_id');
                $ref_no = \Yii::$app->request->post('ref_no');
                $remark = \Yii::$app->request->post('remark_line');

                $trans_date = date('Y-m-d');
                $x = explode('-', $model->trans_date);
                if (count($x) > 1) {
                    $trans_date = $x[2] . '/' . $x[1] . '/' . $x[0];
                }

                $model->trans_date = date('Y-m-d', strtotime($trans_date));

                $model->status = 1;
                if ($model->save(false)) {
                    if ($reciept_title_id != null) {
                        for ($i = 0; $i <= count($reciept_title_id) - 1; $i++) {
                            $model_line = new \common\models\RecieptRecordLine();
                            $model_line->reciept_record_id = $model->id;
                            $model_line->receipt_title_id = $reciept_title_id[$i];
                            $model_line->amount = $amount[$i];
                            $model_line->ref_id = $ref_id[$i];
                            $model_line->ref_no = $ref_no[$i];
                            $model_line->remark = $remark[$i];
                            $model_line->status = 1;
                            $model_line->save(false);


                        }
                    }
                    // create transaction
                    $model_trans = new \backend\models\Stocktrans();
                    $model_trans->trans_date = date('Y-m-d H:i:s');
                    $model_trans->activity_type_id = 6; // recieve record
                    $model_trans->trans_ref_id = $model->id;
                    $model_trans->save(false);

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
     * Updates an existing Recieptrecord model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model_line = \common\models\RecieptRecordLine::find()->where(['reciept_record_id' => $id])->all();

        if ($this->request->isPost && $model->load($this->request->post())) {

//            $new_date = $model->trans_date . ' ' . date('H:i:s');
//
//            $model->trans_date = date('Y-m-d H:i:s', strtotime($new_date));
//                $model->journal_no = $model->getLastNo();


            $reciept_title_id = \Yii::$app->request->post('reciept_title_id');
            $amount = \Yii::$app->request->post('price_line');
            $ref_id = \Yii::$app->request->post('ref_id');
            $ref_no = \Yii::$app->request->post('ref_no');
            $remark = \Yii::$app->request->post('remark_line');
            $line_id = \Yii::$app->request->post('rec_id');

            $removelist = \Yii::$app->request->post('remove_list2');
            $trans_date = date('Y-m-d');
            $x = explode('-', $model->trans_date);
            if (count($x) > 1) {
                $trans_date = $x[2] . '/' . $x[1] . '/' . $x[0];
            }

            $model->trans_date = date('Y-m-d', strtotime($trans_date));

            if ($model->save(false)) {

                if ($line_id != null) {
                    for ($i = 0; $i <= count($line_id) - 1; $i++) {
                        $model_chk = \common\models\RecieptRecordLine::find()->where(['id' => $line_id[$i]])->one();
                        if ($model_chk) {
                            $model_chk->receipt_title_id = $reciept_title_id[$i];
                            $model_chk->amount = $amount[$i];
                            $model_chk->ref_id = $ref_id[$i];
                            $model_chk->ref_no = $ref_no[$i];
                            $model_chk->remark = $remark[$i];
                            $model_chk->save(false);
                        } else {
                            $model_rec = new \common\models\RecieptRecordLine();
                            $model_rec->reciept_record_id = $model->id;
                            $model_rec->receipt_title_id = $reciept_title_id[$i];
                            $model_rec->amount = $amount[$i];
                            $model_rec->ref_id = $ref_id[$i];
                            $model_rec->ref_no = $ref_no[$i];
                            $model_rec->remark = $remark[$i];
                            $model_rec->status = 1;
                            $model_rec->save(false);
                        }
                    }
                }

                $delete_rec = explode(",", $removelist);
                if (count($delete_rec)) {
                    \common\models\RecieptRecordLine::deleteAll(['id' => $delete_rec]);

                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'model_line' => $model_line,
        ]);
    }

    /**
     * Deletes an existing Recieptrecord model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Recieptrecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Recieptrecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = Recieptrecord::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    function actionPrint($id)
    {
        $model = \backend\models\Recieptrecord::find()->where(['id' => $id])->one();
        $model_line = \common\models\RecieptRecordLine::find()->where(['reciept_record_id' => $id])->all();
        return $this->render('_print', [
            'model' => $model,
            'model_line' => $model_line,
        ]);
    }

    public function actionGetoffice()
    {
        $html = '';
        $company_id = \Yii::$app->request->post('company_id');
        if($company_id){
            $model = \common\models\Location::find()->where(['company_id' => $company_id])->all();
            if($model){
                foreach ($model as $key => $value) {
                    $html .= '<option value="'.$value->id.'">'.$value->name.'</option>';
                }

            }
        }
        echo $html;
    }
}
