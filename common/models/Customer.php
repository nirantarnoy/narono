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
            [['business_type', 'status', 'crated_at', 'created_by', 'updated_at', 'udpated_by'], 'integer'],
            [['code', 'name'], 'string', 'max' => 255],
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
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'udpated_by' => 'Udpated By',
        ];
    }
}
