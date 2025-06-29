<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "work_queue_itemback".
 *
 * @property int $id
 * @property int|null $work_queue_id
 * @property int|null $item_back_id
 */
class WorkQueueItemback extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work_queue_itemback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['work_queue_id', 'item_back_id','work_queue_type'], 'integer'],


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
            'item_back_id' => 'Item Back ID',
            'work_queue_type' => 'Work Queue Type',
        ];
    }
}
