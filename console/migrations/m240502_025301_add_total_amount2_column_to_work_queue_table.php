<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queue}}`.
 */
class m240502_025301_add_total_amount2_column_to_work_queue_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue}}', 'total_amount2', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue}}', 'total_amount2');
    }
}
