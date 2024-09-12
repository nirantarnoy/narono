<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%alert}}`.
 */
class m240904_130505_create_alert_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%alert}}', [
            'id' => $this->primaryKey(),
            'trans_date' => $this->datetime(),
            'alert_month' => $this->integer(),
            'normal_alert' => $this->integer(),
            'city_alert' => $this->integer(),
            'four_hour_alert' => $this->integer(),
            'ten_hour_alert' => $this->integer(),
            'status' => $this->integer(),
            'company_id' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%alert}}');
    }
}
