<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Accident $model */

$this->title = 'บันทึกอุบัติเหตุ';
$this->params['breadcrumbs'][] = ['label' => 'อุบัติเหตุ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accident-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
