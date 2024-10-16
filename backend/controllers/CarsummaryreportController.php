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
class CarsummaryreportController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $from_date = \Yii::$app->request->post('search_from_date');
        $to_date = \Yii::$app->request->post('search_to_date');
        $search_car_id = \Yii::$app->request->post('search_car_id');
        $social_per = \Yii::$app->request->post('social_per');
        $search_emp_id = \Yii::$app->request->post('search_emp_id');

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
        return $this->render('_index', [
            'from_date' => $from_date_new,
            'to_date' => $to_date_new,
            'search_car_id' => $search_car_id,
            'social_per' => $social_per,
            'search_emp_id'=> $search_emp_id,
        ]);
    }

    public function actionIndexnew()
    {
        $from_date = \Yii::$app->request->post('search_from_date');
        $to_date = \Yii::$app->request->post('search_to_date');
        $search_car_id = \Yii::$app->request->post('search_car_id');

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

//        $this->layout='main_login';

        return $this->render('_index1', [
            'from_date' => $from_date_new,
            'to_date' => $to_date_new,
            'search_car_id' => $search_car_id,
        ]);
    }

    public function actionReport2()
    {
        return $this->render('_report2');
    }

    public function actionWorkqueuedaily()
    {
        $search_date = \Yii::$app->request->post('search_date');
        $search_car_type = \Yii::$app->request->post('search_car_type');
        return $this->render('_workqueuedaily', [
            'search_date' => $search_date,
            'search_car_type' => $search_car_type,
        ]);
    }

    public function actionExportindex()
    {
        $from_date = \Yii::$app->request->post('search_from_date');
        $to_date = \Yii::$app->request->post('search_to_date');
        $search_car_id = \Yii::$app->request->post('search_car_id');
       return $this->render('_exportindex',[
           'from_date'=> $from_date,
           'to_date'=> $to_date,
           'search_car_id'=> $search_car_id,
       ]);
    }
}