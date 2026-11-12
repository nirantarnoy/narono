<?php

use yii\db\Migration;

/**
 * Adds performance indexes to work_queue and work_queue_dropoff tables.
 */
class m261112_040000_add_indexes_to_work_queue_and_dropoff extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Indexes on work_queue
        $this->createIndex(
            'idx-work_queue-work_queue_date',
            '{{%work_queue}}',
            'work_queue_date'
        );

        $this->createIndex(
            'idx-work_queue-car_id',
            '{{%work_queue}}',
            'car_id'
        );

        $this->createIndex(
            'idx-work_queue-emp_assign',
            '{{%work_queue}}',
            'emp_assign'
        );

        $this->createIndex(
            'idx-work_queue-company_id',
            '{{%work_queue}}',
            'company_id'
        );

        $this->createIndex(
            'idx-work_queue-customer_id',
            '{{%work_queue}}',
            'customer_id'
        );

        // Indexes on work_queue_dropoff
        $this->createIndex(
            'idx-work_queue_dropoff-work_queue_id',
            '{{%work_queue_dropoff}}',
            'work_queue_id'
        );

        $this->createIndex(
            'idx-work_queue_dropoff-dropoff_id',
            '{{%work_queue_dropoff}}',
            'dropoff_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-work_queue_dropoff-dropoff_id', '{{%work_queue_dropoff}}');
        $this->dropIndex('idx-work_queue_dropoff-work_queue_id', '{{%work_queue_dropoff}}');
        $this->dropIndex('idx-work_queue-customer_id', '{{%work_queue}}');
        $this->dropIndex('idx-work_queue-company_id', '{{%work_queue}}');
        $this->dropIndex('idx-work_queue-emp_assign', '{{%work_queue}}');
        $this->dropIndex('idx-work_queue-car_id', '{{%work_queue}}');
        $this->dropIndex('idx-work_queue-work_queue_date', '{{%work_queue}}');
    }
}

