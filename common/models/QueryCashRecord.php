<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "query_cash_record".
 *
 * @property int $id
 * @property string|null $journal_no
 * @property string|null $trans_date
 * @property int|null $payment_method_id
 * @property int|null $company_id
 * @property string|null $pay_for
 * @property int|null $pay_for_type_id
 * @property int|null $cost_title_id
 * @property float|null $amount
 * @property string|null $remark
 * @property string|null $name
 * @property string|null $description
 * @property int|null $car_id
 * @property int|null $car_tail_id
 * @property string|null $car_name
 * @property string|null $car_plate_no
 * @property string|null $car_tail_name
 * @property string|null $car_tail_plate_no
 */
class QueryCashRecord extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'query_cash_record';
    }

    /**
     * {@inheritdoc}
     */
    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'payment_method_id', 'company_id', 'pay_for_type_id', 'cost_title_id', 'car_id', 'car_tail_id'], 'integer'],
            [['trans_date'], 'safe'],
            [['amount'], 'number'],
            [['journal_no', 'pay_for', 'remark', 'name', 'description', 'car_name', 'car_plate_no', 'car_tail_name', 'car_tail_plate_no'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'journal_no' => 'Journal No',
            'trans_date' => 'Trans Date',
            'payment_method_id' => 'Payment Method ID',
            'company_id' => 'Company ID',
            'pay_for' => 'Pay For',
            'pay_for_type_id' => 'Pay For Type ID',
            'cost_title_id' => 'Cost Title ID',
            'amount' => 'Amount',
            'remark' => 'Remark',
            'name' => 'Name',
            'description' => 'Description',
            'car_id' => 'Car ID',
            'car_tail_id' => 'Car Tail ID',
            'car_name' => 'Car Name',
            'car_plate_no' => 'Car Plate No',
            'car_tail_name' => 'Car Tail Name',
            'car_tail_plate_no' => 'Car Tail Plate No',
        ];
    }
}
