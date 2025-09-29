<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use backend\models\CustomerPo;
use backend\models\Customer;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var backend\models\CustomerPoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'จัดการ PO ลูกค้า';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="customer-po-index">

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Summary Cards -->
                <div class="row mb-3">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="total-po">-</h3>
                                <p>รวม PO ทั้งหมด</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="active-po">-</h3>
                                <p>PO ที่ดำเนินการ</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="total-amount">-</h3>
                                <p>มูลค่ารวม (บาท)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3 id="remaining-amount">-</h3>
                                <p>ยอดคงเหลือ (บาท)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-balance-scale"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                        <div class="card-tools">
                            <?= Html::a('<i class="fas fa-plus"></i> สร้าง PO ใหม่', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
                            <?= Html::a('<i class="fas fa-download"></i> Export Excel', ['export'] + Yii::$app->request->queryParams, ['class' => 'btn btn-info btn-sm']) ?>
                        </div>
                    </div>
                    <div class="card-body">

                        <?php Pjax::begin(['id' => 'po-grid-pjax']); ?>

                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'options' => ['class' => 'table-responsive'],
                            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                            'summary' => 'แสดง {begin}-{end} จากทั้งหมด {totalCount} รายการ',
                            'emptyText' => 'ไม่พบข้อมูล',
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                [
                                    'attribute' => 'po_number',
                                    'headerOptions' => ['style' => 'width: 120px;'],
                                    'contentOptions' => ['class' => 'text-nowrap'],
                                ],

                                [
                                    'attribute' => 'po_date',
                                    'headerOptions' => ['style' => 'width: 130px;'],
                                    'contentOptions' => ['class' => 'text-nowrap'],
                                    'filter' => DatePicker::widget([
                                        'model' => $searchModel,
                                        'attribute' => 'po_date',
                                        'options' => ['placeholder' => 'เลือกวันที่...'],
                                        'pluginOptions' => [
                                            'format' => 'yyyy-mm-dd',
                                            'todayHighlight' => true,
                                            'autoclose' => true,
                                        ],
                                    ]),
                                    'value' => function($model) {
                                        return Yii::$app->formatter->asDate($model->po_date);
                                    },
                                ],

                                [
                                    'attribute' => 'po_target_date',
                                    'label' => 'หมดอายุ',
                                    'headerOptions' => ['style' => 'width: 130px;'],
                                    'contentOptions' => ['class' => 'text-nowrap'],
                                    'filter' => DatePicker::widget([
                                        'model' => $searchModel,
                                        'attribute' => 'po_target_date',
                                        'options' => ['placeholder' => 'เลือกวันที่...'],
                                        'pluginOptions' => [
                                            'format' => 'yyyy-mm-dd',
                                            'todayHighlight' => true,
                                            'autoclose' => true,
                                        ],
                                    ]),
                                    'value' => function($model) {
                                        $date = Yii::$app->formatter->asDate($model->po_target_date);
                                        $isExpired = strtotime($model->po_target_date) < time() && $model->status === CustomerPo::STATUS_ACTIVE;
                                        return $isExpired ? '<span class="text-danger">' . $date . '</span>' : $date;
                                    },
                                    'format' => 'raw',
                                ],

                                [
                                    'attribute' => 'customer_name',
                                    'label' => 'ลูกค้า',
                                    'headerOptions' => ['style' => 'width: 150px;'],
                                    'filter' => Select2::widget([
                                        'model' => $searchModel,
                                        'attribute' => 'customer_name',
                                        'data' => ArrayHelper::map(Customer::find()->all(), 'name', 'name'),
                                        'options' => ['placeholder' => 'เลือกลูกค้า...'],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'escapeMarkup' => new \yii\web\JsExpression('function (markup) { return markup; }'),
                                        ],
                                    ]),
                                    'value' => function($model) {
                                        return $model->customer ? $model->customer->name : '-';
                                    },
                                ],

                                [
                                    'attribute' => 'work_name',
                                    'label' => 'งาน',
                                    'contentOptions' => ['style' => 'max-width: 200px; word-wrap: break-word;'],
                                    'value' => function($model) {
                                        return strlen($model->work_name) > 50 ?
                                            substr($model->work_name, 0, 50) . '...' :
                                            $model->work_name;
                                    },
                                ],

                                [
                                    'attribute' => 'po_amount',
                                    'label' => 'มูลค่างาน',
                                    'headerOptions' => ['style' => 'width: 120px;'],
                                    'contentOptions' => ['class' => 'text-right'],
                                    'value' => function($model) {
                                        return number_format($model->po_amount, 2);
                                    },
                                ],

                                [
                                    'attribute' => 'billed_amount',
                                    'label' => 'ยอดวางบิล',
                                    'headerOptions' => ['style' => 'width: 120px;'],
                                    'contentOptions' => ['class' => 'text-right'],
                                    'value' => function($model) {
                                        return number_format($model->billed_amount, 2);
                                    },
                                ],

                                [
                                    'attribute' => 'remaining_amount',
                                    'label' => 'คงเหลือ',
                                    'headerOptions' => ['style' => 'width: 120px;'],
                                    'contentOptions' => ['class' => 'text-right'],
                                    'value' => function($model) {
                                        $amount = number_format($model->remaining_amount, 2);
                                        if ($model->remaining_amount < 0) {
                                            return '<span class="text-danger">' . $amount . '</span>';
                                        } elseif ($model->remaining_amount == 0) {
                                            return '<span class="text-success">' . $amount . '</span>';
                                        }
                                        return $amount;
                                    },
                                    'format' => 'raw',
                                ],

                                [
                                    'attribute' => 'status',
                                    'headerOptions' => ['style' => 'width: 100px;'],
                                    'filter' => CustomerPo::getStatusOptions(),
                                    'value' => function($model) {
                                        return $model->getStatusBadge();
                                    },
                                    'format' => 'raw',
                                ],

                                [
                                    'attribute' => 'po_file',
                                    'label' => 'ไฟล์',
                                    'headerOptions' => ['style' => 'width: 60px;'],
                                    'contentOptions' => ['class' => 'text-center'],
                                    'filter' => false,
                                    'value' => function($model) {
                                        if ($model->po_file) {
                                            return Html::a('<i class="fas fa-download"></i>', ['download', 'id' => $model->id], [
                                                'class' => 'btn btn-sm btn-outline-primary',
                                                'title' => 'ดาวน์โหลดไฟล์',
                                                'data-pjax' => '0',
                                            ]);
                                        }
                                        return '-';
                                    },
                                    'format' => 'raw',
                                ],

                                [
                                    'class' => ActionColumn::className(),
                                    'headerOptions' => ['style' => 'width: 120px;'],
                                    'contentOptions' => ['class' => 'text-center'],
                                    'template' => '{view} {update} {delete}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {
                                            return Html::a('<i class="fas fa-eye"></i>', $url, [
                                                'class' => 'btn btn-sm btn-outline-info',
                                                'title' => 'ดูรายละเอียด',
                                            ]);
                                        },
                                        'update' => function ($url, $model, $key) {
                                            return Html::a('<i class="fas fa-edit"></i>', $url, [
                                                'class' => 'btn btn-sm btn-outline-warning',
                                                'title' => 'แก้ไข',
                                            ]);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                            return Html::a('<i class="fas fa-trash"></i>', $url, [
                                                'class' => 'btn btn-sm btn-outline-danger',
                                                'title' => 'ลบ',
                                                'data' => [
                                                    'confirm' => 'คุณแน่ใจหรือไม่ที่จะลบรายการนี้?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        },
                                    ],
                                    'urlCreator' => function ($action, CustomerPo $model, $key, $index, $column) {
                                        return Url::toRoute([$action, 'id' => $model->id]);
                                    }
                                ],
                            ],
                        ]); ?>

                        <?php Pjax::end(); ?>

                    </div>
                </div>

            </div>
        </section>

    </div>

<?php
$this->registerJs("
// Load statistics
function loadStats() {
    $.get('" . Url::to(['stats']) . "', function(data) {
        var stats = JSON.parse(data);
        $('#total-po').text(stats.total_po);
        $('#active-po').text(stats.active_po);
        $('#total-amount').text(Number(stats.total_amount).toLocaleString('th-TH', {minimumFractionDigits: 0}));
        $('#remaining-amount').text(Number(stats.remaining_amount).toLocaleString('th-TH', {minimumFractionDigits: 0}));
    });
}

// Load stats on page load
loadStats();

// Reload stats after pjax update
$(document).on('pjax:complete', function() {
    loadStats();
});
");
?>