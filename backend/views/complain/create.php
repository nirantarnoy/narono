<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Complain $model */

$this->title = 'สร้างข้อร้องเรียน';
$this->params['breadcrumbs'][] = ['label' => 'ข้อร้องเรียน', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="complain-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
