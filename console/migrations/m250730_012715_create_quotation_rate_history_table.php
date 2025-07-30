<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%quotation_rate_history}}`.
 */
class m250730_012715_create_quotation_rate_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%quotation_rate_history}}', [
            'id' => $this->primaryKey(),
            'quotation_title_id' => $this->integer(),
            'quotation_rate_id' => $this->integer(),
            'oil_price' => $this->float(),
            'rate_amount' => $this->float(),
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
        $this->dropTable('{{%quotation_rate_history}}');
    }
}
