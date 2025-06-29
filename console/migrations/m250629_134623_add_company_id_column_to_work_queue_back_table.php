<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queue_back}}`.
 */
class m250629_134623_add_company_id_column_to_work_queue_back_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue_back}}', 'company_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue_back}}', 'company_id');
    }
}
