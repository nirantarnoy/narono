<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Employeefine $model */

$this->title = 'บันทึกค่าปรับ';
$this->params['breadcrumbs'][] = ['label' => 'ค่าปรับ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employeefine-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
