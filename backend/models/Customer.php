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
            [['business_type', 'status', 'crated_at', 'created_by', 'updated_at', 'udpated_by','customer_group_id','company_id'], 'integer'],
            [['code', 'name','phone','email'], 'string', 'max' => 255],
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
            'name' => 'ชื่อลูกค้า',
            'business_type' => 'Business Type',
            'customer_group_id' => 'กลุ่มลูกค้า',
            'phone' => 'เบอร์ติดต่อ',
            'email' => 'อีเมล',
            'company_id' => 'company',
            'status' => 'สถานะ',
            'crated_at' => 'Crated At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'udpated_by' => 'Udpated By',
        ];
    }
}
