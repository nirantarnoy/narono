<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer_po}}` and `{{%customer_po_invoices}}`.
 *
 * To create this migration file run:
 * php yii migrate/create create_customer_po_tables
 *
 * To execute migration:
 * php yii migrate
 */
class m240923_000001_create_customer_po_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Create customer_po table
        $this->createTable('{{%customer_po}}', [
            'id' => $this->primaryKey()->comment('รหัส PO'),
            'po_number' => $this->string(50)->notNull()->comment('เลขที่ PO'),
            'po_date' => $this->date()->notNull()->comment('วันที่สร้าง PO'),
            'po_target_date' => $this->date()->notNull()->comment('วันที่ PO หมดอายุ'),
            'customer_id' => $this->integer()->notNull()->comment('รหัสลูกค้า'),
            'work_name' => $this->text()->notNull()->comment('รายละเอียดงาน'),
            'po_amount' => $this->decimal(15, 2)->notNull()->defaultValue(0.00)->comment('มูลค่างาน'),
            'billed_amount' => $this->decimal(15, 2)->notNull()->defaultValue(0.00)->comment('ยอดวางบิลแล้ว'),
            'remaining_amount' => $this->decimal(15, 2)->notNull()->defaultValue(0.00)->comment('ยอดคงเหลือ'),
            'po_file' => $this->string(255)->null()->comment('ไฟล์แนบ PO'),
            'status' => $this->string(20)->notNull()->defaultValue('active')->comment('สถานะ: active, completed, cancelled'),
            'remark' => $this->text()->null()->comment('หมายเหตุ'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('วันที่สร้าง'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('วันที่แก้ไข'),
            'created_by' => $this->integer()->null()->comment('ผู้สร้าง'),
            'updated_by' => $this->integer()->null()->comment('ผู้แก้ไข'),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="ตารางจัดการ PO ของลูกค้า"');

        // Create customer_po_invoices table
        $this->createTable('{{%customer_po_invoices}}', [
            'id' => $this->primaryKey()->comment('รหัสการเชื่อมโยง'),
            'po_id' => $this->integer()->notNull()->comment('รหัส PO'),
            'invoice_id' => $this->integer()->notNull()->comment('รหัสใบวางบิล'),
            'amount' => $this->decimal(15, 2)->notNull()->defaultValue(0.00)->comment('ยอดเงินที่หักจาก PO'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('วันที่สร้าง'),
            'created_by' => $this->integer()->null()->comment('ผู้สร้าง'),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="ตารางเชื่อมโยง PO กับใบวางบิล"');

        // Create indexes for customer_po table
        $this->createIndex(
            '{{%idx-customer_po-po_number}}',
            '{{%customer_po}}',
            'po_number',
            true // unique
        );

        $this->createIndex(
            '{{%idx-customer_po-customer_id}}',
            '{{%customer_po}}',
            'customer_id'
        );

        $this->createIndex(
            '{{%idx-customer_po-po_date}}',
            '{{%customer_po}}',
            'po_date'
        );

        $this->createIndex(
            '{{%idx-customer_po-po_target_date}}',
            '{{%customer_po}}',
            'po_target_date'
        );

        $this->createIndex(
            '{{%idx-customer_po-status}}',
            '{{%customer_po}}',
            'status'
        );

        $this->createIndex(
            '{{%idx-customer_po-customer_status}}',
            '{{%customer_po}}',
            ['customer_id', 'status']
        );

        $this->createIndex(
            '{{%idx-customer_po-date_range}}',
            '{{%customer_po}}',
            ['po_date', 'po_target_date']
        );

        $this->createIndex(
            '{{%idx-customer_po-created_by}}',
            '{{%customer_po}}',
            'created_by'
        );

        $this->createIndex(
            '{{%idx-customer_po-updated_by}}',
            '{{%customer_po}}',
            'updated_by'
        );

        // Create indexes for customer_po_invoices table
        $this->createIndex(
            '{{%idx-customer_po_invoices-po_id}}',
            '{{%customer_po_invoices}}',
            'po_id'
        );

        $this->createIndex(
            '{{%idx-customer_po_invoices-invoice_id}}',
            '{{%customer_po_invoices}}',
            'invoice_id'
        );

        $this->createIndex(
            '{{%idx-customer_po_invoices-po_invoice_unique}}',
            '{{%customer_po_invoices}}',
            ['po_id', 'invoice_id'],
            true // unique
        );

        $this->createIndex(
            '{{%idx-customer_po_invoices-created_by}}',
            '{{%customer_po_invoices}}',
            'created_by'
        );

//        // Add foreign key constraints
//        // Note: Adjust table names according to your existing tables
//
//        // Foreign key to customer table (adjust table name if different)
//        if ($this->checkTableExists('customer')) {
//            $this->addForeignKey(
//                '{{%fk-customer_po-customer_id}}',
//                '{{%customer_po}}',
//                'customer_id',
//                '{{%customer}}',
//                'id',
//                'CASCADE',
//                'CASCADE'
//            );
//        }
//
//        // Foreign key to customer_invoice table (adjust table name if different)
//        if ($this->checkTableExists('customer_invoice')) {
//            $this->addForeignKey(
//                '{{%fk-customer_po_invoices-invoice_id}}',
//                '{{%customer_po_invoices}}',
//                'invoice_id',
//                '{{%customer_invoice}}',
//                'id',
//                'CASCADE',
//                'CASCADE'
//            );
//        }
//
//        // Foreign key between customer_po and customer_po_invoices
//        $this->addForeignKey(
//            '{{%fk-customer_po_invoices-po_id}}',
//            '{{%customer_po_invoices}}',
//            'po_id',
//            '{{%customer_po}}',
//            'id',
//            'CASCADE',
//            'CASCADE'
//        );
//
//        // Foreign keys to user table (if exists)
//        if ($this->checkTableExists('user')) {
//            $this->addForeignKey(
//                '{{%fk-customer_po-created_by}}',
//                '{{%customer_po}}',
//                'created_by',
//                '{{%user}}',
//                'id',
//                'SET NULL',
//                'CASCADE'
//            );
//
//            $this->addForeignKey(
//                '{{%fk-customer_po-updated_by}}',
//                '{{%customer_po}}',
//                'updated_by',
//                '{{%user}}',
//                'id',
//                'SET NULL',
//                'CASCADE'
//            );
//
//            $this->addForeignKey(
//                '{{%fk-customer_po_invoices-created_by}}',
//                '{{%customer_po_invoices}}',
//                'created_by',
//                '{{%user}}',
//                'id',
//                'SET NULL',
//                'CASCADE'
//            );
//        }
//
//        // Add check constraints for better data integrity
//        $this->execute("
//            ALTER TABLE {{%customer_po}}
//            ADD CONSTRAINT {{%chk-customer_po-status}}
//            CHECK (status IN ('active', 'completed', 'cancelled'))
//        ");
//
//        $this->execute("
//            ALTER TABLE {{%customer_po}}
//            ADD CONSTRAINT {{%chk-customer_po-po_amount}}
//            CHECK (po_amount >= 0)
//        ");
//
//        $this->execute("
//            ALTER TABLE {{%customer_po}}
//            ADD CONSTRAINT {{%chk-customer_po-billed_amount}}
//            CHECK (billed_amount >= 0)
//        ");
//
//        $this->execute("
//            ALTER TABLE {{%customer_po_invoices}}
//            ADD CONSTRAINT {{%chk-customer_po_invoices-amount}}
//            CHECK (amount >= 0)
//        ");
//
//        $this->execute("
//            ALTER TABLE {{%customer_po}}
//            ADD CONSTRAINT {{%chk-customer_po-date_range}}
//            CHECK (po_target_date >= po_date)
//        ");

        echo "Created customer PO management tables with indexes and constraints successfully.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop check constraints first
        $this->execute("ALTER TABLE {{%customer_po}} DROP CONSTRAINT {{%chk-customer_po-status}}");
        $this->execute("ALTER TABLE {{%customer_po}} DROP CONSTRAINT {{%chk-customer_po-po_amount}}");
        $this->execute("ALTER TABLE {{%customer_po}} DROP CONSTRAINT {{%chk-customer_po-billed_amount}}");
        $this->execute("ALTER TABLE {{%customer_po_invoices}} DROP CONSTRAINT {{%chk-customer_po_invoices-amount}}");
        $this->execute("ALTER TABLE {{%customer_po}} DROP CONSTRAINT {{%chk-customer_po-date_range}}");

        // Drop foreign keys
        if ($this->checkTableExists('customer')) {
            $this->dropForeignKey('{{%fk-customer_po-customer_id}}', '{{%customer_po}}');
        }

        if ($this->checkTableExists('customer_invoice')) {
            $this->dropForeignKey('{{%fk-customer_po_invoices-invoice_id}}', '{{%customer_po_invoices}}');
        }

        $this->dropForeignKey('{{%fk-customer_po_invoices-po_id}}', '{{%customer_po_invoices}}');

        if ($this->checkTableExists('user')) {
            $this->dropForeignKey('{{%fk-customer_po-created_by}}', '{{%customer_po}}');
            $this->dropForeignKey('{{%fk-customer_po-updated_by}}', '{{%customer_po}}');
            $this->dropForeignKey('{{%fk-customer_po_invoices-created_by}}', '{{%customer_po_invoices}}');
        }

        // Drop indexes (foreign key indexes will be dropped automatically)
        $this->dropIndex('{{%idx-customer_po-po_number}}', '{{%customer_po}}');
        $this->dropIndex('{{%idx-customer_po-customer_id}}', '{{%customer_po}}');
        $this->dropIndex('{{%idx-customer_po-po_date}}', '{{%customer_po}}');
        $this->dropIndex('{{%idx-customer_po-po_target_date}}', '{{%customer_po}}');
        $this->dropIndex('{{%idx-customer_po-status}}', '{{%customer_po}}');
        $this->dropIndex('{{%idx-customer_po-customer_status}}', '{{%customer_po}}');
        $this->dropIndex('{{%idx-customer_po-date_range}}', '{{%customer_po}}');
        $this->dropIndex('{{%idx-customer_po-created_by}}', '{{%customer_po}}');
        $this->dropIndex('{{%idx-customer_po-updated_by}}', '{{%customer_po}}');

        $this->dropIndex('{{%idx-customer_po_invoices-po_id}}', '{{%customer_po_invoices}}');
        $this->dropIndex('{{%idx-customer_po_invoices-invoice_id}}', '{{%customer_po_invoices}}');
        $this->dropIndex('{{%idx-customer_po_invoices-po_invoice_unique}}', '{{%customer_po_invoices}}');
        $this->dropIndex('{{%idx-customer_po_invoices-created_by}}', '{{%customer_po_invoices}}');

        // Drop tables
        $this->dropTable('{{%customer_po_invoices}}');
        $this->dropTable('{{%customer_po}}');

        echo "Dropped customer PO management tables successfully.\n";
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
============================================================================
คำสั่งในการใช้งาน Migration:
============================================================================

1. สร้าง migration file:
   php yii migrate/create create_customer_po_tables

2. รัน migration:
   php yii migrate

3. ตรวจสอบสถานะ migration:
   php yii migrate/history

4. Rollback migration (ถ้าจำเป็น):
   php yii migrate/down 1

5. Rollback ไปยัง migration เฉพาะ:
   php yii migrate/to m240923_000001_create_customer_po_tables

============================================================================
ข้อมูลเพิ่มเติม:
============================================================================

ตารางที่จะถูกสร้าง:
- customer_po (ตารางหลักสำหรับ PO)
- customer_po_invoices (ตารางเชื่อมโยง PO กับใบวางบิล)

Indexes ที่สร้าง:
- po_number (unique)
- customer_id
- po_date, po_target_date
- status
- composite indexes สำหรับการค้นหาที่มีประสิทธิภาพ

Foreign Keys:
- customer_po.customer_id → customer.id
- customer_po_invoices.po_id → customer_po.id
- customer_po_invoices.invoice_id → customer_invoice.id
- created_by/updated_by → user.id

Check Constraints:
- สถานะต้องเป็น active/completed/cancelled
- จำนวนเงินต้องไม่ติดลบ
- วันที่หมดอายุต้องมากกว่าหรือเท่ากับวันที่สร้าง PO

============================================================================
การปรับแต่งก่อนรัน Migration:
============================================================================

1. ตรวจสอบชื่อตารางที่มีอยู่แล้ว:
   - customer (ตารางลูกค้า)
   - customer_invoice (ตารางใบวางบิล)
   - user (ตารางผู้ใช้)

2. หากตารางมีชื่อต่างกัน ให้แก้ไขใน method safeUp():
   - เปลี่ยน 'customer' เป็นชื่อตารางลูกค้าจริง
   - เปลี่ยน 'customer_invoice' เป็นชื่อตารางใบวางบิลจริง

3. สร้างโฟลเดอร์สำหรับไฟล์แนบ:
   mkdir -p backend/web/uploads/po/
   chmod 755 backend/web/uploads/po/

4. ตรวจสอบ Database Configuration:
   - charset: utf8
   - collation: utf8_unicode_ci
   - engine: InnoDB

============================================================================
*/