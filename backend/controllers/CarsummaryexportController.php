<?php

namespace backend\controllers;

use backend\models\RoutePlan;
use backend\models\RouteplanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RouteplanController implements the CRUD actions for Routeplan model.
 */
class CarsummaryexportController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $from_date = \Yii::$app->request->post('search_from_date');
        $to_date = \Yii::$app->request->post('search_to_date');
        $search_car_id = \Yii::$app->request->post('search_car_id');
        return $this->render('_index',[
            'from_date'=> $from_date,
            'to_date'=> $to_date,
            'search_car_id'=> $search_car_id,
        ]);
    }
}