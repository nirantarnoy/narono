<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;

/**
 * MinweightController implements the CRUD actions for MinWeight model.
 */
class MinweightController extends Controller
{
    /**
     * Lists all MinWeight models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
