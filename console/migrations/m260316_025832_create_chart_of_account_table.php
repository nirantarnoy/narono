<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chart_of_account}}`.
 */
class m260316_025832_create_chart_of_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chart_of_account}}', [
            'id' => $this->primaryKey(),
            'account_code' => $this->string()->notNull(),
            'sub_account_code' => $this->string(),
            'name' => $this->string(),
            'name_en' => $this->string(),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
        
        $this->createIndex('idx-chart_of_account-account_code', '{{%chart_of_account}}', 'account_code');
        $this->createIndex('idx-chart_of_account-sub_account_code', '{{%chart_of_account}}', 'sub_account_code');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%chart_of_account}}');
    }
}
