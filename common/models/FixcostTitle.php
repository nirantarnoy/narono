<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fixcost_title".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $created_by
 */
class FixcostTitle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fixcost_title';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'created_by','type_id', 'account_id'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ชื่อ',
            'description' => 'รายละเอียด',
            'type_id' => 'ประเภทรับจ่าย',
            'account_id' => 'บัญชีแยกประเภท',
            'status' => 'สถานะ',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }
}
