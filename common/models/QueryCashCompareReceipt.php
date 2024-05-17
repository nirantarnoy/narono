<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "query_cash_compare_receipt".
 *
 * @property string|null $trans_date
 * @property int $id
 * @property int|null $trans_ref_id
 * @property int|null $activity_type_id
 * @property int|null $company_id
 * @property int|null $office_id
 * @property int|null $company_id_2
 * @property int|null $office_id_2
 */
class QueryCashCompareReceipt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'query_cash_compare_receipt';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trans_date'], 'safe'],
            [['id', 'trans_ref_id', 'activity_type_id', 'company_id', 'office_id', 'company_id_2', 'office_id_2'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'trans_date' => 'Trans Date',
            'id' => 'ID',
            'trans_ref_id' => 'Trans Ref ID',
            'activity_type_id' => 'Activity Type ID',
            'company_id' => 'Company ID',
            'office_id' => 'Office ID',
            'company_id_2' => 'Company Id 2',
            'office_id_2' => 'Office Id 2',
        ];
    }
}
