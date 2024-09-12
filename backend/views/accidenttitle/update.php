<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Accidenttitle $model */

$this->title = 'แก้ไขหัวข้ออุบัติเหตุ: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'หัวข้ออุบัติเหตุ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'แก้ไข';
?>
<div class="accidenttitle-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
