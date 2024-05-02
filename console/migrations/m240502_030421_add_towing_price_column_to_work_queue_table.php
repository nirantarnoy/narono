<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queue}}`.
 */
class m240502_030421_add_towing_price_column_to_work_queue_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue}}', 'towing_price', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue}}', 'towing_price');
    }
}
