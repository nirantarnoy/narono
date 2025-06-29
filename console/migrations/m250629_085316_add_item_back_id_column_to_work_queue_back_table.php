<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queue_back}}`.
 */
class m250629_085316_add_item_back_id_column_to_work_queue_back_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue_back}}', 'item_back_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue_back}}', 'item_back_id');
    }
}
