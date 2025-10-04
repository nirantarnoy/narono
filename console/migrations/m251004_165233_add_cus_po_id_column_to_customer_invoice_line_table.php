<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%customer_invoice_line}}`.
 */
class m251004_165233_add_cus_po_id_column_to_customer_invoice_line_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%customer_invoice_line}}', 'cus_po_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%customer_invoice_line}}', 'cus_po_id');
    }
}
