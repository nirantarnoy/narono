<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "quotation_rate_history".
 *
 * @property int $id
 * @property int|null $quotation_title_id
 * @property int|null $quoation_rate_id
 * @property float|null $oil_price
 * @property float|null $rate_amount
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class QuotationRateHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quotation_rate_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quotation_title_id', 'quotation_rate_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['oil_price', 'rate_amount'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quotation_title_id' => 'Quotation Title ID',
            'quotation_rate_id' => 'Quotation Rate ID',
            'oil_price' => 'Oil Price',
            'rate_amount' => 'Rate Amount',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
