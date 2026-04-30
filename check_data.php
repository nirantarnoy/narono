<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/common/config/bootstrap.php';
require __DIR__ . '/backend/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/common/config/main.php',
    require __DIR__ . '/common/config/main-local.php',
    require __DIR__ . '/backend/config/main.php',
    require __DIR__ . '/backend/config/main-local.php'
);

(new yii\web\Application($config));

$count_neg = (new \yii\db\Query())->from('work_queue')->where(['<', 'other_amt', 0])->count();
$count_pos = (new \yii\db\Query())->from('work_queue')->where(['>', 'other_amt', 0])->count();

echo "Negative: $count_neg, Positive: $count_pos\n";

$data = (new \yii\db\Query())->from('work_queue')->where(['<', 'other_amt', 0])->limit(5)->all();
foreach ($data as $row) {
    echo "ID: {$row['id']}, other_amt: {$row['other_amt']}\n";
}
