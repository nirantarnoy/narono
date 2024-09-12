<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Employeefine $model */

$this->title = 'แก้ไขบันทึกค่าปรับ: ' . $model->case_no;
$this->params['breadcrumbs'][] = ['label' => 'บันทึกค่าปรับ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->case_no, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'แก้ไข';
?>
<div class="employeefine-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
