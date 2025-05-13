<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%quotation_dropoff}}`.
 */
class m250513_130404_create_quotation_dropoff_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%quotation_dropoff}}', [
            'id' => $this->primaryKey(),
            'quotation_rate_id' => $this->integer(),
            'dropoff_id' => $this->integer(),
            'route_no' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%quotation_dropoff}}');
    }
}
