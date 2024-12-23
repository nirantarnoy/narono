<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queue}}`.
 */
class m241223_063250_add_other_amt_column_to_work_queue_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue}}', 'other_amt', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue}}', 'other_amt');
    }
}
