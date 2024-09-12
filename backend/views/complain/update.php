<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Complain $model */

$this->title = 'แก้ไขข้อร้องเรียน: ' . $model->case_no;
$this->params['breadcrumbs'][] = ['label' => 'ข้อร้องเรียน', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->case_no, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'แก้ไข';
?>
<div class="complain-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
