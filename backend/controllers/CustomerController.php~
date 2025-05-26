<?php

namespace backend\controllers;

use backend\models\Customer;
use backend\models\CustomerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
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
     * Lists all Customer models.
     *
     * @return string
     */
    public function actionIndex()
    {

        $pageSize = \Yii::$app->request->post("perpage");

        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->pagination->pageSize = $pageSize;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'perpage' => $pageSize,
        ]);
    }

    /**
     * Displays a single Customer model.
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
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Customer();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $party_type = 2;
                $address = \Yii::$app->request->post('cus_address');
                $street = \Yii::$app->request->post('cus_street');
                $district_id = \Yii::$app->request->post('district_id');
                $city_id = \Yii::$app->request->post('city_id');
                $province_id = \Yii::$app->request->post('province_id');
                $zipcode = \Yii::$app->request->post('zipcode');

                $line_contact_name = \Yii::$app->request->post('line_name');
                $line_type_id = \Yii::$app->request->post('line_type_id');
                $line_contact_no = \Yii::$app->request->post('line_contact_no');


                $customer_payment_tax_id = \Yii::$app->request->post('customer_tax_id');
                $customer_payment_tax_branch = \Yii::$app->request->post('customer_tax_branch');
                $customer_payment_tax_email = \Yii::$app->request->post('customer_tax_email');

//                print_r($line_contact_name);return ;

                if ($model->save(false)) {
                    if ($party_type) {
//                        echo $address;
//                        echo $zipcode; return ;
                        $address_chk = \common\models\AddressInfo::find()->where(['party_id' => $model->id, 'party_type' => $party_type])->one();
                        if ($address_chk) {
                            $address_chk->address = $address;
                            $address_chk->street = $street;
                            $address_chk->district_id = $district_id;
                            $address_chk->city_id = $city_id;
                            $address_chk->province_id = $province_id;
                            $address_chk->zipcode = $zipcode;
                            $address_chk->status = 1;
                            if ($address_chk->save()) {

                            }
                        } else {
                            $cus_address = new \common\models\AddressInfo();
                            $cus_address->party_type = $party_type;
                            $cus_address->party_id = $model->id;
                            $cus_address->address = $address;
                            $cus_address->street = $street;
                            $cus_address->district_id = $district_id;
                            $cus_address->city_id = $city_id;
                            $cus_address->province_id = $province_id;
                            $cus_address->zipcode = $zipcode;
                            $cus_address->status = 1;
                            if ($cus_address->save()) {

                            }
                        }
                    }
                    if (count($line_contact_name)) {
//                        echo count($line_contact_name);return ;
                        for ($i = 0; $i <= count($line_contact_name) - 1; $i++) {
//                            print_r($line_contact_name);return ;
                            if ($line_contact_name[$i]) {
//                              print_r($line_contact_name);return ;
                                $contact_chk = \common\models\ContactInfo::find()->where(['party_id' => $model->id, 'contact_name' => $line_contact_name[$i]])->one();
                                if ($contact_chk) {
//                                    $contact_chk->type_id = $line_type_id[$i];
                                    $contact_chk->contact_name = trim($line_contact_name[$i]);
                                    $contact_chk->type_id = $line_type_id[$i];
                                    $contact_chk->contact_no = trim($line_contact_no[$i]);
                                    if ($contact_chk->save(false)) {

                                    }
                                } else {
                                    $new_contact = new \common\models\ContactInfo();
                                    $new_contact->party_type = $party_type;
                                    $new_contact->party_id = $model->id;
                                    $new_contact->type_id = $line_type_id[$i];
                                    $new_contact->contact_name = trim($line_contact_name[$i]);
                                    $new_contact->contact_no = trim($line_contact_no[$i]);
                                    if ($new_contact->save(false)) {

                                    }
                                }
                            }
//                            print_r($line_contact_name);return ;

                        }

                    }

                    $model_tax = new \common\models\CustomerInvoiceInfo();
                    $model_tax->customer_id = $model->id;
                    $model_tax->tax_id = $customer_payment_tax_id;
                    $model_tax->branch = $customer_payment_tax_branch;
                    $model_tax->email = $customer_payment_tax_email;
                    $model_tax->status = 1;
                    $model_tax->save(false);


                    if ($model->customer_group_id) {
                        for ($m = 0; $m <= count($model->customer_group_id) - 1; $m++) {
                            $model_group_assign = new \common\models\CustomerAssignList();
                            $model_group_assign->customer_id = $model->id;
                            $model_group_assign->group_id = $model->customer_group_id[$m];
                            $model_group_assign->save(false);
                        }
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model_line = \common\models\AddressInfo::find()->where(['party_id' => $id])->all();

        $model_contact_line = \common\models\ContactInfo::find()->where(['party_id' => $id])->all();

        $model_customer_tax_info = \common\models\CustomerInvoiceInfo::find()->where(['customer_id' => $id])->one();

        $model_user_group_list = \common\models\CustomerAssignList::find()->where(['customer_id'=>$id])->all();

        $model_customer_invoice_child = \common\models\CustomerInvoiceChild::find()->where(['customer_id' => $id])->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $party_type = 2;
            $address = \Yii::$app->request->post('cus_address');
            $street = \Yii::$app->request->post('cus_street');
            $district_id = \Yii::$app->request->post('district_id');
            $city_id = \Yii::$app->request->post('city_id');
            $province_id = \Yii::$app->request->post('province_id');
            $zipcode = \Yii::$app->request->post('zipcode');

            $line_contact_name = \Yii::$app->request->post('line_name');
            $line_type_id = \Yii::$app->request->post('line_type_id');
            $line_contact_no = \Yii::$app->request->post('line_contact_no');

            $removelist = \Yii::$app->request->post('remove_list');
            $rec_id = \Yii::$app->request->post('rec_id');

            $customer_payment_tax_id = \Yii::$app->request->post('customer_tax_id');
            $customer_payment_tax_branch = \Yii::$app->request->post('customer_tax_branch');
            $customer_payment_tax_email = \Yii::$app->request->post('customer_tax_email');


            // customer invoice

            $line_customer_id = \Yii::$app->request->post('line_customer_id');
            $line_customer_name = \Yii::$app->request->post('line_customer_name');
            $remove_line_customer = \Yii::$app->request->post('remove_customer_list');

            // print_r($model->customer_group_id);return;

//            print_r($removelist); return;
            if ($model->save(false)) {
                if ($party_type) {
//                    echo 'dd'; return
                    $address_chk = \common\models\AddressInfo::find()->where(['party_id' => $model->id, 'party_type' => $party_type])->one();
//                    echo 'dd'; return;
                    if ($address_chk) {
                        $address_chk->party_type = $party_type;
                        $address_chk->address = $address;
                        $address_chk->street = $street;
                        $address_chk->district_id = $district_id;
                        $address_chk->city_id = $city_id;
                        $address_chk->province_id = $province_id;
                        $address_chk->zipcode = $zipcode;
                        $address_chk->status = 1;
                        if ($address_chk->save(false)) {

                        }
                    } else {
                        $new_address = new \common\models\AddressInfo();
                        $new_address->party_type = $party_type;
                        $new_address->party_id = $model->id;
                        $new_address->address = $address;
                        $new_address->street = $street;
                        $new_address->district_id = $district_id;
                        $new_address->city_id = $city_id;
                        $new_address->province_id = $province_id;
                        $new_address->zipcode = $zipcode;
                        $new_address->status = 1;
                        if ($new_address->save(false)) {

                        }
                    }
                }
                if (count($line_contact_name)) {
                    for ($i = 0; $i <= count($line_contact_name) - 1; $i++) {
                        //print_r($line_contact_name);return ;
                        if ($line_contact_name[$i]) {
                            //print_r($line_contact_name);return ;
                            $contact_chk = \common\models\ContactInfo::find()->where(['party_id' => $model->id, 'contact_name' => trim($line_contact_name[$i]), 'party_type' => $party_type])->one();
//                            print_r($contact_chk);return ;
                            if ($contact_chk) {
                                $contact_chk->contact_name = trim($line_contact_name[$i]);
                                $contact_chk->type_id = $line_type_id[$i];
                                $contact_chk->contact_no = trim($line_contact_no[$i]);
                                if ($contact_chk->save(false)) {

                                }
                            } else {
                                $new_contact = new \common\models\ContactInfo();
                                $new_contact->party_type = $party_type;
                                $new_contact->party_id = $model->id;
                                $new_contact->type_id = $line_type_id[$i];
                                $new_contact->contact_name = trim($line_contact_name[$i]);
                                $new_contact->contact_no = trim($line_contact_no[$i]);
                                if ($new_contact->save(false)) {

                                }
                            }

                            $delete_rec = explode(",", $removelist);
                            if (count($delete_rec)) {
                                \common\models\ContactInfo::deleteAll(['party_id' => $model->id, 'id' => $delete_rec]);
                            }
                        }
                        //                            print_r($line_contact_name);return ;

                    }
                }
                $model_tax_check = \common\models\CustomerInvoiceInfo::find()->where(['customer_id' => $model->id])->one();
                if ($model_tax_check) {
                    $model_tax_check->tax_id = $customer_payment_tax_id;
                    $model_tax_check->branch = $customer_payment_tax_branch;
                    $model_tax_check->email = $customer_payment_tax_email;
                    $model_tax_check->save(false);
                } else {
                    $model_tax = new \common\models\CustomerInvoiceInfo();
                    $model_tax->customer_id = $model->id;
                    $model_tax->tax_id = $customer_payment_tax_id;
                    $model_tax->branch = $customer_payment_tax_branch;
                    $model_tax->email = $customer_payment_tax_email;
                    $model_tax->status = 1;
                    $model_tax->save(false);
                }

                if ($model->customer_group_id) {
                    \common\models\CustomerAssignList::deleteAll(['customer_id' => $model->id]);
                    for ($m = 0; $m <= count($model->customer_group_id) - 1; $m++) {
                        $model_group_assign = new \common\models\CustomerAssignList();
                        $model_group_assign->customer_id = $model->id;
                        $model_group_assign->group_id = $model->customer_group_id[$m];
                        $model_group_assign->save(false);
                    }
                }

                if ($remove_line_customer != null) {
                    $xplode = explode(",", $remove_line_customer);
                    if (count($xplode) > 0) {
                        \common\models\CustomerInvoiceChild::deleteAll(['id' => $xplode]);
                    }
                }
                if($line_customer_id!=null){
                    if(count($line_customer_id)>0){
                        print_r($line_customer_id);return;
                        \common\models\CustomerInvoiceChild::deleteAll(['customer_id'=>$model->id]);
                        for ($m = 0; $m <= count($line_customer_id) - 1; $m++) {
                            if($line_customer_id[$m]==0 || $line_customer_id[$m]=='')continue;
                            $model_customer_invoice_child_new = new \common\models\CustomerInvoiceChild();
                            $model_customer_invoice_child_new->customer_id = $model->id;
                            $model_customer_invoice_child_new->customer_child_id = $line_customer_id[$m];
                            $model_customer_invoice_child_new->save(false);
                        }
                    }
                }


            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'model_line' => $model_line,
            'model_contact_line' => $model_contact_line,
            'model_customer_tax_info' => $model_customer_tax_info,
            'model_user_group_list' => $model_user_group_list,
            'model_customer_invoice_child' => $model_customer_invoice_child
        ]);
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionDelete($id)
    {
        $model_address = \common\models\AddressInfo::find()->where(['party_id' => $id])->all();
        $model_contact = \common\models\ContactInfo::find()->where(['party_id' => $id])->all();
        if ($model_address) {
            if (\common\models\AddressInfo::deleteAll(['party_id' => $id])) {
//                $this->findModel($id)->delete();
            }
        }
        if ($model_contact) {
            if (\common\models\ContactInfo::deleteAll(['party_id' => $id])) {
//                $this->findModel($id)->delete();
            }
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = Customer::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public
    function actionShowcity($id)
    {
        $model = \common\models\Amphur::find()->where(['PROVINCE_ID' => $id])->all();

        if (count($model) > 0) {
            echo "<option>--- เลือกอำเภอ ---</option>";
            foreach ($model as $value) {

                echo "<option value='" . $value->AMPHUR_ID . "'>$value->AMPHUR_NAME</option>";

            }
        } else {
            echo "<option>-</option>";
        }
    }

    public
    function actionShowdistrict($id)
    {
        $model = \common\models\District::find()->where(['AMPHUR_ID' => $id])->all();

        if (count($model) > 0) {
            foreach ($model as $value) {

                echo "<option value='" . $value->DISTRICT_ID . "'>$value->DISTRICT_NAME</option>";

            }
        } else {
            echo "<option>-</option>";
        }
    }

    public function actionShowzipcode($id)
    {
        $model = \common\models\Amphur::find()->where(['AMPHUR_ID' => $id])->one();
//        echo $id;
        if ($model) {
            echo $model->POSTCODE;
//            echo '1110';
        } else {
            echo "";
        }
//        echo '111';
    }

    public
    function actionShowaddress($id)
    {
        $model = \common\models\AddressInfo::find()->where(['party_type' => $id])->all();

        if (count($model) > 0) {
            foreach ($model as $value) {

                echo "<option value='" . $value->DISTRICT_ID . "'>$value->DISTRICT_NAME</option>";

            }
        } else {
            echo "<option>-</option>";
        }
    }

    function actionFindcustomerinvoice(){
     $html = '';
     $model = \common\models\Customer::find()->where(['status' => 1,'is_invoice'=>1])->all();
     if($model){
         foreach ($model as $value) {
             $html .= '<tr>';
             $html .= '<td style="text-align: center">
                            <div class="btn btn-outline-success btn-sm" onclick="addselecteditem($(this))" data-var="' . $value->id . '">เลือก</div>
                            <input type="hidden" class="line-find-customer-id" value="' . $value->id . '">                    
                            <input type="hidden" class="line-find-customer-name" value="' . $value->name . '">                    
                           </td>';
             $html .= '<td style="text-align: left">' . $value->name . '</td>';
             $html .= '</tr>';
         }
     }
     echo $html;
    }

    function actionGetcustomerinvoice(){
        $name = '';
        $id = \Yii::$app->request->post('customer_id');
        if($id){
            $model = \common\models\Customer::find()->where(['id'=>$id])->one();
            if($model){
                $name = \backend\models\Customer::findCustomerInvoiceName($model->id);
            }
        }
        echo $name;
    }

    function actionTransfercustomerinvoice(){
        $model = \common\models\Customer::find()->all();
        if($model){
            foreach($model as $value){
                if($value->customer_invoice_id){
                    $model_new = new \common\models\CustomerInvoiceChild();
                    $model_new->customer_id = $value->id;
                    $model_new->customer_child_id = $value->customer_invoice_id;
                    $model_new->status = 1;
                    $model_new->save(false);
                }
            }
        }
    }

}
