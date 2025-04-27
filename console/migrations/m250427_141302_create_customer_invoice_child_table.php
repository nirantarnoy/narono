<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer_invoice_child}}`.
 */
class m250427_141302_create_customer_invoice_child_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customer_invoice_child}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer(),
            'customer_child_id' => $this->integer(),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%customer_invoice_child}}');
    }
}
