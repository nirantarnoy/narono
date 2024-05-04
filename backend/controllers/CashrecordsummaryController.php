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
        return $this->render('_index');
    }
}