<?php

use yii\db\Migration;

class m260219_065950_change_amount_to_decimal_in_cash_record_line extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('cash_record_line', 'amount', $this->decimal(12, 2));
        $this->alterColumn('cash_record_line', 'vat_amount', $this->decimal(12, 2));
    }

    public function safeDown()
    {
        $this->alterColumn('cash_record_line', 'amount', $this->float());
        $this->alterColumn('cash_record_line', 'vat_amount', $this->float());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260219_065950_change_amount_to_decimal_in_cash_record_line cannot be reverted.\n";

        return false;
    }
    */
}
