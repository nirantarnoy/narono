<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "stock_sum".
 *
 * @property int $id
 * @property int|null $company_id
 * @property int|null $warehouse_id
 * @property int|null $item_id
 * @property float|null $qty
 * @property string|null $last_update
 */
class StockSum extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_sum';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'warehouse_id', 'item_id'], 'integer'],
            [['qty'], 'number'],
            [['last_update'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'warehouse_id' => 'Warehouse ID',
            'item_id' => 'Item ID',
            'qty' => 'Qty',
            'last_update' => 'Last Update',
        ];
    }
}
