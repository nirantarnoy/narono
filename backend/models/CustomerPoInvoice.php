<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * CustomerPoInvoice Model
 *
 * @property int $id
 * @property int $po_id
 * @property int $invoice_id
 * @property float $amount
 * @property int $created_at
 * @property int $created_by
 */
class CustomerPoInvoice extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_po_invoices';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false, // Only track created_at
            ],
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false, // Only track created_by
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['po_id', 'invoice_id', 'amount'], 'required'],
            [['po_id', 'invoice_id', 'created_by'], 'integer'],
            [['amount'], 'number', 'min' => 0],
            [['po_id', 'invoice_id'], 'unique', 'targetAttribute' => ['po_id', 'invoice_id'], 'message' => 'ใบวางบิลนี้ถูกเชื่อมโยงกับ PO นี้แล้ว'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'po_id' => 'PO ID',
            'invoice_id' => 'Invoice ID',
            'amount' => 'จำนวนเงิน',
            'created_at' => 'สร้างเมื่อ',
            'created_by' => 'สร้างโดย',
        ];
    }

    /**
     * Relation to PO
     */
    public function getPo()
    {
        return $this->hasOne(CustomerPo::class, ['id' => 'po_id']);
    }

    /**
     * Relation to Invoice
     */
    public function getInvoice()
    {
        return $this->hasOne(CustomerInvoice::class, ['id' => 'invoice_id']);
    }

    /**
     * Before save - validate amount against PO remaining amount
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                // Check if amount doesn't exceed PO remaining amount
                $po = CustomerPo::findOne($this->po_id);
                if ($po) {
                    $currentBilled = CustomerPoInvoice::find()
                        ->where(['po_id' => $this->po_id])
                        ->sum('amount') ?? 0;

                    if (($currentBilled + $this->amount) > $po->po_amount) {
                        $this->addError('amount', 'จำนวนเงินเกินมูลค่า PO ที่เหลือ');
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }

    /**
     * After save - update PO billed amount
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->po) {
            $this->po->updateBilledAmount();
        }
    }

    /**
     * After delete - update PO billed amount
     */
    public function afterDelete()
    {
        parent::afterDelete();

        if ($this->po) {
            $this->po->updateBilledAmount();
        }
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmount()
    {
        return number_format($this->amount, 2);
    }
}