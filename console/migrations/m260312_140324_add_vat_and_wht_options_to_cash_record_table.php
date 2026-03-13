<?php

use yii\db\Migration;

class m260312_140324_add_vat_and_wht_options_to_cash_record_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cash_record', 'is_vat', $this->integer()->defaultValue(0));
        $this->addColumn('cash_record', 'is_wht', $this->integer()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('cash_record', 'is_vat');
        $this->dropColumn('cash_record', 'is_wht');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260312_140324_add_vat_and_wht_options_to_cash_record_table cannot be reverted.\n";

        return false;
    }
    */
}
