<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\bootstrap4\Modal;

/** @var yii\web\View $this */
/** @var backend\models\CustomerPo $model */
/** @var backend\models\CustomerInvoice[] $linkedInvoices */

$this->title = 'PO: ' . $model->po_number;
$this->params['breadcrumbs'][] = ['label' => 'จัดการ PO ลูกค้า', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
    <div class="customer-po-view">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                                <div class="card-tools">
                                    <?= Html::a('<i class="fas fa-edit"></i> แก้ไข', ['update', 'id' => $model->id], ['class' => 'btn btn-warning btn-sm']) ?>
                                    <?= Html::a('<i class="fas fa-link"></i> เชื่อมโยงใบวางบิล', '#', [
                                        'class' => 'btn btn-info btn-sm',
                                        'data-toggle' => 'modal',
                                        'data-target' => '#link-invoice-modal'
                                    ]) ?>
                                    <?= Html::a('<i class="fas fa-arrow-left"></i> กลับ', ['index'], ['class' => 'btn btn-secondary btn-sm']) ?>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <!-- PO Details -->
                                    <div class="col-md-8">
                                        <?= DetailView::widget([
                                            'model' => $model,
                                            'options' => ['class' => 'table table-striped detail-view'],
                                            'attributes' => [
                                                'po_number',
                                                [
                                                    'attribute' => 'po_date',
                                                    'value' => Yii::$app->formatter->asDate($model->po_date),
                                                ],
                                                [
                                                    'attribute' => 'po_target_date',
                                                    'value' => function($model) {
                                                        $date = Yii::$app->formatter->asDate($model->po_target_date);
                                                        $isExpired = strtotime($model->po_target_date) < time() && $model->status === \backend\models\CustomerPo::STATUS_ACTIVE;
                                                        return $isExpired ? '<span class="text-danger"><strong>' . $date . ' (หมดอายุแล้ว)</strong></span>' : $date;
                                                    },
                                                    'format' => 'raw',
                                                ],
                                                [
                                                    'attribute' => 'customer_id',
                                                    'label' => 'ลูกค้า',
                                                    'value' => $model->customer ? $model->customer->name : '-',
                                                ],
                                                [
                                                    'attribute' => 'work_name',
                                                    'label' => 'งาน',
                                                    'format' => 'ntext',
                                                ],
                                                [
                                                    'attribute' => 'status',
                                                    'value' => $model->getStatusBadge(),
                                                    'format' => 'raw',
                                                ],
                                                [
                                                    'attribute' => 'remark',
                                                    'value' => $model->remark ?: '-',
                                                    'format' => 'ntext',
                                                ],
                                                [
                                                    'attribute' => 'po_file',
                                                    'label' => 'ไฟล์แนบ',
                                                    'value' => function($model) {
                                                        if ($model->po_file) {
                                                            return Html::a('<i class="fas fa-download"></i> ' . $model->po_file, ['download', 'id' => $model->id], [
                                                                'class' => 'btn btn-outline-primary btn-sm',
                                                                'target' => '_blank'
                                                            ]);
                                                        }
                                                        return '-';
                                                    },
                                                    'format' => 'raw',
                                                ],
                                                [
                                                    'attribute' => 'created_at',
                                                    'value' => Yii::$app->formatter->asDatetime($model->created_at),
                                                ],
                                                [
                                                    'attribute' => 'updated_at',
                                                    'value' => Yii::$app->formatter->asDatetime($model->updated_at),
                                                ],
                                            ],
                                        ]) ?>
                                    </div>

                                    <!-- Summary Cards -->
                                    <div class="col-md-4">
                                        <div class="card bg-info">
                                            <div class="card-body text-center text-white">
                                                <h3><?= number_format($model->po_amount, 2) ?></h3>
                                                <p class="mb-0">มูลค่างาน (บาท)</p>
                                            </div>
                                        </div>

                                        <div class="card bg-warning mt-2">
                                            <div class="card-body text-center text-white">
                                                <h3><?= number_format($model->billed_amount, 2) ?></h3>
                                                <p class="mb-0">ยอดวางบิลแล้ว (บาท)</p>
                                            </div>
                                        </div>

                                        <div class="card <?= $model->remaining_amount > 0 ? 'bg-success' : ($model->remaining_amount < 0 ? 'bg-danger' : 'bg-secondary') ?> mt-2">
                                            <div class="card-body text-center text-white">
                                                <h3><?= number_format($model->remaining_amount, 2) ?></h3>
                                                <p class="mb-0">คงเหลือ (บาท)</p>
                                            </div>
                                        </div>

                                        <?php if ($model->remaining_amount < 0): ?>
                                            <div class="alert alert-danger mt-2">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <strong>เกินมูลค่า PO!</strong><br>
                                                ยอดวางบิลเกินกว่ามูลค่างาน
                                            </div>
                                        <?php elseif ($model->remaining_amount == 0): ?>
                                            <div class="alert alert-success mt-2">
                                                <i class="fas fa-check-circle"></i>
                                                <strong>วางบิลครบแล้ว!</strong><br>
                                                PO นี้วางบิลครบตามมูลค่าแล้ว
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Linked Invoices -->
                <?php if (!empty($linkedInvoices)): ?>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">ใบวางบิลที่เชื่อมโยง</h3>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $invoiceDataProvider = new ArrayDataProvider([
                                        'allModels' => $linkedInvoices,
                                        'pagination' => false,
                                    ]);
                                    ?>

                                    <?= GridView::widget([
                                        'dataProvider' => $invoiceDataProvider,
                                        'tableOptions' => ['class' => 'table table-striped table-bordered'],
                                        'summary' => false,
                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],

                                            [
                                                'attribute' => 'invoice_no',
                                                'label' => 'เลขที่ใบวางบิล',
                                            ],

                                            [
                                                'attribute' => 'invoice_date',
                                                'label' => 'วันที่ใบวางบิล',
                                                'value' => function($model) {
                                                    return Yii::$app->formatter->asDate($model->invoice_date);
                                                },
                                            ],

                                            [
                                                'attribute' => 'total_amount',
                                                'label' => 'มูลค่าใบวางบิล',
                                                'contentOptions' => ['class' => 'text-right'],
                                                'value' => function($model) {
                                                    return number_format($model->total_amount, 2);
                                                },
                                            ],

                                            [
                                                'label' => 'จำนวนที่หักจาก PO',
                                                'contentOptions' => ['class' => 'text-right'],
                                                'value' => function($model){
                                                    $poInvoice = \backend\models\CustomerPoInvoice::findOne([
                                                        'po_id' => $model->id,
                                                        'invoice_id' => $model->id
                                                    ]);
                                                    return $poInvoice ? number_format($poInvoice->amount, 2) : '0.00';
                                                },
                                            ],

                                            [
                                                'class' => 'yii\grid\ActionColumn',
                                                'template' => '{unlink}',
                                                'headerOptions' => ['style' => 'width: 80px;'],
                                                'contentOptions' => ['class' => 'text-center'],
                                                'buttons' => [
                                                    'unlink' => function ($url, $model, $key) {
                                                        return Html::a('<i class="fas fa-unlink"></i>',
                                                            ['unlink-invoice', 'id' => $model->id, 'invoiceId' => $model->id], [
                                                                'class' => 'btn btn-sm btn-outline-danger',
                                                                'title' => 'ยกเลิกการเชื่อมโยง',
                                                                'data-confirm' => 'คุณแน่ใจหรือไม่ที่จะยกเลิกการเชื่อมโยงใบวางบิลนี้?',
                                                                'data-method' => 'post',
                                                            ]);
                                                    },
                                                ],
                                            ],
                                        ],
                                    ]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </section>

    </div>

    <!-- Link Invoice Modal -->
