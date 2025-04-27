<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "customer_invoice".
 *
 * @property int $id
 * @property string|null $invoice_no
 * @property string|null $invoice_date
 * @property string|null $invoice_target_date
 * @property int|null $sale_id
 * @property string|null $work_name
 * @property int|null $customer_id
 * @property float|null $total_amount
 * @property float|null $vat_amount
 * @property float|null $vat_per
 * @property float|null $total_all_amount
 * @property string|null $total_text
 * @property string|null $remark
 * @property string|null $remark2
 * @property int|null $create_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $status
 * @property string|null $customer_extend_remark
 * @property string|null $company_extend_remark
 */
class CustomerInvoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sale_id', 'customer_id'], 'required'],
            [['invoice_date', 'invoice_target_date'], 'safe'],
            [['sale_id', 'customer_id', 'create_at', 'created_by', 'updated_at', 'updated_by', 'status'], 'integer'],
            [['total_amount', 'vat_amount', 'vat_per', 'total_all_amount','final_amount'], 'number'],
            [['invoice_no', 'work_name', 'total_text', 'remark', 'remark2', 'customer_extend_remark', 'company_extend_remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_no' => 'เลขที่',
            'invoice_date' => 'วันที่',
            'invoice_target_date' => 'วันที่ครบกำหนด',
            'sale_id' => 'พนักงานวางบิล',
            'work_name' => 'Work Name',
            'customer_id' => 'ลูกค้า',
            'total_amount' => 'ยอดรวม',
            'vat_amount' => 'Vat',
            'vat_per' => 'Vat Per',
            'total_all_amount' => 'ยอดรวมทั้งสิ้น',
            'total_text' => 'ยอดรวมตัวหนังสือ',
            'final_amount'=> 'ยอดชำระ',
            'remark' => 'หมายเหตุ',
            'remark2' => 'หมายเหตุ2',
            'create_at' => 'Create At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'status' => 'สถานะ',
            'customer_extend_remark' => 'หมายเหตุลูกค้า',
            'company_extend_remark' => 'หมายเหตุบริษัท',
        ];
    }
}
