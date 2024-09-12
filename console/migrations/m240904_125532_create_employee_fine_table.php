<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_fine}}`.
 */
class m240904_125532_create_employee_fine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_fine}}', [
            'id' => $this->primaryKey(),
            'case_no' => $this->string(),
            'trans_date' => $this->datetime(),
            'place' => $this->string(),
            'cause_description' => $this->string(),
            'company_id' => $this->integer(),
            'car_id' => $this->integer(),
            'emp_id' => $this->integer(),
            'customer_id' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%employee_fine}}');
    }
}
