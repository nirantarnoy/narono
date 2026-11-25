<?php

namespace backend\controllers;

use backend\models\Cashrecord;
use backend\models\CashrecordSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CashrecordController implements the CRUD actions for Cashrecord model.
 */
class CashrecordreportController extends Controller
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
     * Lists all Cashrecord models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $find_cash_record_id = 0;
        if(!empty(\Yii::$app->request->post('find_cash_record_id'))){
            $find_cash_record_id = \Yii::$app->request->post('find_cash_record_id');
        }
        return $this->render('index', [
            'find_cash_record_id' => $find_cash_record_id,
        ]);
    }

}
