<?php

use yii\db\Migration;

class m260219_065602_add_check_no_and_account_table_to_cash_record extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cash_record', 'check_no', $this->string()->after('bank_account'));
        
        $this->createTable('cash_record_account', [
            'id' => $this->primaryKey(),
            'cash_record_id' => $this->integer(),
            'account_name' => $this->string(),
            'account_code' => $this->string(),
            'debit' => $this->decimal(10, 2),
            'credit' => $this->decimal(10, 2),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('cash_record_account');
        $this->dropColumn('cash_record', 'check_no');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260219_065602_add_check_no_and_account_table_to_cash_record cannot be reverted.\n";

        return false;
    }
    */
}
