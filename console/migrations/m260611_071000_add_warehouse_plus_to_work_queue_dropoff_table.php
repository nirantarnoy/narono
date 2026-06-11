<?php

use yii\db\Migration;

/**
 * Class m260611_071000_add_warehouse_plus_to_work_queue_dropoff_table
 */
class m260611_071000_add_warehouse_plus_to_work_queue_dropoff_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('work_queue_dropoff', 'warehouse_plus', $this->float()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('work_queue_dropoff', 'warehouse_plus');
    }
}
