<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%quotation_rate}}`.
 */
class m250331_024851_add_drop_off_id_column_to_quotation_rate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%quotation_rate}}', 'drop_off_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%quotation_rate}}', 'drop_off_id');
    }
}
