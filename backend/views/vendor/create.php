<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Vendor $model */

$this->title = 'สร้างผู้ขาย';
$this->params['breadcrumbs'][] = ['label' => 'ผู้ขาย', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