<?php
Modal::begin([
    'id' => 'link-invoice-modal',
    'title' => 'เชื่อมโยงใบวางบิล',
    'size' => 'modal-lg',
]);
?>
    <div id="link-invoice-content">
        <!-- Content will be loaded via AJAX -->
    </div>
<?php Modal::end(); ?>

<?php
$this->registerJs("
// Load link invoice form via AJAX
$('#link-invoice-modal').on('show.bs.modal', function() {
    $('#link-invoice-content').html('<div class=\"text-center\"><i class=\"fas fa-spinner fa-spin\"></i> กำลังโหลด...</div>');
    
    $.get('" . \yii\helpers\Url::to(['link-invoice', 'id' => $model->id]) . "', function(data) {
        $('#link-invoice-content').html(data);
    }).fail(function() {
        $('#link-invoice-content').html('<div class=\"alert alert-danger\">เกิดข้อผิดพลาดในการโหลดข้อมูล</div>');
    });
});

// Handle link invoice form submission
$(document).on('submit', '#link-invoice-form', function(e) {
    e.preventDefault();
    
    var formData = $(this).serialize();
    
    $.post($(this).attr('action'), formData, function(response) {
        var result = JSON.parse(response);
        
        if (result.success) {
            $('#link-invoice-modal').modal('hide');
            location.reload(); // Refresh page to show updated data
        } else {
            alert(result.message);
        }
    }).fail(function() {
        alert('เกิดข้อผิดพลาดในการส่งข้อมูล');
    });
});
");
?>