<?php
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../common/config/main.php'),
    require(__DIR__ . '/../common/config/main-local.php'),
    require(__DIR__ . '/../backend/config/main.php'),
    require(__DIR__ . '/../backend/config/main-local.php')
);
new yii\web\Application($config);
echo "DropoffPlace count: " . \common\models\DropoffPlace::find()->count() . "\n";
echo "WorkQueue count: " . \backend\models\Workqueue::find()->count() . "\n";
?>
