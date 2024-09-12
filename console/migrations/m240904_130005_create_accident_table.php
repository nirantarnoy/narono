<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%accident}}`.
 */
class m240904_130005_create_accident_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%accident}}', [
            'id' => $this->primaryKey(),
            'case_no' => $this->string(),
            'trans_date' => $this->datetime(),
            'place' => $this->string(),
            'car_id' => $this->integer(),
            'emp_id' => $this->integer(),
            'accident_title_id' => $this->integer(),
            'accident_title_name' => $this->string(),
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
        $this->dropTable('{{%accident}}');
    }
}
