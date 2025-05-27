<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queue_dropoff}}`.
 */
class m250527_012013_add_quotation_route_no_column_to_work_queue_dropoff_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue_dropoff}}', 'quotation_route_no', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue_dropoff}}', 'quotation_route_no');
    }
}
