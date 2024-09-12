<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Accident $model */

$this->title = 'แก้ไขบันทึกอุบัติเหตุ: ' . $model->case_no;
$this->params['breadcrumbs'][] = ['label' => 'บันทึกอุบัติเหตุ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->case_no, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'แก้ไข';
?>
<div class="accident-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
