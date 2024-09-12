<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Alert $model */

$this->title = 'สร้าง Alert';
$this->params['breadcrumbs'][] = ['label' => 'Alerts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alert-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
