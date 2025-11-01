<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dropoff_place".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class DropoffPlace extends \common\models\DropoffPlace
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dropoff_place';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'created_by', 'updated_at', 'updated_by','hp','car_type_id'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
            [['oil_rate_qty'],'safe']
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
            'status' => 'สถานะ',
            'car_type_id'=>'ประเภทรถ',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public static function getHp($id)
    {
        $model = DropoffPlace::find()->where(['id' => $id])->one();
        return $model != null ? $model->hp : '';
    }
    public static function getOilrate($id)
    {
        $model = DropoffPlace::find()->where(['id' => $id])->one();
        return $model != null ? $model->oil_rate_qty : '';
    }
    public static function getOilrate1($id)
    {
        $model = DropoffPlace::find()->where(['id' => $id])->one();
        return $model != null ? $model->oil_rate_qty_1 : '';
    }
    public static function getCartypeId($id)
    {
        $model = DropoffPlace::find()->where(['id' => $id])->one();
        return $model != null ? $model->car_type_id : 0;
    }


    public static function getInfo($id)
    {
        $data = [];
        $model = DropoffPlace::find()->where(['id' => $id])->one();
        if ($model) {
            array_push($data, ['hp' => $model->hp, 'oil_rate_qty' => $model->oil_rate_qty]);
        }
        return $data;
    }

    public static function findName($id){
        $model = DropoffPlace::find()->where(['id' => $id])->one();
        return $model != null ? $model->name : '';
    }
}
