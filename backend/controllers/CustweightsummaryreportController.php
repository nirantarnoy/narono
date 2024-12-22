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
class CustweightsummaryreportController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex(){
        $year = \Yii::$app->request->post('find_year');
        $month = \Yii::$app->request->post('find_month');
        $car_type_id = \Yii::$app->request->post('find_car_type_id');


        return $this->render('_index',[
            'find_year'=>$year,
            'find_month'=>$month,
            'car_type_id'=>$car_type_id,
        ]);
    }
}