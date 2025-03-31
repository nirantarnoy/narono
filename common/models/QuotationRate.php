<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "quotation_rate".
 *
 * @property int $id
 * @property int|null $zone_id
 * @property int|null $province_id
 * @property string|null $route_code
 * @property int|null $car_type_id
 * @property float|null $distance
 * @property float|null $load_qty
 * @property float|null $price_current_rate
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_a
 * @property int|null $updated_by
 * @property int|null $quotation_title_id
 */
class QuotationRate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quotation_rate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['zone_id', 'province_id', 'car_type_id', 'created_at', 'created_by', 'updated_a', 'updated_by', 'quotation_title_id','price_type_id'], 'integer'],
            [['distance', 'load_qty', 'price_current_rate'], 'number'],
            [['route_code'], 'string', 'max' => 255],
            [['drop_off_id'],'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'zone_id' => 'Zone ID',
            'province_id' => 'Province ID',
            'route_code' => 'Route Code',
            'car_type_id' => 'Car Type ID',
            'distance' => 'Distance',
            'load_qty' => 'Load Qty',
            'price_current_rate' => 'Price Current Rate',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_a' => 'Updated A',
            'updated_by' => 'Updated By',
            'quotation_title_id' => 'Quotation Title ID',
            'drop_off_id'=>'Drop Off',
        ];
    }
}
