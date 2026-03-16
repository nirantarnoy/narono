<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);

(new yii\web\Application($config));

try {
    // 1. ลองเพิ่มคอลัมน์ account_id เข้าไปตรงๆ
    Yii::$app->db->createCommand("ALTER TABLE fixcost_title ADD COLUMN account_id INT NULL")->execute();
    echo "Successfully added 'account_id' column to 'fixcost_title' table.\n";
} catch (\Exception $e) {
    echo "Error or Already exists: " . $e->getMessage() . "\n";
}

try {
    // 2. ล้าง Cache ของ Schema
    Yii::$app->db->getSchema()->refresh();
    echo "Database schema cache refreshed.\n";
} catch (\Exception $e) {
    echo "Cache refresh error: " . $e->getMessage() . "\n";
}
?>
