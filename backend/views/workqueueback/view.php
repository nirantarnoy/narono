<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Workqueue $model */

$this->title = $model->work_queue_no;
$this->params['breadcrumbs'][] = ['label' => 'จัดคิวงาน(ของกลับ)', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="workqueue-view">


    <p>
        <?= $model->status != 100 ?Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']):'' ?>
        <?= $model->status != 100 ?Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]):'' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'work_queue_no',
            'work_queue_date',
//            'customer_id',
//            'emp_assign',
            [
                'attribute' => 'customer_id',
                'value' => function ($data) {
                    return \backend\models\Customer::findCusName($data->customer_id);
                }
            ],
//            'emp_assign',
            [
                'attribute' => 'emp_assign',
                'value' => function ($data) {
                    return \backend\models\Employee::findFullName($data->emp_assign);
                }
            ],
            'dp_no',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($data) {
                    if ($data->status == 1) {
                        return '<div class="badge badge-success">ใช้งาน</div>';
                    } else {
                        return '<div class="badge badge-secondary">ไม่ใช้งาน</div>';
                    }
                }
            ],
//            'create_at',
//            'created_by',
//            'updated_at',
//            'updated_by',
        ],
    ]) ?>

</div>
