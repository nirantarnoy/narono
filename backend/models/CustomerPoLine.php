<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customer_po_line".
 *
 * @property int $id รหัสรายการ
 * @property int $po_id รหัส PO
 * @property string $item_name ชื่องาน
 * @property string|null $description รายละเอียดงาน
 * @property float $qty จำนวน
 * @property string|null $unit หน่วยนับ
 * @property float $price ราคาต่อหน่วย
 * @property float $line_total ราคารวม
 * @property int|null $sort_order ลำดับ
 * @property string|null $status สถานะ
 * @property string $created_at วันที่สร้าง
 * @property string $updated_at วันที่แก้ไข
 * @property int|null $created_by ผู้สร้าง
 * @property int|null $updated_by ผู้แก้ไข
 *
 * @property User $createdBy
 * @property CustomerPo $po
 * @property User $updatedBy
 */
class CustomerPoLine extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_po_line';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'unit', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['qty'], 'default', 'value' => 1.00],
            [['line_total'], 'default', 'value' => 0.00],
            [['sort_order'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 'active'],
            [['po_id', 'item_name'], 'required'],
            [['po_id', 'sort_order', 'created_by', 'updated_by'], 'integer'],
            [['description'], 'string'],
            [['qty', 'price', 'line_total'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['item_name'], 'string', 'max' => 255],
            [['unit'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 20],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['po_id'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerPo::class, 'targetAttribute' => ['po_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'po_id' => 'Po ID',
            'item_name' => 'Item Name',
            'description' => 'Description',
            'qty' => 'Qty',
            'unit' => 'Unit',
            'price' => 'Price',
            'line_total' => 'Line Total',
            'sort_order' => 'Sort Order',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Po]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPo()
    {
        return $this->hasOne(CustomerPo::class, ['id' => 'po_id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

}
