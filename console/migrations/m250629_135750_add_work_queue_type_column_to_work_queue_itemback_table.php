<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queue_itemback}}`.
 */
class m250629_135750_add_work_queue_type_column_to_work_queue_itemback_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue_itemback}}', 'work_queue_type', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue_itemback}}', 'work_queue_type');
    }
}
