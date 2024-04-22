<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Location $model */

$this->title = 'แก้ไขข้อมูลสำนักงาน: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'สำนักงาน', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'แก้ไข';
?>
<div class="location-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
