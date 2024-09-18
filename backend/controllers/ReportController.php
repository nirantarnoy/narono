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
class ReportController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionReport1(){
        $from_date = \Yii::$app->request->post('from_date');
        $to_date = \Yii::$app->request->post('to_date');
        $viewday_amt = \Yii::$app->request->post('viewday_amt');

        if($viewday_amt == null){
            $viewday_amt = 0;
        }

//        echo $from_date;
//        echo $to_date;
//        return;

        $x1 = explode('/',$from_date);
        $x2 = explode('/',$to_date);

        $from_date_new = date("Y-m-d");
        $to_date_new = date("Y-m-d");

        if($x1!=null && count($x1)>1){
            //echo count($x1);
            $from_date_new = $x1[2].'-'.$x1[1].'-'.$x1[0];
        }
        if($x2!=null && count($x2)>1){
            $to_date_new = $x2[2].'-'.$x2[1].'-'.$x2[0];
        }
         //echo $from_date_new;
        return $this->render('_report1',[
          'from_date'=>$from_date_new,
          'to_date'=>$to_date_new,
          'viewday_amt' => $viewday_amt,
      ]);
    }
    public function actionReport2(){
        $car_search = \Yii::$app->request->post('car_search');
        $search_date = \Yii::$app->request->post('search_date');
        $search_to_date = \Yii::$app->request->post('search_to_date');
        $search_car_id = \Yii::$app->request->post('search_car_id');
        $search_emp_id  = \Yii::$app->request->post('search_emp_id');
        return $this->render('_report2',[
            'car_search' => $car_search,
            'search_date' => $search_date,
            'search_to_date' => $search_to_date,
            'search_car_id' => $search_car_id,
            'search_emp_id' => $search_emp_id,
        ]);
    }
    public function actionWorkqueuedaily(){
        $search_date = \Yii::$app->request->post('search_date');
        $search_to_date = \Yii::$app->request->post('search_to_date');
        $search_company_id = \Yii::$app->request->post('search_company_id');
        $search_car_type = \Yii::$app->request->post('search_car_type');
        $search_car_id = \Yii::$app->request->post('search_car_id');
        $search_emp_id  = \Yii::$app->request->post('search_emp_id');
        return $this->render('_workqueuedaily',[
            'search_date' => $search_date,
            'search_car_type' => $search_car_type,
            'search_to_date' => $search_to_date,
            'search_company_id' => $search_company_id,
            'search_car_id' => $search_car_id,
            'search_emp_id' => $search_emp_id,
        ]);
    }
    public function actionReportcashrecord(){
        $search_date = \Yii::$app->request->post('search_date');
        $search_cost_type = \Yii::$app->request->post('search_cost_type');
        return $this->render('_cashreport',[
            'search_date' => $search_date,
            'search_cost_type' => $search_cost_type,
        ]);
    }
}