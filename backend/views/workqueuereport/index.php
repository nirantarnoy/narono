<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WorkQueueReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customers array */

$this->title = 'รายงานคิวงาน (วันที่ 1-15)';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-queue-report-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <i class="glyphicon glyphicon-filter"></i> กรองข้อมูล
            </h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => ['class' => 'form-horizontal'],
            ]); ?>

            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($searchModel, 'start_date')->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => 'วันที่เริ่มต้น'],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                        ]
                    ])->label('วันที่เริ่มต้น') ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($searchModel, 'end_date')->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => 'วันที่สิ้นสุด'],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                        ]
                    ])->label('วันที่สิ้นสุด') ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($searchModel, 'customer_id')->dropDownList(
                        ArrayHelper::map($customers, 'id', function($model) {
                            return $model->code . ' - ' . $model->name;
                        }),
                        [
                            'prompt' => 'ทั้งหมด',
                            'class' => 'form-control'
                        ]
                    )->label('ลูกค้า') ?>
                </div>

                <div class="col-md-2">
                    <div class="form-group" style="margin-top: 25px;">
                        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> ค้นหา', [
                            'class' => 'btn btn-primary btn-block'
                        ]) ?>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="panel-title" style="margin-top: 7px;">
                        <i class="glyphicon glyphicon-list-alt"></i> รายการข้อมูล
                    </h3>
                </div>
                <div class="col-md-6 text-right">
                    <?= Html::a('<i class="glyphicon glyphicon-file"></i> ดูรายงาน',
                        ['report', 'WorkQueueReportSearch' => [
                            'start_date' => $searchModel->start_date,
                            'end_date' => $searchModel->end_date,
                            'customer_id' => $searchModel->customer_id,
                        ]],
                        ['class' => 'btn btn-info btn-sm', 'target' => '_blank']
                    ) ?>
                    <?= Html::a('<i class="glyphicon glyphicon-download-alt"></i> PDF',
                        ['pdf', 'WorkQueueReportSearch' => [
                            'start_date' => $searchModel->start_date,
                            'end_date' => $searchModel->end_date,
                            'customer_id' => $searchModel->customer_id,
                        ]],
                        ['class' => 'btn btn-danger btn-sm', 'target' => '_blank']
                    ) ?>
                    <?= Html::a('<i class="glyphicon glyphicon-download-alt"></i> Excel',
                        ['excel', 'WorkQueueReportSearch' => [
                            'start_date' => $searchModel->start_date,
                            'end_date' => $searchModel->end_date,
                            'customer_id' => $searchModel->customer_id,
                        ]],
                        ['class' => 'btn btn-success btn-sm']
                    ) ?>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-bordered'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'work_queue_date',
                        'label' => 'วันที่',
                        'format' => ['date', 'php:d/m/Y'],
                        'headerOptions' => ['style' => 'width: 120px; text-align: center;'],
                        'contentOptions' => ['style' => 'text-align: center;'],
                    ],
                    [
                        'attribute' => 'work_queue_no',
                        'label' => 'เลขที่คิว',
                        'headerOptions' => ['style' => 'width: 150px;'],
                    ],
                    [
                        'attribute' => 'customer_id',
                        'label' => 'ลูกค้า',
                        'value' => function($model) {
                            return $model->customer ? $model->customer->name : '-';
                        },
                    ],
//                    [
//                        'label' => 'จำนวน (ชิ้น)',
//                        'value' => function($model) {
//                            $total = 0;
//                            foreach ($model->workQueueDropoff as $dropoff) {
//                                $total += $dropoff->qty;
//                            }
//                            return $total;
//                        },
//                        'format' => 'integer',
//                        'headerOptions' => ['style' => 'width: 120px; text-align: center;'],
//                        'contentOptions' => ['style' => 'text-align: right;'],
//                    ],
//                    [
//                        'label' => 'น้ำหนัก (กก.)',
//                        'value' => function($model) {
//                            $total = 0;
//                            foreach ($model->workQueueDropoff as $dropoff) {
//                                $total += $dropoff->weight;
//                            }
//                            return $total;
//                        },
//                        'format' => ['decimal', 2],
//                        'headerOptions' => ['style' => 'width: 130px; text-align: center;'],
//                        'contentOptions' => ['style' => 'text-align: right;'],
//                    ],
//                    [
//                        'label' => 'ราคารวม (บาท)',
//                        'value' => function($model) {
//                            $total = 0;
//                            foreach ($model->workQueueDropoff as $dropoff) {
//                                $total += $dropoff->price_line_total;
//                            }
//                            return $total;
//                        },
//                        'format' => ['decimal', 2],
//                        'headerOptions' => ['style' => 'width: 150px; text-align: center;'],
//                        'contentOptions' => ['style' => 'text-align: right;'],
//                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'จัดการ',
                        'headerOptions' => ['style' => 'width: 80px; text-align: center;'],
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                                    ['/work-queue/view', 'id' => $model->id],
                                    ['title' => 'ดูรายละเอียด', 'class' => 'btn btn-sm btn-info']
                                );
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>

<style>
    .panel-heading {
        background-color: #f5f5f5;
    }
    .table thead th {
        background-color: #e8f4f8;
        font-weight: bold;
    }
</style>