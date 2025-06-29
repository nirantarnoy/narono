<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%quotaion}}`.
 */
class m250629_073051_add_haul_id_column_to_quotation_title_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%quotation_title}}', 'haul_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%quotation_title}}', 'haul_id');
    }
}
