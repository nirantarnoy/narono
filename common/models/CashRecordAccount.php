<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cash_record_account".
 *
 * @property int $id
 * @property int|null $cash_record_id
 * @property string|null $account_name
 * @property string|null $account_code
 * @property float|null $debit
 * @property float|null $credit
 */
class CashRecordAccount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cash_record_account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cash_record_id'], 'integer'],
            [['debit', 'credit'], 'number'],
            [['account_name', 'account_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cash_record_id' => 'Cash Record ID',
            'account_name' => 'ชื่อบัญชี',
            'account_code' => 'รหัสบัญชี',
            'debit' => 'เดบิต',
            'credit' => 'เครดิต',
        ];
    }
}
