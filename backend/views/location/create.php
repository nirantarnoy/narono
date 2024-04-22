<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Location $model */

$this->title = 'สร้างข้อมูลสำนักงาน';
$this->params['breadcrumbs'][] = ['label' => 'สำนักงาน', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
