<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%work_queue_back_line}}`.
 */
class m250629_084821_create_work_queue_back_line_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%work_queue_back_line}}', [
            'id' => $this->primaryKey(),
            'work_queue_back_id' => $this->integer(),
            'doc' => $this->string(),
            'status' => $this->integer(),
            'description' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%work_queue_back_line}}');
    }
}
