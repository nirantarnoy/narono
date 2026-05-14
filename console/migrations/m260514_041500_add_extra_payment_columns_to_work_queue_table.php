<?php

use yii\db\Migration;

/**
 * Handles adding extra columns to table `{{%work_queue}}`.
 */
class m260514_041500_add_extra_payment_columns_to_work_queue_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue}}', 'labour_price_general', $this->double());
        $this->addColumn('{{%work_queue}}', 'labour_price_special', $this->double());
        $this->addColumn('{{%work_queue}}', 'delivery_2_cus_price', $this->double());
        $this->addColumn('{{%work_queue}}', 'incentive_price', $this->double());
        $this->addColumn('{{%work_queue}}', 'sunday_price', $this->double());
        $this->addColumn('{{%work_queue}}', 'rangsit_price', $this->double());
        $this->addColumn('{{%work_queue}}', 'diligence_price', $this->double());
        $this->addColumn('{{%work_queue}}', 'traffic_fine_price', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue}}', 'labour_price_general');
        $this->dropColumn('{{%work_queue}}', 'labour_price_special');
        $this->dropColumn('{{%work_queue}}', 'delivery_2_cus_price');
        $this->dropColumn('{{%work_queue}}', 'incentive_price');
        $this->dropColumn('{{%work_queue}}', 'sunday_price');
        $this->dropColumn('{{%work_queue}}', 'rangsit_price');
        $this->dropColumn('{{%work_queue}}', 'diligence_price');
        $this->dropColumn('{{%work_queue}}', 'traffic_fine_price');
    }
}
