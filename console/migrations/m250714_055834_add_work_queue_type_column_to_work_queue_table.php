<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queue}}`.
 */
class m250714_055834_add_work_queue_type_column_to_work_queue_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue}}', 'work_queue_type', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue}}', 'work_queue_type');
    }
}
