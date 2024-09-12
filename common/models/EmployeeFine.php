<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "employee_fine".
 *
 * @property int $id
 * @property string|null $case_no
 * @property string|null $trans_date
 * @property string|null $place
 * @property string|null $cause_description
 * @property int|null $company_id
 * @property int|null $car_id
 * @property int|null $emp_id
 * @property int|null $customer_id
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class EmployeeFine extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_fine';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trans_date'], 'safe'],
            [['company_id', 'car_id', 'emp_id', 'customer_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['case_no', 'place', 'cause_description'], 'string', 'max' => 255],
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
            'trans_date' => 'วันที่ปรับ',
            'place' => 'สถานที่',
            'cause_description' => 'คำอธิบายการเสียค่าปรับ',
            'company_id' => 'บริษัท',
            'car_id' => 'ทะเบียน',
            'emp_id' => 'พนักงานขับรถ',
            'customer_id' => 'ลูกค้า',
            'status' => 'สถานะ',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
