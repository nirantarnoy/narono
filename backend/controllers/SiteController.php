<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error','logindriver'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'changepassword','grab','logoutdriver'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $f_date = null;
        $t_date = null;

        $dashboard_date = Yii::$app->request->post('dashboard_date');
        if (!empty($dashboard_date)) {
            $dates = explode(' - ', $dashboard_date);
            if (count($dates) == 2) {
                $f_date = date('Y-m-d', strtotime(str_replace('/', '-', trim($dates[0]))));
                $t_date = date('Y-m-d', strtotime(str_replace('/', '-', trim($dates[1]))));
            }
        }

        // Count cars directly in SQL
        $car_qty = (int)(new \yii\db\Query())
            ->from('{{%car}}')
            ->count();

        // Count work_queue totals and status breakdown in a single fast SQL aggregate query
        $query = (new \yii\db\Query())
            ->from('{{%work_queue}}')
            ->select([
                'total' => 'COUNT(*)',
                'complete' => 'COALESCE(SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END), 0)',
                'not_complete' => 'COALESCE(SUM(CASE WHEN status != 1 OR status IS NULL THEN 1 ELSE 0 END), 0)',
            ]);

        if (!empty($f_date) && !empty($t_date)) {
            $query->andWhere(['>=', 'work_queue_date', $f_date])
                  ->andWhere(['<=', 'work_queue_date', $t_date]);
        }

        $stats = $query->one();

        $work_queue_qty = (int)($stats['total'] ?? 0);
        $work_queue_complete_qty = (int)($stats['complete'] ?? 0);
        $work_queue_not_complete_qty = (int)($stats['not_complete'] ?? 0);

        return $this->render('index', [
            'f_date' => $f_date,
            't_date' => $t_date,
            'car_qty' => $car_qty,
            'work_queue_qty' => $work_queue_qty,
            'work_queue_complete_qty' => $work_queue_complete_qty,
            'work_queue_not_complete_qty' => $work_queue_not_complete_qty,
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //echo "login ok"; return;
            // return $this->goBack();
            return $this->redirect(['site/index']);
        }

        //   $model->password = '';
        $model->password = '';
        $this->layout = 'main_login';
        $model->password = '';
        return $this->render('login_new', [
            'model' => $model,
        ]);


    }


    public function actionDriverlogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //echo "login ok"; return;
            // return $this->goBack();
//            return $this->redirect(['site/index']);
            $_SESSION['driver_login']=\Yii::$app->user->id;

            return $this->redirect(['carsummaryreport/indexnew']);
        }

        //   $model->password = '';
        $model->password = '';
        $this->layout = 'main_login';
        $model->password = '';
        return $this->render('login_new1', [
            'model' => $model,
        ]);


    }

    public function actionLogindriver()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //echo "login ok"; return;
            // return $this->goBack();
//            return $this->redirect(['workqueuereceive/index']);

            $_SESSION['driver_login']=\Yii::$app->user->id;

            return $this->redirect(['carsummaryreport/indexnew']);

        }

        //   $model->password = '';
        $model->password = '';
        $this->layout = 'main_login';
        $model->password = '';
        return $this->render('login_emp_car', [
            'model' => $model,
        ]);


    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        \Yii::$app->user->logout();

//        if(isset($_SESSION['driver_login'])){
//            return $this->redirect(['site/logindriver']);
//        }

        return $this->goHome();
    }
    public function actionLogoutdriver()
    {
        \Yii::$app->user->logout();

        return $this->redirect(['site/logindriver']);
    }

    public function actionChangepassword()
    {
        $model = new \backend\models\Resetform();
        if ($model->load(Yii::$app->request->post())) {

            $model_user = \backend\models\User::find()->where(['id' => Yii::$app->user->id])->one();
            if ($model->oldpw != '' && $model->newpw != '' && $model->confirmpw != '') {
                if ($model->confirmpw != $model->newpw) {
                    $session = Yii::$app->session;
                    $session->setFlash('msg_err', 'รหัสยืนยันไม่ตรงกับรหัสใหม่');
                } else {
                    if ($model_user && $model_user->validatePassword($model->oldpw)) {
                        $model_user->setPassword($model->confirmpw);
                        $model_user->generateAuthKey();
                        if ($model_user->save(false)) {
                            \Yii::$app->user->logout();
                            $session = Yii::$app->session;
                            $session->open();
                            $session->setFlash('msg_success', 'ทำการเปลี่ยนรหัสผ่านเรียบร้อยแล้ว กรุณาเข้าสู่ระบบด้วยรหัสผ่านใหม่');
                            return $this->redirect(['site/login']);
                        }
                    } else {
                        $session = Yii::$app->session;
                        $session->setFlash('msg_err', 'รหัสผ่านเดิมไม่ถูกต้อง');
                    }
                }

            } else {
                $session = Yii::$app->session;
                $session->setFlash('msg_err', 'กรุณาป้อนข้อมูลให้ครบ');
            }

        }
        return $this->render('_setpassword', [
            'model' => $model
        ]);
    }

    public function actionGrab()
    {

        $aControllers = [];


        // $path = \Yii::$app->getBasePath() . 'icesystem/';
        $path = \Yii::$app->basePath;

        $ctrls = function ($path) use (&$ctrls, &$aControllers) {

            $oIterator = new \DirectoryIterator($path);

            foreach ($oIterator as $oFile) {

                if (!$oFile->isDot()

                    && (false !== strpos($oFile->getPathname(), 'controllers')

                        || false !== strpos($oFile->getPathname(), 'modules')

                    )

                ) {


                    if ($oFile->isDir()) {

                        $ctrls($oFile->getPathname());

                    } else {

                        if (strpos($oFile->getBasename(), 'Controller.php')) {


                            $content = file_get_contents($oFile->getPathname());

                            $controllerName = $oFile->getBasename('.php');


                            $route = explode(\Yii::$app->basePath, $oFile->getPathname());

                            $route = str_ireplace(array('modules', 'controllers', 'Controller.php'), '', $route[1]);

                            $route = preg_replace("/(\/){2,}/", "/", $route);


                            $aControllers[$controllerName] = [

                                'filepath' => $oFile->getPathname(),

                                'route' => mb_strtolower($route),

                                'actions' => [],

                            ];

                            preg_match_all('#function action(.*)\(#ui', $content, $aData);


                            $acts = function ($aData) use (&$aControllers, &$controllerName) {


                                if (!empty($aData) && isset($aData[1]) && !empty($aData[1])) {


                                    $aControllers[$controllerName]['actions'] = array_map(

                                        function ($actionName) {
                                            return mb_strtolower(trim($actionName, '{\\.*()'));
                                        },

                                        $aData[1]

                                    );


                                }

                            };


                            $acts($aData);

                        }

                    }


                }

            }

        };


        $ctrls($path);


        echo '<pre>';

        //   print_r($aControllers);

        foreach ($aControllers as $value) {

            //  $route_name = substr($value['route'],2);
            $route_name = substr($value['route'], 1);
            for ($x = 0; $x <= count($value['actions']) - 1; $x++) {
                $fullname = $route_name . '/' . $value['actions'][$x];
                if ($fullname != '') {
                    $chk = \common\models\AuthItem::find()->where(['name' => $fullname])->one();
                    if ($chk) continue;

                    $model = new \common\models\AuthItem();
                    $model->name = $fullname;
                    $model->type = 2;
                    $model->description = '';
                    $model->created_at = time();
                    $model->save(false);
                }
                echo $fullname . '<br/>';

            }
            //echo $route_name;
            // print_r($value['route']);
        }
        // print_r($aControllers['AdjustmentController']);

    }

}
