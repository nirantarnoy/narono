<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queue_dropoff}}`.
 */
class m250408_030933_add_is_invoice_column_to_work_queue_dropoff_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue_dropoff}}', 'is_invoice', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue_dropoff}}', 'is_invoice');
    }
}
