<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%quotation_title}}`.
 */
class m250516_024706_add_price_type_id_column_to_quotation_title_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%quotation_title}}', 'price_type_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%quotation_title}}', 'price_type_id');
    }
}
