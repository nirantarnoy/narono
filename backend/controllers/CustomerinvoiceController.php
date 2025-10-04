<?php

namespace backend\controllers;

use backend\models\Customerinvoice;
use backend\models\CustomerinvoiceSearch;
use common\models\PreinvoiceLine;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomerinvoiceController implements the CRUD actions for Customerinvoice model.
 */
class CustomerinvoiceController extends Controller
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
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Customerinvoice models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CustomerinvoiceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customerinvoice model.
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
     * Creates a new Customerinvoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Customerinvoice();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $line_order_id = \Yii::$app->request->post('line_order_id');
                $line_text = \Yii::$app->request->post('line_text');
                $line_price = \Yii::$app->request->post('line_price');
                $line_qty = \Yii::$app->request->post('line_qty');
             //   $line_cus_po_id = \Yii::$app->request->post('line_cus_po_id');

                $line_cus_po_id = $this->getPoId($line_order_id); // get po id from preinvoice id

                $invoice_date = date('Y-m-d');
                $invoice_target_date = date('Y-m-d');
                $x = explode('-', $model->invoice_date);
                $x2 = explode('-', $model->invoice_target_date);

                if (count($x) > 1) {
                    $invoice_date = $x[2] . '/' . $x[1] . '/' . $x[0];
                }
                if (count($x2) > 1) {
                    $invoice_target_date = $x2[2] . '/' . $x2[1] . '/' . $x2[0];
                }

                $final_amount = ($model->total_all_amount);
                $total_text = $this->numtothai($final_amount);

                $model->invoice_date = date('Y-m-d', strtotime($invoice_date));
                $model->invoice_target_date = date('Y-m-d',strtotime($invoice_target_date));
                $model->invoice_no = Customerinvoice::getLastNo();
                $model->total_text = $total_text;
               // $model->po_amount = $final_amount;
                //$model->cus_po_id = $line_cus_po_id;
                if ($model->save(false)) {
                    if($line_order_id!=null){
                        $total_invoice_amount = 0;
                        for($i=0;$i<=count($line_order_id)-1;$i++){
                            $total_invoice_amount += ($line_price[$i] * $line_qty[$i]);
                            $modelline = new \common\models\CustomerInvoiceLine();
                            $modelline->invoice_id = $model->id;
                            $modelline->item_work_id = $line_order_id[$i];
                            $modelline->item_name = $line_text[$i];
                            $modelline->price = $line_price[$i];
                            $modelline->qty = $line_qty[$i];
                            $modelline->line_total = ($line_price[$i] * $line_qty[$i]);
                            $modelline->cus_po_id = $line_cus_po_id;
                            if($modelline->save(false)){
                                $model_preinvoiceline = \common\models\PreinvoiceLine::find()->where(['preinvoice_id'=>$line_order_id[$i]])->all();
                                if($model_preinvoiceline){
                                    foreach($model_preinvoiceline as $valuex){
                                        \backend\models\Workqueue::updateAll(['is_invoice'=>1],['id'=>$valuex->work_queue_id]);
                                    }
                                }
                                \backend\models\Preinvoice::updateAll(['status'=>2],['id'=>$line_order_id[$i]]);

                            }
                        }

                        if($total_invoice_amount > 0){
                            $model_customerpo = \backend\models\CustomerPo::find()->where(['id'=>$line_cus_po_id])->one();
                            if($model_customerpo){
                                $model_customerpo->updateBilledAmount();
                            }
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
            'modelline'=> null,
        ]);
    }

    /**
     * Updates an existing Customerinvoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelline = \common\models\CustomerInvoiceLine::find()->where(['invoice_id'=>$id])->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $line_order_id = \Yii::$app->request->post('line_order_id');
            $line_text = \Yii::$app->request->post('line_text');
            $line_price = \Yii::$app->request->post('line_price');
            $line_qty = \Yii::$app->request->post('line_qty');
            $line_edit_id = \Yii::$app->request->post('line_edit_id');

            $invoice_date = date('Y-m-d');
            $invoice_target_date = date('Y-m-d');
            $x = explode('-', $model->invoice_date);
            $x2 = explode('-', $model->invoice_target_date);

            if (count($x) > 1) {
                $invoice_date = $x[2] . '/' . $x[1] . '/' . $x[0];
            }
            if (count($x2) > 1) {
                $invoice_target_date = $x2[2] . '/' . $x2[1] . '/' . $x2[0];
            }

            $final_amount = ($model->total_all_amount);
            $total_text = $this->numtothai($final_amount);

            $model->invoice_date = date('Y-m-d', strtotime($invoice_date));
            $model->invoice_target_date = date('Y-m-d',strtotime($invoice_target_date));
            $model->total_text = $total_text;
            if($model->save(false)){
              if($line_order_id!=null){
                  for($i=0;$i<=count($line_order_id)-1;$i++){
                      $model_check = \common\models\CustomerInvoiceLine::find()->where(['id'=>$line_edit_id[$i]])->one();
                      if($model_check){
                          $model_check->item_name = $line_text[$i];
                          $model_check->price = $line_price[$i];
                          $model_check->qty = $line_qty[$i];
                          $model_check->line_total = ($line_price[$i] * $line_qty[$i]);
                          $model_check->save(false);
                      }else{
                          $modelline = new \common\models\CustomerInvoiceLine();
                          $modelline->invoice_id = $model->id;
                          $modelline->item_work_id = $line_order_id[$i];
                          $modelline->item_name = $line_text[$i];
                          $modelline->price = $line_price[$i];
                          $modelline->qty = $line_qty[$i];
                          $modelline->line_total = ($line_price[$i] * $line_qty[$i]);
                          $modelline->save(false);
                      }
                  }
              }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelline'=> $modelline,
        ]);
    }

    public function getPoId($id){
        $po_id = 0;
        $sql = "SELECT wq.cus_po_id as po_id FROM preinvoice_line pl INNER JOIN work_queue wq ON pl.work_queue_id = wq.id WHERE preinvoice_id =" + $id;
        $query = \Yii::$app->db->createCommand($sql)->queryAll();
        if($query){
            foreach($query as $value){
                $po_id = $value['po_id'];
            }
        }
        return $po_id;
    }

    /**
     * Deletes an existing Customerinvoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        \common\models\CustomerInvoiceLine::deleteAll(['invoice_id'=>$id]);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Customerinvoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Customerinvoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customerinvoice::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionFindworkqueue()
    {
        $customer_id = \Yii::$app->request->post('customer_id');
        $html = '';
        if ($customer_id > 0) {
            $model = \backend\models\Workqueue::find()->where(['is_invoice' => 0])->all();
            // $model = \backend\models\Workqueue::find()->where(['is_invoice' => 0,'customer_id'=>$customer_id])->all();
            if ($model) {
                foreach ($model as $value) {
                    $work_type_name = \backend\models\WorkOptionType::findName($value->work_option_type_id);
                    $html .= '<tr>';
                    $html .= '<td style="text-align: center">
                            <div class="btn btn-outline-success btn-sm" onclick="addselecteditem($(this))" data-var="' . $value->id . '">เลือก</div>
                            <input type="hidden" class="line-find-order-id" value="' . $value->id . '">
                            <input type="hidden" class="line-find-qty" value="' . 1 . '">
                            <input type="hidden" class="line-find-price" value="' . 0 . '">
                            <input type="hidden" class="line-find-work-type-name" value="' . $work_type_name . '">
                           <input type="hidden" class="line-find-order-no" value="' . $value->work_queue_no . '">
                           </td>';
                    $html .= '<td style="text-align: left">' . $value->work_queue_no . '</td>';
                    $html .= '<td style="text-align: left">' . date('d-m-Y', strtotime($value->work_queue_date)) . '</td>';
                    $html .= '<td style="text-align: left">' . $work_type_name . '</td>';
                    $html .= '</tr>';
                }
            }
        }
        echo $html;
    }
    public function actionFindpreinvioce()
    {
        $customer_id = \Yii::$app->request->post('customer_id');
        $html = '';
        if ($customer_id > 0) {
            $model = \backend\models\Preinvoice::find()->where(['status' => 1])->all();
            // $model = \backend\models\Workqueue::find()->where(['is_invoice' => 0,'customer_id'=>$customer_id])->all();
            if ($model) {
                foreach ($model as $value) {
                    $work_type_name = $value->name;
                    $html .= '<tr>';
                    $html .= '<td style="text-align: center">
                            <div class="btn btn-outline-success btn-sm" onclick="addselecteditem($(this))" data-var="' . $value->id . '">เลือก</div>
                            <input type="hidden" class="line-find-order-id" value="' . $value->id . '">
                            <input type="hidden" class="line-find-qty" value="' . 1 . '">
                            <input type="hidden" class="line-find-price" value="' . $value->total_amount . '">
                            <input type="hidden" class="line-find-work-type-name" value="' . $work_type_name . '">
                           <input type="hidden" class="line-find-order-no" value="' . $value->journal_no . '">
                           <input type="hidden" class="line-find-cus-po-id" value="' . $value->journal_no . '">
                           </td>';
                    $html .= '<td style="text-align: left">' . $value->journal_no . '</td>';
                    $html .= '<td style="text-align: left">' . date('d-m-Y', strtotime($value->journal_date)) . '</td>';
                    $html .= '<td style="text-align: left">' . $work_type_name . '</td>';
                    $html .= '</tr>';
                }
            }
        }
        echo $html;
    }

    public function numtothai($num)
    {
        $return = "";
        $num = str_replace(",", "", $num);
        $number = explode(".", $num);
        if (sizeof($number) > 2) {
            return 'รูปแบบข้อมุลไม่ถูกต้อง';
            exit;
        } else if (sizeof($number) == 1) {
            $number[1] = 0;
        }
        // return $number[0];
        $return .= self::numtothaistring($number[0]) . "บาท";

        $stang = intval($number[1]);
        // return $stang;
        if ($stang > 0) {
            if (strlen($stang) == 1) {
                $stang = $stang . '0';
            }
            if ($stang == '10') {
                $return .= 'สิบสตางค์';
            } else if ($stang == '11') {
                $return .= 'สิบเอ็ดสตางค์';
            } else if ($stang == '12') {
                $return .= 'สิบสองสตางค์';
            } else if ($stang == '13') {
                $return .= 'สิบสามสตางค์';
            } else if ($stang == '14') {
                $return .= 'สิบสี่สตางค์';
            } else if ($stang == '15') {
                $return .= 'สิบห้าสตางค์';
            } else if ($stang == '16') {
                $return .= 'สิบหกสตางค์';
            } else if ($stang == '17') {
                $return .= 'สิบเจ็ดสตางค์';
            } else if ($stang == '18') {
                $return .= 'สิบแปดสตางค์';
            } else if ($stang == '19') {
                $return .= 'สิบเก้าสตางค์';
            } else {
                $return .= self::numtothaistring($stang) . "สตางค์";
            }

        } else {
            $return .= "ถ้วน";
        }
        return $return;
    }
    public function numtothaistring($num)
    {
        $return_str = "";
        $txtnum1 = array('', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า');
        $txtnum2 = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');
        $num_arr = str_split($num);
        $count = count($num_arr);
        foreach ($num_arr as $key => $val) {
            // echo $count." ".$val." ".$key."</br>";
            if ($count > 1 && $val == 1 && $key == ($count - 1)) {
                $return_str .= "เอ็ด";
            } else if ($count > 1 && $val == 1 && $key == 2) {
                $return_str .= $txtnum2[$val];
            } else if ($count > 1 && $val == 2 && $key == ($count - 2)) {
                $return_str .= "ยี่" . $txtnum2[$count - $key - 1];
            } else if ($count > 1 && $val == 0) {
            } else {
                $return_str .= $txtnum1[$val] . $txtnum2[$count - $key - 1];
            }
        }
        return $return_str;
    }
}
