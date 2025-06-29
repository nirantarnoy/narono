<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queque_back}}`.
 */
class m250629_134004_add_drop_place_column_to_work_queue_back_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue_back}}', 'drop_place', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue_back}}', 'drop_place');
    }
}
