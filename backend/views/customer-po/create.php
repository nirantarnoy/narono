<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\CustomerPo $model */

$this->title = 'สร้าง PO ใหม่';
$this->params['breadcrumbs'][] = ['label' => 'จัดการ PO ลูกค้า', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-po-create">
    <section class="content">
        <div class="container-fluid">
            <?= $this->render('_form', [
                'model' => $model,
                'modelsLine' => $modelsLine,
            ]) ?>
        </div>
    </section>

</div>