<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

// เปิดใช้งาน Action Log บันทึกประวัติการ เพิ่ม/แก้ไข/ลบ ข้อมูลแบบละเอียดอัตโนมัติ
\common\components\ActionLogBehavior::attach();

