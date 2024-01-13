<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property int|null $business_type
 * @property int|null $status
 * @property int|null $crated_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $udpated_by
 */
class Customer extends \common\models\Customer
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_type', 'status', 'crated_at', 'created_by', 'updated_at', 'udpated_by','customer_group_id','company_id','payment_term_id','payment_method_id','work_type_id'], 'integer'],
            [['code', 'name','phone','email'], 'string', 'max' => 255],
            [['address','taxid','branch_code','branch_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'รหัส',
            'name' => 'ชื่อลูกค้า',
            'business_type' => 'Business Type',
            'customer_group_id' => 'กลุ่มลูกค้า',
            'phone' => 'เบอร์ติดต่อ',
            'email' => 'อีเมล',
            'company_id' => 'บริษัท',
            'status' => 'สถานะ',
            'payment_term_id'=>'เงื่อนไขชำระเงิน',
            'payment_method_id'=>'วิธีชำระเงิน',
            'work_type_id'=>'ประเภทงาน',
            'address'=>'ที่อยู่วางบิล',
            'taxid'=>'เลขที่ผู้เสียภาษี',
            'branch_code'=>'รหัสสาขา',
            'branch_name'=>'ชื่อสาขา',
            'crated_at' => 'สร้างเมื่อ',
            'created_by' => 'สร้างโดย',
            'updated_at' => 'แก้ไขเมื่อ',
            'udpated_by' => 'แก้ไขโดย',
        ];
    }

    public static function findCusName($id)
    {
        $model = Customer::find()->where(['id' => $id])->one();
        return $model != null ? $model->name : '';
    }
    public static function findAddress($id)
    {
        $model = Customer::find()->where(['id' => $id])->one();
        return $model != null ? $model->address : '';
    }
    public static function findAddressProvinceId($id)
    {
        $model = AddressInfo::find()->where(['party_id' => $id,'party_type'=>2])->one();
        return $model != null ? $model->province_id : 0;
    }
    public static function findTaxId($id)
    {
        $model = Customer::find()->where(['id' => $id])->one();
        return $model != null ? $model->taxid : '';
    }
    public static function findWorkTypeByCustomerid($id)
    {
        $model = Customer::find()->where(['id' => $id])->one();
        if($model){
            $model_type = \common\models\WorkOptionType::find()->where(['id'=>$model->work_type_id])->one();
            if($model_type){
                return  $model_type->name;
            }
        }
        return '';
    }
    public static function findWorkTypeIdByCustomer($id)
    {
        $type_id = 0;
        $model = Customer::find()->where(['id' => $id])->one();
        if($model){
            $model_type = \common\models\WorkOptionType::find()->where(['id'=>$model->work_type_id])->one();
            if($model_type){
                $type_id = $model_type->id;
            }
        }
        return $type_id;
    }
    public static function findCusProvinceId($id)
    {
        $model = Customer::find()->where(['id' => $id])->one();
        return $model != null ? $model->name : '';
    }
}
