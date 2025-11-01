<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Quotationtitle $model */

$this->title = 'แก้ไขเสนอราคา: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'เสนอราคา', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="quotationtitle-update">
    <?= $this->render('_form', [
        'model' => $model,
        'model_line'=>$model_line,
        'last_month_price'=>null,
    ]) ?>

</div>
