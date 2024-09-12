<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Complaintitle $model */

$this->title = 'แก้ไขหัวข้อร้องเรียน: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'หัวข้อร้องเรียน', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'แก้ไข';
?>
<div class="complaintitle-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
