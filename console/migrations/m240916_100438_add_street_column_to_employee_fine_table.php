<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%employee_fine}}`.
 */
class m240916_100438_add_street_column_to_employee_fine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%employee_fine}}', 'street', $this->string());
        $this->addColumn('{{%employee_fine}}', 'zone', $this->string());
        $this->addColumn('{{%employee_fine}}', 'kilometer', $this->string());
        $this->addColumn('{{%employee_fine}}', 'district_id', $this->integer());
        $this->addColumn('{{%employee_fine}}', 'city_id', $this->integer());
        $this->addColumn('{{%employee_fine}}', 'province_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%employee_fine}}', 'street');
        $this->dropColumn('{{%employee_fine}}', 'zone');
        $this->dropColumn('{{%employee_fine}}', 'kilometer');
        $this->dropColumn('{{%employee_fine}}', 'district_id');
        $this->dropColumn('{{%employee_fine}}', 'city_id');
        $this->dropColumn('{{%employee_fine}}', 'province_id');
    }
}
