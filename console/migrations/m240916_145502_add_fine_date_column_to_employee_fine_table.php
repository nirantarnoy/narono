<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%employee_fine}}`.
 */
class m240916_145502_add_fine_date_column_to_employee_fine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%employee_fine}}', 'fine_date', $this->datetime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%employee_fine}}', 'fine_date');
    }
}
