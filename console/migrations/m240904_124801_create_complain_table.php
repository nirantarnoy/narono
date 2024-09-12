<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%complain}}`.
 */
class m240904_124801_create_complain_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%complain}}', [
            'id' => $this->primaryKey(),
            'case_no' => $this->string(),
            'trans_date' => $this->datetime(),
            'complain_title_id' => $this->integer(),
            'place' => $this->string(),
            'emp_id' => $this->integer(),
            'car_id' => $this->integer(),
            'plate_no' => $this->string(),
            'description' => $this->string(),
            'status' => $this->integer(),
            'company_id' => $this->integer(),
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
        $this->dropTable('{{%complain}}');
    }
}
