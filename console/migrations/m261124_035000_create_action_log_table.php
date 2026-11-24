<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%action_log}}`.
 */
class m261124_035000_create_action_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%action_log}}', [
            'id' => $this->bigPrimaryKey(),
            'user_id' => $this->integer()->null(),
            'username' => $this->string(255)->null(),
            'action' => $this->string(50)->notNull(), // CREATE, UPDATE, DELETE
            'table_name' => $this->string(100)->notNull(),
            'model_class' => $this->string(255)->notNull(),
            'record_id' => $this->string(100)->null(),
            'record_name' => $this->string(255)->null(),
            'description' => $this->text()->null(),
            'raw_data_before' => 'LONGTEXT',
            'raw_data_after' => 'LONGTEXT',
            'data_before_json' => 'LONGTEXT',
            'data_after_json' => 'LONGTEXT',
            'url' => $this->string(500)->null(),
            'method' => $this->string(20)->null(),
            'ip_address' => $this->string(45)->null(),
            'user_agent' => $this->string(500)->null(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-action_log-table_name-record_id', '{{%action_log}}', ['table_name', 'record_id']);
        $this->createIndex('idx-action_log-user_id', '{{%action_log}}', 'user_id');
        $this->createIndex('idx-action_log-action', '{{%action_log}}', 'action');
        $this->createIndex('idx-action_log-created_at', '{{%action_log}}', 'created_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%action_log}}');
    }
}

