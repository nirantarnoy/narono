<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "work_queue_dropoff".
 *
 * @property int $id
 * @property int|null $work_queue_id
 * @property int|null $dropoff_id
 */
class WorkQueueDropoff extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work_queue_dropoff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['work_queue_id', 'dropoff_id'], 'integer'],
            [['qty','weight','price_per_ton','price_line_total'], 'number'],
            [['dropoff_no'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'work_queue_id' => 'Work Queue ID',
            'dropoff_id' => 'Dropoff ID',
        ];
    }
}
