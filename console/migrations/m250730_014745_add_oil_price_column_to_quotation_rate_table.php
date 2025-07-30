<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%quotation_rate}}`.
 */
class m250730_014745_add_oil_price_column_to_quotation_rate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%quotation_rate}}', 'oil_price', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%quotation_rate}}', 'oil_price');
    }
}
