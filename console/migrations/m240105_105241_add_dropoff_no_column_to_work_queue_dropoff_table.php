<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queue_dropoff}}`.
 */
class m240105_105241_add_dropoff_no_column_to_work_queue_dropoff_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue_dropoff}}', 'dropoff_no', $this->string());
        $this->addColumn('{{%work_queue_dropoff}}', 'qty', $this->float());
        $this->addColumn('{{%work_queue_dropoff}}', 'weight', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue_dropoff}}', 'dropoff_no');
        $this->dropColumn('{{%work_queue_dropoff}}', 'qty');
        $this->dropColumn('{{%work_queue_dropoff}}', 'weight');
    }
}
