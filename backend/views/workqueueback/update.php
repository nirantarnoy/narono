<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Workqueue $model */

$this->title = 'แก้ไขคจัดคิวงาน(ของกลับ): ' . $model->work_queue_no;
$this->params['breadcrumbs'][] = ['label' => 'จัดคิวงาน(ของกลับ)', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->work_queue_no, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'แก้ไข';
?>
<div class="workqueue-update">


    <?= $this->render('_form', [
        'model' => $model,
        'model_line_doc' => $model_line_doc,
        'w_dropoff' => $w_dropoff,
        'w_itemback' => $w_itemback,
    ]) ?>

</div>
