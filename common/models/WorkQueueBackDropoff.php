<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "work_queue_back_dropoff".
 *
 * @property int $id
 * @property int|null $work_queue_back_id
 * @property int|null $dropoff_id
 * @property string|null $dropoff_no
 * @property float|null $qty
 * @property float|null $weight
 * @property float|null $price_per_ton
 * @property float|null $price_line_total
 * @property int|null $is_charter
 * @property int|null $is_invoice
 * @property string|null $quotation_route_no
 */
class WorkQueueBackDropoff extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work_queue_back_dropoff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['work_queue_back_id', 'dropoff_id', 'is_charter', 'is_invoice'], 'integer'],
            [['qty', 'weight', 'price_per_ton', 'price_line_total'], 'number'],
            [['dropoff_no', 'quotation_route_no'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'work_queue_back_id' => 'Work Queue Back ID',
            'dropoff_id' => 'Dropoff ID',
            'dropoff_no' => 'Dropoff No',
            'qty' => 'Qty',
            'weight' => 'Weight',
            'price_per_ton' => 'Price Per Ton',
            'price_line_total' => 'Price Line Total',
            'is_charter' => 'Is Charter',
            'is_invoice' => 'Is Invoice',
            'quotation_route_no' => 'Quotation Route No',
        ];
    }
}
