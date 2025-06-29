<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "work_queue_back".
 *
 * @property int $id
 * @property string|null $work_queue_no
 * @property string|null $work_queue_date
 * @property int|null $customer_id
 * @property int|null $emp_assign
 * @property int|null $route_plan_id
 * @property int|null $car_id
 * @property int|null $tail_id
 * @property float|null $weight_on_go
 * @property float|null $weight_on_back
 * @property float|null $weight_go_deduct
 * @property string|null $go_deduct_reason
 * @property float|null $back_deduct
 * @property string|null $back_reason
 * @property int|null $tail_back_id
 * @property int|null $approve_status
 * @property int|null $approve_by
 * @property string|null $dp_no
 * @property int|null $is_labur
 * @property int|null $is_invoice
 * @property int|null $is_express_road
 * @property float|null $labour_price
 * @property float|null $express_road_price
 * @property int|null $destination_id
 * @property float|null $cover_sheet_price
 * @property float|null $overnight_price
 * @property float|null $warehouse_plus_price
 * @property float|null $other_price
 * @property int|null $is_other
 * @property float|null $test_price
 * @property float|null $damaged_price
 * @property int|null $work_option_type_id
 * @property float|null $oil_daily_price
 * @property float|null $total_lite
 * @property float|null $total_distance
 * @property float|null $total_amount
 * @property float|null $deduct_other_price
 * @property float|null $work_double_price
 * @property float|null $oil_out_price
 * @property float|null $total_amount2
 * @property float|null $towing_price
 * @property float|null $total_out_lite
 * @property float|null $other_amt
 * @property int|null $status
 * @property int|null $create_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class WorkQueueBack extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work_queue_back';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['work_queue_date'], 'safe'],
            [['customer_id', 'emp_assign', 'route_plan_id', 'car_id', 'tail_id', 'tail_back_id', 'approve_status', 'approve_by', 'is_labur', 'is_invoice', 'is_express_road', 'destination_id', 'is_other', 'work_option_type_id', 'status', 'create_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['weight_on_go', 'weight_on_back', 'weight_go_deduct', 'back_deduct', 'labour_price', 'express_road_price', 'cover_sheet_price', 'overnight_price', 'warehouse_plus_price', 'other_price', 'test_price', 'damaged_price', 'oil_daily_price', 'total_lite', 'total_distance', 'total_amount', 'deduct_other_price', 'work_double_price', 'oil_out_price', 'total_amount2', 'towing_price', 'total_out_lite', 'other_amt'], 'number'],
            [['work_queue_no', 'go_deduct_reason', 'back_reason', 'dp_no'], 'string', 'max' => 255],
            [['item_back_id','paper_type_name','drop_place'],'safe'],
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
            'go_deduct_reason' => 'หมายเหตุ',
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
            'item_back_id'=> 'ประเภทของกลับ',
            'paper_type_name'=>'ประเภทเศษกระดาษ',
            'drop_place'=>'สถานที่ลงของ',
            'other_amt' => 'อื่นๆ',
            'create_at' => 'Create At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
