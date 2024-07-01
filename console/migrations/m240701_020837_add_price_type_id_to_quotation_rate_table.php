<?php

use yii\db\Migration;

/**
 * Class m240701_020837_add_price_type_id_to_quotation_rate_table
 */
class m240701_020837_add_price_type_id_to_quotation_rate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240701_020837_add_price_type_id_to_quotation_rate_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240701_020837_add_price_type_id_to_quotation_rate_table cannot be reverted.\n";

        return false;
    }
    */
}
