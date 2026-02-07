<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Quotationtitle $model */

$this->title = 'สร้างเสนอราคา';
$this->params['breadcrumbs'][] = ['label' => 'เสนอราคา', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quotationtitle-create">

    <?= $this->render('_form', [
        'model' => $model,
        'model_line'=>null,
    ]) ?>

</div>
