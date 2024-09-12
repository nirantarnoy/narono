<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "accident".
 *
 * @property int $id
 * @property string|null $case_no
 * @property string|null $trans_date
 * @property string|null $place
 * @property int|null $car_id
 * @property int|null $emp_id
 * @property int|null $accident_title_id
 * @property string|null $accident_title_name
 * @property string|null $description
 * @property int|null $status
 * @property int|null $company_id
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class Accident extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'accident';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trans_date'], 'safe'],
            [['car_id', 'emp_id', 'accident_title_id', 'status', 'company_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['case_no', 'place', 'accident_title_name', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [

            'id' => 'ID',
            'case_no' => 'Case No',
            'trans_date' => 'วันที่เกิดเคส',
            'accident_title_id' => 'หัวข้ออุบัติเหตุ',
            'place' => 'สถานที่',
            'emp_id' => 'พนักงานขับรถ',
            'car_id' => 'รถ',
            'plate_no' => 'ทะเบียน',
            'description' => 'คำอธิบายอุบัติเหตุ',
            'status' => 'สถานะ',
            'accident_title_name' => 'Accident Title Name',
            'company_id' => 'Company ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
