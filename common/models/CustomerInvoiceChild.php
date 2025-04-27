<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "customer_invoice_child".
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $customer_child_id
 * @property int|null $status
 */
class CustomerInvoiceChild extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_invoice_child';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'customer_child_id', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'customer_child_id' => 'Customer Child ID',
            'status' => 'Status',
        ];
    }
}
