<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%min_weight}}`.
 */
class m260708_094652_create_min_weight_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%min_weight}}', [
            'id' => $this->primaryKey(),
            'item_name' => $this->string(255)->comment('รายการ'),
            'company_id' => $this->integer()->comment('บริษัท'),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        $this->createTable('{{%min_weight_line}}', [
            'id' => $this->primaryKey(),
            'min_weight_id' => $this->integer(),
            'car_type_id' => $this->integer()->comment('ประเภทรถ'),
            'options' => $this->string(255)->comment('ประเภทตู้/เปลือย'),
            'min_weight' => $this->decimal(10, 2)->comment('น้ำหนักขั้นต่ำ'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%min_weight_line}}');
        $this->dropTable('{{%min_weight}}');
    }
}
