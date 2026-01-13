<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "tax_import".
 *
 * @property int $id
 * @property int|null $sequence
 * @property string|null $doc_date
 * @property string|null $reference_no
 * @property string|null $vendor_name
 * @property string|null $tax_id
 * @property string|null $branch_code
 * @property string|null $tax_invoice_no
 * @property string|null $tax_invoice_date
 * @property string|null $tax_record_date
 * @property int|null $price_type
 * @property string|null $account_code
 * @property string|null $description
 * @property float|null $qty
 * @property float|null $unit_price
 * @property string|null $tax_rate
 * @property string|null $wht_amount
 * @property string|null $paid_by
 * @property float|null $paid_amount
 * @property string|null $pnd_type
 * @property string|null $remarks
 * @property string|null $group_type
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class TaxImport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tax_import}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sequence', 'price_type', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['qty', 'unit_price', 'paid_amount'], 'number'],
            [['doc_date', 'tax_invoice_date', 'tax_record_date'], 'string', 'max' => 8],
            [['reference_no'], 'string', 'max' => 32],
            [['vendor_name'], 'string', 'max' => 255],
            [['tax_id'], 'string', 'max' => 13],
            [['branch_code'], 'string', 'max' => 5],
            [['tax_invoice_no'], 'string', 'max' => 35],
            [['account_code', 'tax_rate', 'wht_amount', 'paid_by', 'pnd_type', 'group_type'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 1000],
            [['remarks'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sequence' => 'ลำดับที่',
            'doc_date' => 'วันที่เอกสาร',
            'reference_no' => 'อ้างอิงถึง',
            'vendor_name' => 'ผู้รับเงิน/คู่ค้า',
            'tax_id' => 'เลขทะเบียน 13 หลัก',
            'branch_code' => 'เลขสาขา 5 หลัก',
            'tax_invoice_no' => 'เลขที่ใบกำกับฯ',
            'tax_invoice_date' => 'วันที่ใบกำกับฯ',
            'tax_record_date' => 'วันที่บันทึกภาษีซื้อ',
            'price_type' => 'ประเภทราคา',
            'account_code' => 'บัญชี',
            'description' => 'คำอธิบาย',
            'qty' => 'จำนวน',
            'unit_price' => 'ราคาต่อหน่วย',
            'tax_rate' => 'อัตราภาษี',
            'wht_amount' => 'หัก ณ ที่จ่าย',
            'paid_by' => 'ชำระโดย',
            'paid_amount' => 'จำนวนเงินที่ชำระ',
            'pnd_type' => 'ภ.ง.ด.',
            'remarks' => 'หมายเหตุ',
            'group_type' => 'กลุ่มจัดประเภท',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
        ];
    }
}
