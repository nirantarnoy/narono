<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "query_quotation_price_per_ton".
 *
 * @property int $id
 * @property int|null $car_type_id
 * @property int|null $price_type_id
 * @property int|null $dropoff_id
 * @property float|null $price_current_rate
 */
class QueryQuotationPricePerTon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'query_quotation_price_per_ton';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'car_type_id', 'price_type_id', 'dropoff_id'], 'integer'],
            [['price_current_rate'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_type_id' => 'Car Type ID',
            'price_type_id' => 'Price Type ID',
            'dropoff_id' => 'Dropoff ID',
            'price_current_rate' => 'Price Current Rate',
        ];
    }
}
