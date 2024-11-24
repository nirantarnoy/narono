<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queue_dropoff}}`.
 */
class m241124_112140_add_is_charter_column_to_work_queue_dropoff_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue_dropoff}}', 'is_charter', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue_dropoff}}', 'is_charter');
    }
}
