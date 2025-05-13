<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "quotation_dropoff".
 *
 * @property int $id
 * @property int|null $quotation_id
 * @property int|null $dropoff_id
 * @property string|null $route_no
 */
class QuotationDropoff extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quotation_dropoff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quotation_rate_id', 'dropoff_id'], 'integer'],
            [['route_no'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quotation_rate_id' => 'Quotation ID',
            'dropoff_id' => 'Dropoff ID',
            'route_no' => 'Route No',
        ];
    }
}
