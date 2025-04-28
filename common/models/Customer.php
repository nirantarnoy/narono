<?php

namespace common\models;

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
class Customer extends \yii\db\ActiveRecord
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
            [['business_type', 'status', 'crated_at', 'created_by', 'updated_at', 'udpated_by','payment_term_id','payment_method_id','work_type_id'], 'integer'],
            [['code', 'name','address','taxid','branch_code','branch_name'], 'string', 'max' => 255],
            [['customer_group_id','is_invoice'],'safe'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'business_type' => 'Business Type',
            'status' => 'Status',
            'crated_at' => 'Crated At',
            'payment_term_id'=>'เงื่อนไขชำระเงิน',
            'payment_method_id'=>'วิธีชำระเงิน',
            'address'=>'ที่อยู่วางบิล',
            'taxid'=>'เลขที่ผู้เสียภาษี',
            'branch_code'=>'รหัสสาขา',
            'branch_name'=>'ชื่อสาขา',
            'work_type_id'=>'ประเภทงาน',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'udpated_by' => 'Udpated By',
            'is_invoice' => 'ลูกค้าวางบิล',
        ];
    }
}
