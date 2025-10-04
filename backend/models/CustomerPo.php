<?php
namespace backend\models;

use common\models\CustomerInvoiceLine;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * CustomerPo Model
 *
 * @property int $id
 * @property string $po_number
 * @property string $po_date
 * @property string $po_target_date
 * @property int $customer_id
 * @property string $work_name
 * @property float $po_amount
 * @property float $billed_amount
 * @property float $remaining_amount
 * @property string $po_file
 * @property string $status
 * @property string $remark
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class CustomerPo extends ActiveRecord
{
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public $po_file_upload;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_po';
    }

    /**
     * {@inheritdoc}
     */
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
            [['po_number', 'po_date', 'po_target_date', 'customer_id', 'work_name'], 'required'],
            [['po_date', 'po_target_date'], 'date', 'format' => 'php:Y-m-d'],
            [['customer_id', 'created_by', 'updated_by'], 'integer'],
            [['po_amount', 'billed_amount', 'remaining_amount'], 'number', 'min' => 0],
            [['work_name', 'remark'], 'string'],
            [['po_number'], 'string', 'max' => 50],
            [['po_file'], 'string', 'max' => 255],
            [['status'], 'string'],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_COMPLETED, self::STATUS_CANCELLED]],
            [['po_number'], 'unique'],
            [['po_file_upload'], 'file', 'extensions' => 'pdf,doc,docx,jpg,jpeg,png', 'maxSize' => 1024 * 1024 * 10],
            [['po_target_date'], 'compare', 'compareAttribute' => 'po_date', 'operator' => '>=', 'message' => 'วันที่หมดอายุต้องมากกว่าหรือเท่ากับวันที่สร้าง PO'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'po_number' => 'เลขที่ PO',
            'po_date' => 'วันที่สร้าง PO',
            'po_target_date' => 'วันที่ PO หมดอายุ',
            'customer_id' => 'ลูกค้า',
            'work_name' => 'งาน',
            'po_amount' => 'มูลค่างาน',
            'billed_amount' => 'ยอดวางบิล',
            'remaining_amount' => 'คงเหลือ',
            'po_file' => 'ไฟล์ PO',
            'po_file_upload' => 'อัพโลดไฟล์ PO',
            'status' => 'สถานะ',
            'remark' => 'หมายเหตุ',
            'created_at' => 'สร้างเมื่อ',
            'updated_at' => 'แก้ไขเมื่อ',
            'created_by' => 'สร้างโดย',
            'updated_by' => 'แก้ไขโดย',
        ];
    }

    /**
     * Status options
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_ACTIVE => 'ดำเนินการ',
            self::STATUS_COMPLETED => 'เสร็จสิ้น',
            self::STATUS_CANCELLED => 'ยกเลิก',
        ];
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        $options = self::getStatusOptions();
        return isset($options[$this->status]) ? $options[$this->status] : $this->status;
    }

    /**
     * Get status badge
     */
    public function getStatusBadge()
    {
        $badges = [
            self::STATUS_ACTIVE => 'badge-success',
            self::STATUS_COMPLETED => 'badge-info',
            self::STATUS_CANCELLED => 'badge-danger',
        ];
        $class = isset($badges[$this->status]) ? $badges[$this->status] : 'badge-secondary';
        return '<span class="badge ' . $class . '">' . $this->getStatusLabel() . '</span>';
    }

    /**
     * Relation to customer
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    /**
     * Relation to PO invoices
     */
    public function getPoInvoices()
    {
        return $this->hasMany(CustomerPoInvoice::class, ['po_id' => 'id']);
    }

    /**
     * Relation to invoices through po_invoices
     */
    public function getInvoices()
    {
        return $this->hasMany(CustomerInvoice::class, ['id' => 'invoice_id'])
            ->via('poInvoices');
    }

    /**
     * Before save - handle file upload and calculate remaining amount
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Handle file upload
            if ($this->po_file_upload) {
                $this->uploadFile();
            }

            // Calculate remaining amount
//            $this->po_amount = 0;
//            $this->billed_amount = 0;
            $this->remaining_amount = 0; // (($this->po_amount) - ($this->billed_amount))??0;


            return true;
        }
        return false;
    }

    /**
     * After save - update billed amount from invoices
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (!$insert) {
            $this->updateBilledAmount();
        }
    }

    /**
     * Upload file
     */
    public function uploadFile()
    {
        if ($this->po_file_upload) {
            $uploadPath = Yii::getAlias('@backend/web/uploads/po/');
            FileHelper::createDirectory($uploadPath);

            $fileName = $this->po_number . '_' . time() . '.' . $this->po_file_upload->extension;
            $filePath = $uploadPath . $fileName;

            if ($this->po_file_upload->saveAs($filePath)) {
                // Delete old file if exists
                if ($this->po_file && file_exists($uploadPath . $this->po_file)) {
                    unlink($uploadPath . $this->po_file);
                }
                $this->po_file = $fileName;
                return true;
            }
        }
        return false;
    }

    /**
     * Get file URL
     */
    public function getFileUrl()
    {
        if ($this->po_file) {
            return Yii::getAlias('@web/uploads/po/') . $this->po_file;
        }
        return null;
    }

    /**
     * Update billed amount from linked invoices
     */
    public function updateBilledAmount()
    {
        $totalBilled = CustomerInvoiceLine::find()
            ->where(['cus_po_id' => $this->id])
            ->sum('line_total') ?? 0;

        $this->updateAttributes([
            'billed_amount' => $totalBilled,
            'remaining_amount' => ($this->po_amount) - ($totalBilled)
        ]);

        // Update status based on remaining amount
        if ($this->remaining_amount <= 0 && $this->status === self::STATUS_ACTIVE) {
            $this->updateAttributes(['status' => self::STATUS_COMPLETED]);
        }
    }

    /**
     * Link invoice to PO
     */
    public function linkInvoice($invoiceId, $amount)
    {
        $poInvoice = new CustomerPoInvoice();
        $poInvoice->po_id = $this->id;
        $poInvoice->invoice_id = $invoiceId;
        $poInvoice->amount = $amount;

        if ($poInvoice->save()) {
            $this->updateBilledAmount();
            return true;
        }
        return false;
    }

    /**
     * Unlink invoice from PO
     */
    public function unlinkInvoice($invoiceId)
    {
        CustomerPoInvoice::deleteAll(['po_id' => $this->id, 'invoice_id' => $invoiceId]);
        $this->updateBilledAmount();
    }

    /**
     * Get formatted amount
     */
    public function getFormattedPoAmount()
    {
        return number_format($this->po_amount, 2);
    }

    public function getFormattedBilledAmount()
    {
        return number_format($this->billed_amount, 2);
    }

    public function getFormattedRemainingAmount()
    {
        return number_format($this->remaining_amount, 2);
    }

    // ใน backend/models/CustomerPo.php

    /**
     * Update PO amount from lines
     */
    public function updatePoAmountFromLines()
    {
        $total = CustomerPoLine::find()
            ->where(['po_id' => $this->id])
            ->sum('line_total');
        $this->po_amount = $total ?: 10;
        return $this->save(false, ['po_amount']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerPoLines()
    {
        return $this->hasMany(CustomerPoLine::class, ['po_id' => 'id'])
            ->orderBy(['sort_order' => SORT_ASC]);
    }
}