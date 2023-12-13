<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cash_record".
 *
 * @property int $id
 * @property string|null $journal_no
 * @property string|null $trans_date
 * @property int|null $car_id
 * @property int|null $car_tail_id
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $create_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class CashRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cash_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trans_date','approve_date','recieve_date'], 'safe'],
            [['car_id', 'car_tail_id', 'status', 'created_at', 'create_by', 'updated_at', 'updated_by','trans_ref_id','payment_method_id','approve_by','cashier_by','recieve_by'], 'integer'],
            [['journal_no'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'journal_no' => 'เลขที่',
            'trans_date' => 'วันที่',
            'car_id' => 'รถ',
            'car_tail_id' => 'ส่วนพ่วง',
            'status' => 'สถานะ',
            'created_at' => 'Created At',
            'trans_ref_id' =>'เลขที่อ้างอิง',
            'create_by' =>'พนักงาน',
            'payment_method_id' =>'วิธีจ่ายเงิน',
            'approve_by' =>'ผู้อนุมัติ',
            'cashier_by' =>'ผู้จ่ายเงิน',
            'approve_date' =>'วันที่อนุมัติ',
            'cashier_date' =>'วันที่จ่ายเงิน',
            'recieve_by' =>'ผู้รับเงิน',
            'recieve_date' =>'วันที่รับเงิน',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}