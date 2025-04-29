<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Customer $model */

$this->title = 'แก้ไขลูกค้า: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'ลูกค้า', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'แก้ไข';
?>
<div class="customer-update">



    <?= $this->render('_form', [
        'model' => $model,
        'model_line'=>$model_line,
        'model_contact_line'=>$model_contact_line,
        'model_customer_tax_info' => $model_customer_tax_info,
        'model_user_group_list' => $model_user_group_list,
        '$model_customer_invoice_child' => $model_customer_invoice_child,
    ]) ?>

</div>
