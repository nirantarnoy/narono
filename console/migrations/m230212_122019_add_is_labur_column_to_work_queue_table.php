<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%work_queue}}`.
 */
class m230212_122019_add_is_labur_column_to_work_queue_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%work_queue}}', 'is_labur', $this->integer());
        $this->addColumn('{{%work_queue}}', 'is_express_road', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%work_queue}}', 'is_labur');
        $this->dropColumn('{{%work_queue}}', 'is_express_road');
    }
}
