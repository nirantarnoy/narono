<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "customer_invoice_line".
 *
 * @property int $id
 * @property int|null $invoice_id
 * @property string|null $item_name
 * @property float|null $qty
 * @property float|null $price
 * @property float|null $line_total
 * @property int|null $status
 */
class CustomerInvoiceLine extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_invoice_line';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id', 'status','item_work_id'], 'integer'],
            [['qty', 'price', 'line_total'], 'number'],
            [['item_name'], 'string', 'max' => 255],
            [['cus_po_id'],'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_id' => 'Invoice ID',
            'item_name' => 'Item Name',
            'qty' => 'Qty',
            'price' => 'Price',
            'line_total' => 'Line Total',
            'status' => 'Status',
        ];
    }
}
