<?php

namespace backend\models;

use common\models\WorkQueueDropoff;
use Yii;
use yii\db\ActiveRecord;

date_default_timezone_set('Asia/Bangkok');

class Workqueue extends \common\models\WorkQueue
{
    public function behaviors()
    {
        return [
            'timestampcdate' => [
                'class' => \yii\behaviors\AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'create_at',
                ],
                'value' => time(),
            ],
            'timestampudate' => [
                'class' => \yii\behaviors\AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'updated_at',
                ],
                'value' => time(),
            ],
            'timestampcby' => [
                'class' => \yii\behaviors\AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_by',
                ],
                'value' => Yii::$app->user->id,
            ],
            'timestamuby' => [
                'class' => \yii\behaviors\AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_by',
                ],
                'value' => Yii::$app->user->id,
            ],
//            'timestampcompany' => [
//                'class' => \yii\behaviors\AttributeBehavior::className(),
//                'attributes' => [
//                    ActiveRecord::EVENT_BEFORE_INSERT => 'company_id',
//                ],
//                'value' => isset($_SESSION['user_company_id']) ? $_SESSION['user_company_id'] : 1,
//            ],
//            'timestampbranch' => [
//                'class' => \yii\behaviors\AttributeBehavior::className(),
//                'attributes' => [
//                    ActiveRecord::EVENT_BEFORE_INSERT => 'branch_id',
//                ],
//                'value' => isset($_SESSION['user_branch_id']) ? $_SESSION['user_branch_id'] : 1,
//            ],
            'timestampupdate' => [
                'class' => \yii\behaviors\AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => time(),
            ],
        ];
    }

    public static function findNo($id){
        $model = Workqueue::find()->where(['id'=>$id])->one();
        return $model != null ?$model->work_queue_no:'';
    }
    public function findWorkType($id){
        $model = Workqueue::find()->where(['id'=>$id])->one();
        if($model){
            $model_type = \common\models\WorkOptionType::find()->where(['id'=>$model->work_option_type_id])->one();
            if($model_type){
                return  $model_type->name;
            }
        }
        return '';
    }
//    public static function findName($id){
//        $model = \common\models\RoutePlan::find()->where(['id'=>$id])->one();
//        return $model!= null?$model->name:'';
//    }
//    public function findUnitid($code){
//        $model = Unit::find()->where(['name'=>$code])->one();
//        return count($model)>0?$model->id:0;
//    }


    public static function getLastNo()
    {
        //   $model = Orders::find()->MAX('order_no');
        $model = Workqueue::find()->MAX('work_queue_no');

        $pre = "WQ";

        if ($model != null) {
//            $prefix = $pre.substr(date("Y"),2,2);
//            $cnum = substr((string)$model,4,strlen($model));
//            $len = strlen($cnum);
//            $clen = strlen($cnum + 1);
//            $loop = $len - $clen;
            $prefix = $pre . '-' . substr(date("Y"), 2, 2);
          //  $prefix = $pre . '-' . substr(date("Y"), 2, 2). substr(date("m"), 0, 2);
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
       //     return $prefix . '00001';
            return $prefix . '00001';
        }
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    public function getWorkQueueDropoff(){
        return $this->hasMany(WorkqueueDropoff::className(), ['work_queue_id' => 'id']);
    }
}
