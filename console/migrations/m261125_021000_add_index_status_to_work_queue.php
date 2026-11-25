<?php

use yii\db\Migration;

/**
 * Adds status index to work_queue for dashboard performance.
 */
class m261125_021000_add_index_status_to_work_queue extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Check if index already exists before creating
        $indexes = $this->db->createCommand("SHOW INDEX FROM {{%work_queue}} WHERE Key_name = 'idx-work_queue-status'")->queryAll();
        if (empty($indexes)) {
            $this->createIndex(
                'idx-work_queue-status',
                '{{%work_queue}}',
                'status'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-work_queue-status',
            '{{%work_queue}}'
        );
    }
}

