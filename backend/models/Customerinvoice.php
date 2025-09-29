<?php
namespace backend\models;
use Yii;
use yii\db\ActiveRecord;
use common\models\Customer;
use backend\models\CustomerPo;
use backend\models\CustomerPoInvoice;


date_default_timezone_set('Asia/Bangkok');

class Customerinvoice extends \common\models\CustomerInvoice
{
    public function behaviors()
    {
        return [
//            'timestampcdate'=>[
//                'class'=> \yii\behaviors\AttributeBehavior::className(),
//                'attributes'=>[
//                    ActiveRecord::EVENT_BEFORE_INSERT=>'created_at',
//                ],
//                'value'=> time(),
//            ],
//            'timestampudate'=>[
//                'class'=> \yii\behaviors\AttributeBehavior::className(),
//                'attributes'=>[
//                    ActiveRecord::EVENT_BEFORE_INSERT=>'updated_at',
//                ],
//                'value'=> time(),
//            ],
////            'timestampcby'=>[
////                'class'=> \yii\behaviors\AttributeBehavior::className(),
////                'attributes'=>[
////                    ActiveRecord::EVENT_BEFORE_INSERT=>'created_by',
////                ],
////                'value'=> Yii::$app->user->identity->id,
////            ],
////            'timestamuby'=>[
////                'class'=> \yii\behaviors\AttributeBehavior::className(),
////                'attributes'=>[
////                    ActiveRecord::EVENT_BEFORE_UPDATE=>'updated_by',
////                ],
////                'value'=> Yii::$app->user->identity->id,
////            ],
//            'timestampupdate'=>[
//                'class'=> \yii\behaviors\AttributeBehavior::className(),
//                'attributes'=>[
//                    ActiveRecord::EVENT_BEFORE_UPDATE=>'updated_at',
//                ],
//                'value'=> time(),
//            ],
        ];
    }

//    public function findUnitname($id){
//        $model = Unit::find()->where(['id'=>$id])->one();
//        return count($model)>0?$model->name:'';
//    }
    public static function findInvoiceNo($id){
        $model = Customerinvoice::find()->where(['id'=>$id])->one();
        return $model!= null?$model->invoice_no:'';
    }
//    public function findUnitid($code){
//        $model = Unit::find()->where(['name'=>$code])->one();
//        return count($model)>0?$model->id:0;
//    }

    public static function getLastNo()
    {
        //   $model = Orders::find()->MAX('order_no');
        $model = Customerinvoice::find()->MAX('invoice_no');

        $pre = "IV";

        if ($model != null) {
//            $prefix = $pre.substr(date("Y"),2,2);
//            $cnum = substr((string)$model,4,strlen($model));
//            $len = strlen($cnum);
//            $clen = strlen($cnum + 1);
//            $loop = $len - $clen;
            $prefix = $pre . '-' . substr(date("Y"), 2, 2);
            $cnum = substr((string)$model, 5, strlen($model));
            $len = strlen($cnum);
            $clen = strlen($cnum + 1);
            $loop = $len - $clen;
            for ($i = 1; $i <= $loop; $i++) {
                $prefix .= "0";
            }
            $prefix .= $cnum + 1;
            return $prefix;
        } else {
            $prefix = $pre . '-' . substr(date("Y"), 2, 2);
            return $prefix . '00001';
        }
    }

    /**
     * Relations
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    public function getPoInvoices()
    {
        return $this->hasMany(CustomerPoInvoice::class, ['invoice_id' => 'id']);
    }

    public function getCustomerPos()
    {
        return $this->hasMany(CustomerPo::class, ['id' => 'po_id'])
            ->via('poInvoices');
    }

}
