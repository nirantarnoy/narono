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
class CashrecordreportdailyController extends Controller
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
     * Lists all Cashrecord models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $from_date = \Yii::$app->request->post('search_from_date');
        $to_date = \Yii::$app->request->post('search_to_date');
        $search_company_id = \Yii::$app->request->post('search_company_id');
        $search_office_id = \Yii::$app->request->post('search_office_id');

      //  echo $search_office_id;

        $x1 = explode('-', $from_date);
        $x2 = explode('-', $to_date);

        $from_date_new = date("Y-m-d");
        $to_date_new = date("Y-m-d");

        if ($x1 != null && count($x1) > 1) {
            //echo count($x1);
            $from_date_new = $x1[2] . '-' . $x1[1] . '-' . $x1[0];
        }
        if ($x2 != null && count($x2) > 1) {
            $to_date_new = $x2[2] . '-' . $x2[1] . '-' . $x2[0];
        }
        //echo $from_date_new;
        return $this->render('index', [
            'from_date' => $from_date_new,
            'to_date' => $to_date_new,
            'search_company_id' =>$search_company_id,
            'search_office_id' =>$search_office_id,
        ]);

    }
}
