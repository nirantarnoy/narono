<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queue}}`.
 */
class m250814_080451_add_oil_out_price_2_column_to_work_queue_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue}}', 'oil_out_price_2', $this->float());
        $this->addColumn('{{%work_queue}}', 'total_out_lite_2', $this->float());
        $this->addColumn('{{%work_queue}}', 'total_amount3', $this->float());
        $this->addColumn('{{%work_queue}}', 'oil_out_price_3', $this->float());
        $this->addColumn('{{%work_queue}}', 'total_out_lite_3', $this->float());
        $this->addColumn('{{%work_queue}}', 'total_amount4', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue}}', 'oil_out_price_2');
        $this->dropColumn('{{%work_queue}}', 'total_out_lite_2');
        $this->dropColumn('{{%work_queue}}', 'total_amount3');
        $this->dropColumn('{{%work_queue}}', 'oil_out_price_3');
        $this->dropColumn('{{%work_queue}}', 'total_out_lite_3');
        $this->dropColumn('{{%work_queue}}', 'total_amount4');
    }
}
