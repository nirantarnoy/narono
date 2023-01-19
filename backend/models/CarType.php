<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cartype".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $status
 * @property int|null $company_id
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class CarType extends \common\models\CarType
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cartype';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'company_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
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
            'name' => 'ประเภท',
            'description' => 'รายละเอียด',
            'status' => 'สถานะ',
            'company_id' => 'บริษัท',
            // 'created_at' => 'Created At',
            // 'created_by' => 'Created By',
            // 'updated_at' => 'Updated At',
            // 'updated_by' => 'Updated By',
        ];
    }

    public static function findName($id)
    {
        $model = CarType::find()->where(['id' => $id])->one();
        return $model != null ? $model->name : '';
    }
}
