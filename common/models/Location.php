<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "location".
 *
 * @property int $id
 * @property int|null $company_id
 * @property string|null $name
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $created_by
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'status', 'created_at', 'created_by','updated_at','updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'บริษัท',
            'name' => 'ชื่อสำนักงาน',
            'status' => 'สถานะ',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
