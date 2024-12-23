<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "work_queue".
 *
 * @property int $id
 * @property string|null $work_queue_no
 * @property string|null $work_queue_date
 * @property int|null $customer_id
 * @property int|null $emp_assign
 * @property int|null $status
 * @property int|null $create_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class WorkQueue extends \yii\db\ActiveRecord
{
    public $item_back_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work_queue';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['work_queue_date'], 'safe'],
            [['customer_id', 'emp_assign', 'status', 'create_at', 'created_by', 'updated_at', 'updated_by','route_plan_id','tail_id','car_id','tail_back_id','approve_status','approve_by','is_labur','is_express_road','is_other','work_option_type_id','is_invoice','company_id'], 'integer'],
            [['work_queue_no','go_deduct_reason','back_reason','dp_no'], 'string', 'max' => 255],
            [['weight_on_go','weight_on_back','weight_go_deduct','back_deduct','labour_price','express_road_price','other_price','test_price','damaged_price','total_lite','total_distance','total_amount'], 'double'],
            [['cover_sheet_price','overnight_price','warehouse_plus_price','deduct_other_price','work_double_price','total_amount2','towing_price','other_amt'], 'double'],
            [['oil_daily_price','oil_out_price','total_out_lite'],'safe'],
            [['item_back_id'],'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'work_queue_no' => 'คิวงานเลขที่',
            'work_queue_date' => 'วันที่',
            'customer_id' => 'ลูกค้า',
            'emp_assign' => 'พนักงาน',
            'status' => 'สถานะ',
            'route_plan_id' => 'ปลายทาง',
            'car_id' => 'ทะเบียนหัว',
            'tail_id' => 'ทะเบียนหาง',
            'weight_on_go' => 'น้ำหนักเที่ยวไป',
            'weight_on_back' => 'น้ำหนักเที่ยวกลับ',
            'weight_go_deduct' => 'หักขาไป',
            'back_deduct' => 'หักขากลับ',
            'go_deduct_reason' => 'เหตุผลขาไป',
            'back_reason' => 'เหตุผลขากลับ',
            'tail_back_id' => 'ส่วนพ่วงขากลับ',
            'approve_status' => 'อนุมัติ',
            'approve_by'=>'ผู้อนุมัติ',
            'dp_no' => 'DP/Shipment',
            'oil_daily_price'=> 'ราคาน้ำมัน',
            'is_labur'=>'มีค่าเที่ยว',
            'is_express_road'=>'มีค่าทางด่วน',
            'is_other'=>'มีค่าอื่นๆ',
            'labour_price' => 'ค่าเที่ยว',
            'express_road_price'=> 'ค่าทางด่วน',
            'other_price' => 'พิเศษอื่นๆ',
            'test_price' => 'ค่าเงินยืมทดรอง',
            'damaged_price' => 'เงินประกันสินค้าเสียหาย',
            'cover_sheet_price' => 'ค่าคลุมผ้าใบ',
            'overnight_price' => 'ค่าค้างคืน',
            'warehouse_plus_price' => 'ค่าบวกคลัง',
            'work_option_type_id'=>'Work Type',
            'deduct_other_price'=>'หักเงิน อื่นๆ',
            'work_double_price'=>'ค่าเบิ้ลงาน',
            'total_amount2'=>'ราคาปั๊มนอก(บาท)',
            'towing_price'=>'ค่าลาก/ค่าแบก',
            'total_out_lite' => 'รวมจำนวนปั๊มนอก(ลิตร)',
            'other_amt' => 'อื่นๆ',
            'create_at' => 'Create At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
