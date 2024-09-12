<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%alert}}`.
 */
class m240912_023727_add_emp_id_column_to_alert_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%alert}}', 'emp_id', $this->integer());
        $this->addColumn('{{%alert}}', 'car_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%alert}}', 'emp_id');
        $this->dropColumn('{{%alert}}', 'car_id');
    }
}
