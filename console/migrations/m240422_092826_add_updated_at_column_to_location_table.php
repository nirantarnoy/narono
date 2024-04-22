<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%location}}`.
 */
class m240422_092826_add_updated_at_column_to_location_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%location}}', 'updated_at', $this->integer());
        $this->addColumn('{{%location}}', 'updated_by', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%location}}', 'updated_at');
        $this->dropColumn('{{%location}}', 'updated_by');
    }
}
