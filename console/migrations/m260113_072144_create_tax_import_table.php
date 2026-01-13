<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tax_import}}`.
 */
class m260113_072144_create_tax_import_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tax_import}}', [
            'id' => $this->primaryKey(),
            'sequence' => $this->integer(),
            'doc_date' => $this->string(8),
            'reference_no' => $this->string(32),
            'vendor_name' => $this->string(255),
            'tax_id' => $this->string(13),
            'branch_code' => $this->string(5),
            'tax_invoice_no' => $this->string(35),
            'tax_invoice_date' => $this->string(8),
            'tax_record_date' => $this->string(8),
            'price_type' => $this->integer()->defaultValue(1),
            'account_code' => $this->string(50),
            'description' => $this->string(1000),
            'qty' => $this->decimal(12, 4)->defaultValue(1),
            'unit_price' => $this->decimal(12, 4)->defaultValue(0),
            'tax_rate' => $this->string(10)->defaultValue('7%'),
            'wht_amount' => $this->string(50)->defaultValue('0'),
            'paid_by' => $this->string(50),
            'paid_amount' => $this->decimal(12, 4)->defaultValue(0),
            'pnd_type' => $this->string(10),
            'remarks' => $this->string(500),
            'group_type' => $this->string(50),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tax_import}}');
    }
}
