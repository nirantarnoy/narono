<?php

use yii\db\Migration;

class m260316_031324_add_account_id_to_fixcost_title_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%fixcost_title}}', 'account_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%fixcost_title}}', 'account_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260316_031324_add_account_id_to_fixcost_title_table cannot be reverted.\n";

        return false;
    }
    */
}
