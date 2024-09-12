<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "alert".
 *
 * @property int $id
 * @property string|null $trans_date
 * @property int|null $alert_month
 * @property int|null $normal_alert
 * @property int|null $city_alert
 * @property int|null $four_hour_alert
 * @property int|null $ten_hour_alert
 * @property int|null $status
 * @property int|null $company_id
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class Alert extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'alert';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trans_date'], 'safe'],
            [['alert_month', 'normal_alert', 'city_alert', 'four_hour_alert', 'ten_hour_alert', 'status', 'company_id', 'created_at', 'created_by', 'updated_at', 'updated_by','emp_id','car_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'trans_date' => 'วันที่',
            'alert_month' => 'เดือน',
            'normal_alert' => 'เส้นทางปกติ',
            'city_alert' => 'เส้นทางชุมชน',
            'four_hour_alert' => 'Alert 4 ชั่วโมง',
            'ten_hour_alert' => 'Alert 10 ชั่วโมง',
            'status' => 'สถานะ',
            'emp_id'=>'พนักงาน',
            'car_id'=>'รถ',
            'company_id' => 'Company ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
