<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "query_car_work_summary".
 *
 * @property int $id
 * @property string|null $des_name
 * @property int|null $des_province_id
 * @property float|null $total_distanct
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $customer_id
 * @property int|null $hp
 * @property float|null $oil_rate_qty
 * @property int|null $item_back_id
 * @property int|null $company_id
 * @property int|null $dropoff_place_id
 * @property float|null $dropoff_qty
 * @property string|null $dropoff_name
 * @property float|null $labour_price
 * @property float|null $express_road_price
 * @property float|null $other_price
 * @property float|null $work_labour_price
 * @property float|null $work_express_road_price
 * @property float|null $work_other_price
 * @property float|null $test_price
 * @property float|null $damaged_price
 * @property float|null $deduct_other_price
 * @property float|null $other_amt
 * @property float|null $cover_sheet_price
 * @property float|null $overnight_price
 * @property float|null $warehouse_plus_price
 * @property float|null $work_double_price
 * @property float|null $towing_price
 * @property float|null $trail_labour_price
 * @property int|null $car_type_id
 * @property string|null $car_type_name
 */
class QueryCarWorkSummary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'query_car_work_summary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'des_province_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'customer_id', 'hp', 'item_back_id', 'company_id', 'dropoff_place_id', 'car_type_id'], 'integer'],
            [['total_distanct', 'oil_rate_qty', 'dropoff_qty', 'labour_price', 'express_road_price', 'other_price', 'work_labour_price', 'work_express_road_price', 'work_other_price', 'test_price', 'damaged_price', 'deduct_other_price', 'other_amt', 'cover_sheet_price', 'overnight_price', 'warehouse_plus_price', 'work_double_price', 'towing_price', 'trail_labour_price'], 'number'],
            [['des_name', 'dropoff_name', 'car_type_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'des_name' => 'Des Name',
            'des_province_id' => 'Des Province ID',
            'total_distanct' => 'Total Distanct',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'customer_id' => 'Customer ID',
            'hp' => 'Hp',
            'oil_rate_qty' => 'Oil Rate Qty',
            'item_back_id' => 'Item Back ID',
            'company_id' => 'Company ID',
            'dropoff_place_id' => 'Dropoff Place ID',
            'dropoff_qty' => 'Dropoff Qty',
            'dropoff_name' => 'Dropoff Name',
            'labour_price' => 'Labour Price',
            'express_road_price' => 'Express Road Price',
            'other_price' => 'Other Price',
            'car_type_id' => 'Car Type ID',
            'car_type_name' => 'Car Type Name',
        ];
    }
}
