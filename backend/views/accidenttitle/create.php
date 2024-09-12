<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Accidenttitle $model */

$this->title = 'สร้างหัวข้ออุบัติเหตุ';
$this->params['breadcrumbs'][] = ['label' => 'หัวข้ออุบัติเหตุ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accidenttitle-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
