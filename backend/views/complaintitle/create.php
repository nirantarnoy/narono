<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Complaintitle $model */

$this->title = 'สร้างหัวข้อร้องเรียน';
$this->params['breadcrumbs'][] = ['label' => 'หัวข้อร้องเรียน', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="complaintitle-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
