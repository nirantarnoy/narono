<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queue_back}}`.
 */
class m250629_123448_add_papar_type_name_column_to_work_queue_back_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue_back}}', 'paper_type_name', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue_back}}', 'paper_type_name');
    }
}
