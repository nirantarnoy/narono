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
class CashrecordsummaryController extends Controller
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
    public function actionIndex(){
        $search_company_id = \Yii::$app->request->post('search_company_id');
        $search_office_id = \Yii::$app->request->post('search_office_id');
        return $this->render('_index',[
            'search_company_id'=>$search_company_id,
            'search_office_id' => $search_office_id,
        ]);
    }
}