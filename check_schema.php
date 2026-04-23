<?php
require 'vendor/autoload.php';
require 'common/config/bootstrap.php';
require 'backend/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require 'common/config/main.php',
    require 'common/config/main-local.php',
    require 'backend/config/main.php',
    require 'backend/config/main-local.php'
);

(new yii\web\Application($config));

$table = Yii::$app->db->getTableSchema('query_car_work_summary');
if ($table) {
    echo "Table: query_car_work_summary\n";
    foreach ($table->columns as $column) {
        echo "- {$column->name} ({$column->type})\n";
    }
} else {
    echo "Table not found\n";
}
