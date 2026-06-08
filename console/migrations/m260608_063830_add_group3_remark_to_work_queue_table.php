<?php

use yii\db\Migration;

class m260608_063830_add_group3_remark_to_work_queue_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('work_queue', 'group3_remark', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('work_queue', 'group3_remark');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260608_063830_add_group3_remark_to_work_queue_table cannot be reverted.\n";

        return false;
    }
    */
}
