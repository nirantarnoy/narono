<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Alert $model */

$this->title = 'แก้ไข Alert: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Alerts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'แก้ไข';
?>
<div class="alert-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
