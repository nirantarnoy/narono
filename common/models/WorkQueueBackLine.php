<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "work_queue_back_line".
 *
 * @property int $id
 * @property int|null $work_queue_back_id
 * @property string|null $doc
 * @property int|null $status
 * @property string|null $description
 */
class WorkQueueBackLine extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work_queue_back_line';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['work_queue_back_id', 'status'], 'integer'],
            [['doc', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'work_queue_back_id' => 'Work Queue Back ID',
            'doc' => 'Doc',
            'status' => 'Status',
            'description' => 'Description',
        ];
    }
}
