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
class CashreportdailyController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex(){
        $search_date = \Yii::$app->request->post('search_date');
        $search_to_date = \Yii::$app->request->post('search_to_date');
        $search_cost_type = \Yii::$app->request->post('search_cost_type');
        $search_company_id = \Yii::$app->request->post('search_company_id');
        $search_office_id = \Yii::$app->request->post('search_office_id');

        $this->layout = 'main_print_landcape';

        return $this->render('_index',[
            'search_date' => $search_date,
            'search_to_date' => $search_to_date,
            'search_cost_type' => $search_cost_type,
            'search_company_id' =>$search_company_id,
            'search_office_id' =>$search_office_id,
        ]);
    }
}