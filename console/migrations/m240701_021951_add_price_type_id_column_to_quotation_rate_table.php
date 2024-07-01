<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%quotation_rate}}`.
 */
class m240701_021951_add_price_type_id_column_to_quotation_rate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%quotation_rate}}', 'price_type_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%quotation_rate}}', 'price_type_id');
    }
}
