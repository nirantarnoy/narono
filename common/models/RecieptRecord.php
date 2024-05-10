<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reciept_record".
 *
 * @property int $id
 * @property string|null $journal_no
 * @property string|null $trans_date
 * @property int|null $status
 * @property int|null $create_at
 * @property int|null $created_by
 */
class RecieptRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reciept_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trans_date'], 'safe'],
            [['status', 'create_at', 'created_by','emp_id','company_id','office_id'], 'integer'],
            [['journal_no'], 'string', 'max' => 255],
            [['trans_ref_id'],'safe'],
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
            'emp_id' => 'อ้างถึง',
            'trans_ref_id'=> 'รายการอ้างอิง',
            'company_id' => 'บริษัท',
            'office_id' => 'สำนักงาน',
            'status' => 'สถานะ',
            'create_at' => 'Create At',
            'created_by' => 'พนักงาน',
        ];
    }
}
