<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Customergroup $model */

$this->title = 'แก้ไขกลุ่มลูกกค้า: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'กลุ่มลูกค้า', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'แกเไข';
?>
<div class="customergroup-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
