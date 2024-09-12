<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "complain".
 *
 * @property int $id
 * @property string|null $case_no
 * @property string|null $trans_date
 * @property int|null $complain_title_id
 * @property string|null $place
 * @property int|null $emp_id
 * @property int|null $car_id
 * @property string|null $plate_no
 * @property string|null $description
 * @property int|null $status
 * @property int|null $company_id
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class Complain extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'complain';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trans_date'], 'safe'],
            [['complain_title_id', 'emp_id', 'car_id', 'status', 'company_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['case_no', 'place', 'plate_no', 'description'], 'string', 'max' => 255],
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
            'complain_title_id' => 'หัวข้อคอมเพลน',
            'place' => 'สถานที่',
            'emp_id' => 'พนักงานขับรถ',
            'car_id' => 'รถ',
            'plate_no' => 'ทะเบียน',
            'description' => 'คำอธิบายคอมเพลน',
            'status' => 'สถานะ',
            'company_id' => 'บริษัท',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
