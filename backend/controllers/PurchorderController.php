<?php

namespace backend\controllers;

use backend\models\Purchorder;
use backend\models\PurchorderSearch;
use backend\models\WarehouseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PurchorderController implements the CRUD actions for Purchorder model.
 */
class PurchorderController extends Controller
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
                        'delete' => ['POST','GET'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Purchorder models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $viewstatus = 1;

        if (\Yii::$app->request->get('viewstatus') != null) {
            $viewstatus = \Yii::$app->request->get('viewstatus');
        }

        $pageSize = \Yii::$app->request->post("perpage");
        $searchModel = new PurchorderSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
//        if($viewstatus ==1){
//            $dataProvider->query->andFilterWhere(['status'=>$viewstatus]);
//        }
//        if($viewstatus == 2){
//            $dataProvider->query->andFilterWhere(['status'=>0]);
//        }

        $dataProvider->setSort(['defaultOrder' => ['id' => SORT_DESC]]);
        $dataProvider->pagination->pageSize = $pageSize;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'perpage' => $pageSize,
            'viewstatus' => $viewstatus,
        ]);
    }

    /**
     * Displays a single Purchorder model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Purchorder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Purchorder();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $line_item_id =\Yii::$app->request->post('line_product_id');
                $line_qty =\Yii::$app->request->post('line_qty');
                $line_price =\Yii::$app->request->post('line_price');
                $line_total =\Yii::$app->request->post('line_total');

                $p_date =date('Y-m-d');
                $xdate = explode('-', $model->trans_date);
                if($xdate !=null){
                    if(count($xdate)>1){
                        $p_date = $xdate[2].'-'.$xdate[1].'-'.$xdate[0];
                    }
                }

                $model->trans_date = date('Y-m-d',strtotime($p_date));
                $model->status = 1;
                $model->purch_no = Purchorder::getLastNo();
                if($model->save(false)){
                    if($line_item_id!=null){
                        for($i=0;$i<=count($line_item_id)-1;$i++){
                            $model_line = new \common\models\PurchLine();
                            $model_line->purch_id = $model->id;
                            $model_line->product_id = $line_item_id[$i];
                            $model_line->qry = $line_qty[$i];
                            $model_line->remain_qty = $line_qty[$i];
                            $model_line->line_price = (float)$line_price[$i];
                            $model_line->line_total = (float)$line_total[$i];
                            $model_line->save(false);
                        }
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                }

            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Purchorder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_line = \common\models\PurchLine::find()->where(['purch_id'=>$id])->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $line_item_id =\Yii::$app->request->post('line_product_id');
            $line_qty =\Yii::$app->request->post('line_qty');
            $line_price =\Yii::$app->request->post('line_price');
            $line_total =\Yii::$app->request->post('line_total');
            $removelist = \Yii::$app->request->post('removelist');

                //print_r($line_item_id);return;

            $xdate = explode('-',$model->trans_date);
            $t_date = date('Y-m-d');
            if(count($xdate)>1){
                $t_date = $xdate[2].'-'.$xdate[1].'-'.$xdate[0];
            }
            $model->trans_date = date('Y-m-d',strtotime($t_date));
            if($model->save(false)){
                if($line_item_id!=null){
                    for($i=0;$i<=count($line_item_id)-1;$i++){
                        $model_check = \common\models\PurchLine::find()->where(['product_id'=>$line_item_id[$i]])->one();
                        if($model_check){
                            $model_check->product_id = $line_item_id[$i];
                            $model_check->qry = $line_qty[$i];
                            $model_check->remain_qty = $line_qty[$i];
                            $model_check->line_price = (float)$line_price[$i];
                            $model_check->line_total = (float)$line_total[$i];
                            $model_check->save(false);
                        }else{
                            $model_line = new \common\models\PurchLine();
                            $model_line->purch_id = $model->id;
                            $model_line->product_id = $line_item_id[$i];
                            $model_line->qry = $line_qty[$i];
                            $model_line->remain_qty = $line_qty[$i];
                            $model_line->line_price = (float)$line_price[$i];
                            $model_line->line_total = (float)$line_total[$i];
                            $model_line->save(false);
                        }

                    }
                }
                if($removelist !=null){
                    $x=explode(',',$removelist);
                    if($x!=null){
                        for($m=0;$m<=count($x)-1;$m++){
                            \common\models\PurchLine::deleteAll(['id'=>$x[$m]]);
                        }
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }

        }

        return $this->render('update', [
            'model' => $model,
            'model_line'=>$model_line,
        ]);
    }

    /**
     * Deletes an existing Purchorder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        \common\models\PurchLine::deleteAll(['purch_id'=>$id]);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Purchorder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Purchorder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchorder::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionFinditem()
    {
        $html = '';
        $has_data = 0;
        //$model = \backend\models\Workqueue::find()->where(['is_invoice' => 0])->all();
        $model = \backend\models\Product::find()->where(['status' => 1])->all();
        if ($model) {
            $has_data = 1;
            foreach ($model as $value) {
                $onhand_qty = $this->getOnhand($value->id);
                $html .= '<tr>';
                $html .= '<td style="text-align: center">
                            <div class="btn btn-outline-success btn-sm" onclick="addselecteditem($(this))" data-var="' . $value->id . '">เลือก</div>
                            <input type="hidden" class="line-find-item-id" value="' . $value->id . '">
                            <input type="hidden" class="line-find-item-code" value="' . $value->code . '">
                            <input type="hidden" class="line-find-item-name" value="' . $value->name . '">
                           <input type="hidden" class="line-find-onhand-qty" value="' . $onhand_qty . '">
                           </td>';
                $html .= '<td style="text-align: left">' . $value->code . '</td>';
                $html .= '<td style="text-align: left">' . $value->name. '</td>';
                $html .= '<td style="text-align: left">' . $onhand_qty . '</td>';
                $html .= '</tr>';
            }
        }

        if ($has_data == 0) {
            $html .= '<tr>';
            $html .= '<td colspan="5" style="text-align: center;color: red;">ไม่พบข้อมูล</td>';
            $html .= '</tr>';
        }
        echo $html;
    }
    public function actionSavereceive(){
        $purch_id = \Yii::$app->request->post('po_id');
        $line_rec_id = \Yii::$app->request->post('line_receive_id');
        $line_product_id = \Yii::$app->request->post('line_receive_product_id');
        $line_rec_qty = \Yii::$app->request->post('line_receive_qty');
        $line_rec_price = \Yii::$app->request->post('line_receive_price');
        $warehouse_id = \Yii::$app->request->post('line_warehouse_id');

        //print_r($line_rec_id);return;
        if($line_rec_id!=null){
            for($i=0;$i<=count($line_rec_id)-1;$i++){
                if($line_rec_qty[$i] == 0 || $line_rec_qty[$i] == null || $line_rec_qty[$i] == '')continue;
                $model_trans = new \backend\models\Stocktrans();
                $model_trans->journal_no = $model_trans::getReceiveLastNo();
                $model_trans->trans_date = date('Y-m-d H:i:s');
                $model_trans->item_id = $line_product_id[$i];
                $model_trans->qty = (float)$line_rec_qty[$i];
                $model_trans->activity_type_id = 1; // 1 is po receive
                $model_trans->stock_type_id = 1; // 1 = in , 2 = out
                $model_trans->warehouse_id = $warehouse_id[$i];
                $model_trans->trans_ref_id = $purch_id;
                $model_trans->trans_price = (float)$line_rec_price[$i];
                if($model_trans->save(false)){
                    $model_stock = \backend\models\Stocksum::find()->where(['item_id'=>$line_product_id[$i],'warehouse_id'=>$warehouse_id[$i]])->one();
                    if($model_stock){
                        $model_stock->qty = (float)$model_stock->qty + (float)$line_rec_qty[$i];
                        $model_stock->last_update = date('Y-m-d H:i:s');
                        if($model_stock->save(false)){
                            $update_purch_remain = \common\models\PurchLine::find()->where(['id'=>$line_rec_id[$i]])->one();
                            if($update_purch_remain){
                                $update_purch_remain->remain_qty = (float)$update_purch_remain->remain_qty - (float)$line_rec_qty[$i];
                                $update_purch_remain->save(false);
                            }
                        }
                    }else{
                        $model_new = new \backend\models\Stocksum();
                        $model_new->company_id = 1;
                        $model_new->item_id = $line_product_id[$i];
                        $model_new->warehouse_id = $warehouse_id[$i];
                        $model_new->qty = (float)$line_rec_qty[$i];
                        $model_new->last_update = date('Y-m-d H:i:s');
                        if($model_new->save(false)){
                            $update_purch_remain = \common\models\PurchLine::find()->where(['id'=>$line_rec_id[$i]])->one();
                            if($update_purch_remain){
                                $update_purch_remain->remain_qty = (float)$update_purch_remain->remain_qty - (float)$line_rec_qty[$i];
                                $update_purch_remain->save(false);
                            }
                        }
                    }
                }
            }
        }

        return $this->redirect(['purchorder/update','id'=>$purch_id]);
    }
    public function getOnhand($product_id){
        $onhand = 0;

        if($product_id){
            $onhand = \backend\models\Stocksum::find()->where(['item_id'=>$product_id])->sum('qty');
        }
        return $onhand;
    }
}
