<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ChartOfAccount */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'ผังบัญชี', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="chart-of-account-view">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center border-0">
             <h3 class="card-title text-primary font-weight-bold" style="margin-top: 10px;"><i class="fas fa-info-circle mr-1"></i> รายละเอียด: <?= Html::encode($this->title) ?></h3>
             <div class="card-tools ml-auto">
                 <?= Html::a('<i class="fas fa-edit mr-1"></i> แก้ไข', ['update', 'id' => $model->id], ['class' => 'btn btn-primary shadow-sm mr-1']) ?>
                 <?= Html::a('<i class="fas fa-trash-alt mr-1"></i> ลบ', ['delete', 'id' => $model->id], [
                     'class' => 'btn btn-danger shadow-sm',
                     'data' => [
                         'confirm' => 'คุณต้องการลบรายการนี้ใช่หรือไม่?',
                         'method' => 'post',
                     ],
                 ]) ?>
                 <?= Html::a('กลับหน้าหลัก', ['index'], ['class' => 'btn btn-outline-secondary shadow-sm ml-1']) ?>
             </div>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'account_code',
                    'sub_account_code',
                    'name',
                    'name_en',
                    [
                        'attribute' => 'status',
                        'format' => 'html',
                        'value' => $model->status == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>',
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' => $model->created_at ? date('d/m/Y H:i', $model->created_at) : '-',
                    ],
                    [
                        'attribute' => 'updated_at',
                        'value' => $model->updated_at ? date('d/m/Y H:i', $model->updated_at) : '-',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
