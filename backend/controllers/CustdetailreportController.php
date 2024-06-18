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
class CustdetailreportController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex(){
        $customer_id = \Yii::$app->request->post('find_customer_id');
        $year = \Yii::$app->request->post('find_year');
        $car_type_id = \Yii::$app->request->post('find_car_type_id');


        return $this->render('_index',[
            'find_year'=>$year,
            'car_type_id'=>$car_type_id,
            'find_customer_id'=>$customer_id,
        ]);
    }
}