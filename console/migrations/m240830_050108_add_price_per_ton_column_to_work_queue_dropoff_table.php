<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queue_dropoff}}`.
 */
class m240830_050108_add_price_per_ton_column_to_work_queue_dropoff_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue_dropoff}}', 'price_per_ton', $this->float());
        $this->addColumn('{{%work_queue_dropoff}}', 'price_line_total', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue_dropoff}}', 'price_per_ton');
        $this->dropColumn('{{%work_queue_dropoff}}', 'price_line_total');
    }
}
