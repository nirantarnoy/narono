<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer_po_line}}`.
 */
class m241001_000001_create_customer_po_line_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customer_po_line}}', [
            'id' => $this->primaryKey()->comment('รหัสรายการ'),
            'po_id' => $this->integer()->notNull()->comment('รหัส PO'),
            'item_name' => $this->string(255)->notNull()->comment('ชื่องาน'),
            'description' => $this->text()->null()->comment('รายละเอียดงาน'),
            'qty' => $this->decimal(15, 2)->notNull()->defaultValue(1)->comment('จำนวน'),
            'unit' => $this->string(50)->null()->comment('หน่วยนับ'),
            'price' => $this->decimal(15, 2)->notNull()->defaultValue(0.00)->comment('ราคาต่อหน่วย'),
            'line_total' => $this->decimal(15, 2)->notNull()->defaultValue(0.00)->comment('ราคารวม'),
            'sort_order' => $this->integer()->defaultValue(0)->comment('ลำดับ'),
            'status' => $this->string(20)->defaultValue('active')->comment('สถานะ'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('วันที่สร้าง'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('วันที่แก้ไข'),
            'created_by' => $this->integer()->null()->comment('ผู้สร้าง'),
            'updated_by' => $this->integer()->null()->comment('ผู้แก้ไข'),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="ตารางรายละเอียด PO"');

        // Create indexes
        $this->createIndex(
            '{{%idx-customer_po_line-po_id}}',
            '{{%customer_po_line}}',
            'po_id'
        );

        $this->createIndex(
            '{{%idx-customer_po_line-sort_order}}',
            '{{%customer_po_line}}',
            'sort_order'
        );

        $this->createIndex(
            '{{%idx-customer_po_line-status}}',
            '{{%customer_po_line}}',
            'status'
        );

        // Add foreign key to customer_po
        $this->addForeignKey(
            '{{%fk-customer_po_line-po_id}}',
            '{{%customer_po_line}}',
            'po_id',
            '{{%customer_po}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Add foreign keys to user table (if exists)
        if ($this->checkTableExists('user')) {
            $this->addForeignKey(
                '{{%fk-customer_po_line-created_by}}',
                '{{%customer_po_line}}',
                'created_by',
                '{{%user}}',
                'id',
                'SET NULL',
                'CASCADE'
            );

            $this->addForeignKey(
                '{{%fk-customer_po_line-updated_by}}',
                '{{%customer_po_line}}',
                'updated_by',
                '{{%user}}',
                'id',
                'SET NULL',
                'CASCADE'
            );
        }

        // Add check constraints
        $this->execute("
            ALTER TABLE {{%customer_po_line}} 
            ADD CONSTRAINT {{%chk-customer_po_line-qty}} 
            CHECK (qty > 0)
        ");

        $this->execute("
            ALTER TABLE {{%customer_po_line}} 
            ADD CONSTRAINT {{%chk-customer_po_line-price}} 
            CHECK (price >= 0)
        ");

        $this->execute("
            ALTER TABLE {{%customer_po_line}} 
            ADD CONSTRAINT {{%chk-customer_po_line-line_total}} 
            CHECK (line_total >= 0)
        ");

        echo "Created customer_po_line table successfully.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop check constraints
        $this->execute("ALTER TABLE {{%customer_po_line}} DROP CONSTRAINT {{%chk-customer_po_line-qty}}");
        $this->execute("ALTER TABLE {{%customer_po_line}} DROP CONSTRAINT {{%chk-customer_po_line-price}}");
        $this->execute("ALTER TABLE {{%customer_po_line}} DROP CONSTRAINT {{%chk-customer_po_line-line_total}}");

        // Drop foreign keys
        $this->dropForeignKey('{{%fk-customer_po_line-po_id}}', '{{%customer_po_line}}');

        if ($this->checkTableExists('user')) {
            $this->dropForeignKey('{{%fk-customer_po_line-created_by}}', '{{%customer_po_line}}');
            $this->dropForeignKey('{{%fk-customer_po_line-updated_by}}', '{{%customer_po_line}}');
        }

        // Drop indexes
        $this->dropIndex('{{%idx-customer_po_line-po_id}}', '{{%customer_po_line}}');
        $this->dropIndex('{{%idx-customer_po_line-sort_order}}', '{{%customer_po_line}}');
        $this->dropIndex('{{%idx-customer_po_line-status}}', '{{%customer_po_line}}');

        // Drop table
        $this->dropTable('{{%customer_po_line}}');

        echo "Dropped customer_po_line table successfully.\n";
    }

    /**
     * Check if table exists
     */
    private function checkTableExists($tableName)
    {
        $tableSchema = $this->getDb()->getTableSchema("{{%{$tableName}}}");
        return $tableSchema !== null;
    }
}

/*
คำสั่งรัน Migration:
php yii migrate/create create_customer_po_line_table
php yii migrate
*/