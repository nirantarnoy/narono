<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TaxImportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model backend\models\TaxImport */

$this->title = 'จัดการข้อมูลภาษี (Tax Import)';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tax-import-index">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-edit"></i> กรอกข้อมูล</h3>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'tax-import-form',
                        'options' => ['class' => 'row'],
                    ]); ?>

                    <?= Html::hiddenInput('TaxImport[id]', '', ['id' => 'taximport-id']) ?>

                    <div class="col-md-2">
                        <?= $form->field($model, 'sequence')->textInput(['id' => 'taximport-sequence', 'type' => 'number']) ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'doc_date')->textInput(['id' => 'taximport-doc_date', 'placeholder' => 'YYYYMMDD']) ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'reference_no')->textInput(['id' => 'taximport-reference_no']) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'vendor_name')->textInput(['id' => 'taximport-vendor_name']) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'tax_id')->textInput(['id' => 'taximport-tax_id', 'maxlength' => 13]) ?>
                    </div>

                    <div class="col-md-2">
                        <?= $form->field($model, 'branch_code')->textInput(['id' => 'taximport-branch_code', 'maxlength' => 5]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'tax_invoice_no')->textInput(['id' => 'taximport-tax_invoice_no']) ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'tax_invoice_date')->textInput(['id' => 'taximport-tax_invoice_date', 'placeholder' => 'YYYYMMDD']) ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'tax_record_date')->textInput(['id' => 'taximport-tax_record_date', 'placeholder' => 'YYYYMMDD']) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'price_type')->dropDownList([
                            1 => '1 = แยกภาษี',
                            2 => '2 = รวมภาษี',
                            3 => '3 = ไม่มีภาษี'
                        ], ['id' => 'taximport-price_type']) ?>
                    </div>

                    <div class="col-md-2">
                        <?= $form->field($model, 'account_code')->textInput(['id' => 'taximport-account_code']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'description')->textInput(['id' => 'taximport-description']) ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'qty')->textInput(['id' => 'taximport-qty', 'type' => 'number', 'step' => '0.0001']) ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'unit_price')->textInput(['id' => 'taximport-unit_price', 'type' => 'number', 'step' => '0.0001']) ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'tax_rate')->textInput(['id' => 'taximport-tax_rate']) ?>
                    </div>

                    <div class="col-md-2">
                        <?= $form->field($model, 'wht_amount')->textInput(['id' => 'taximport-wht_amount']) ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'paid_by')->textInput(['id' => 'taximport-paid_by']) ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'paid_amount')->textInput(['id' => 'taximport-paid_amount', 'type' => 'number', 'step' => '0.01']) ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'pnd_type')->textInput(['id' => 'taximport-pnd_type']) ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'group_type')->textInput(['id' => 'taximport-group_type']) ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'remarks')->textInput(['id' => 'taximport-remarks']) ?>
                    </div>

                    <div class="col-md-12 text-right mt-3">
                        <?= Html::button('<i class="fas fa-plus-circle"></i> Add / Save', ['class' => 'btn btn-success btn-lg shadow-sm', 'id' => 'btn-save-tax']) ?>
                        <?= Html::button('<i class="fas fa-redo"></i> Clear', ['class' => 'btn btn-secondary btn-lg shadow-sm', 'id' => 'btn-clear-tax']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-table"></i> รายการข้อมูล</h3>
                    <div class="card-tools ml-auto">
                        <?= Html::button('<i class="fas fa-file-import"></i> Import Excel', [
                            'class' => 'btn btn-outline-primary shadow-sm mr-2',
                            'data-toggle' => 'modal',
                            'data-target' => '#importModal'
                        ]) ?>
                        <?= Html::a('<i class="fas fa-file-excel"></i> Export Excel', ['export'], ['class' => 'btn btn-outline-success shadow-sm']) ?>
                    </div>
                </div>

                <!-- Import Modal -->
                <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="importModalLabel">นำเข้าข้อมูลจาก Excel</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?= Html::beginForm(['import'], 'post', ['enctype' => 'multipart/form-data']) ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>เลือกไฟล์ Excel (.xlsx, .xls)</label>
                                    <?= Html::fileInput('import_file', null, ['class' => 'form-control', 'accept' => '.xlsx, .xls', 'required' => true]) ?>
                                    <small class="text-muted mt-2 d-block">
                                        * ระบบจะอ่านข้อมูลจากแถวที่ 13 เป็นต้นไป<br>
                                        * ตรวจสอบหัว Column ในแถวที่ 12 ให้ตรงตามที่กำหนด
                                    </small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-primary">เริ่มนำเข้าข้อมูล</button>
                            </div>
                            <?= Html::endForm() ?>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'tableOptions' => ['class' => 'table table-hover table-striped mb-0'],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'sequence',
                                'doc_date',
                                'reference_no',
                                'vendor_name',
                                'tax_id',
                                'branch_code',
                                'tax_invoice_no',
                                'tax_invoice_date',
                                'tax_record_date',
                                [
                                    'attribute' => 'price_type',
                                    'value' => function($model) {
                                        $types = [1 => 'แยกภาษี', 2 => 'รวมภาษี', 3 => 'ไม่มีภาษี'];
                                        return $types[$model->price_type] ?? $model->price_type;
                                    }
                                ],
                                'account_code',
                                'description',
                                'qty',
                                'unit_price',
                                'tax_rate',
                                'wht_amount',
                                'paid_by',
                                [
                                    'attribute' => 'paid_amount',
                                    'format' => ['decimal', 2],
                                    'contentOptions' => ['class' => 'text-right'],
                                ],
                                'pnd_type',
                                'remarks',
                                'group_type',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{edit} {delete}',
                                    'contentOptions' => ['style' => 'min-width: 100px;'],
                                    'buttons' => [
                                        'edit' => function ($url, $model) {
                                            return Html::button('<i class="fas fa-edit"></i>', [
                                                'class' => 'btn btn-sm btn-info btn-edit-tax',
                                                'data-id' => $model->id,
                                                'title' => 'แก้ไข',
                                            ]);
                                        },
                                        'delete' => function ($url, $model) {
                                            return Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], [
                                                'class' => 'btn btn-sm btn-danger',
                                                'data' => [
                                                    'confirm' => 'คุณต้องการลบข้อมูลนี้ใช่หรือไม่?',
                                                    'method' => 'post',
                                                ],
                                                'title' => 'ลบ',
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
    </div>
</div>

<?php
$saveUrl = Url::to(['save'],true);
$getDataUrl = Url::to(['get-data'],true);
$js = <<<JS
    $('#btn-save-tax').on('click', function() {
        var form = $('#tax-import-form');
        var formData = form.serializeArray();
        formData.push({name: yii.getCsrfParam(), value: yii.getCsrfToken()});
        
        $.ajax({
            url: '$saveUrl',
            type: 'POST',
            data: formData,
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: res.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้ (Status: ' + xhr.status + ')'
                });
            }
        });
    });

    $('.btn-edit-tax').on('click', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '$getDataUrl',
            type: 'GET',
            data: {id: id},
            success: function(res) {
                $('#taximport-id').val(res.id);
                $('#taximport-sequence').val(res.sequence);
                $('#taximport-doc_date').val(res.doc_date);
                $('#taximport-reference_no').val(res.reference_no);
                $('#taximport-vendor_name').val(res.vendor_name);
                $('#taximport-tax_id').val(res.tax_id);
                $('#taximport-branch_code').val(res.branch_code);
                $('#taximport-tax_invoice_no').val(res.tax_invoice_no);
                $('#taximport-tax_invoice_date').val(res.tax_invoice_date);
                $('#taximport-tax_record_date').val(res.tax_record_date);
                $('#taximport-price_type').val(res.price_type);
                $('#taximport-account_code').val(res.account_code);
                $('#taximport-description').val(res.description);
                $('#taximport-qty').val(res.qty);
                $('#taximport-unit_price').val(res.unit_price);
                $('#taximport-tax_rate').val(res.tax_rate);
                $('#taximport-wht_amount').val(res.wht_amount);
                $('#taximport-paid_by').val(res.paid_by);
                $('#taximport-paid_amount').val(res.paid_amount);
                $('#taximport-pnd_type').val(res.pnd_type);
                $('#taximport-group_type').val(res.group_type);
                $('#taximport-remarks').val(res.remarks);
                
                $('html, body').animate({
                    scrollTop: $(".tax-import-index").offset().top
                }, 500);
                
                $('#taximport-sequence').focus();
            }
        });
    });

    $('#btn-clear-tax').on('click', function() {
        $('#tax-import-form')[0].reset();
        $('#taximport-id').val('');
    });
JS;
$this->registerJs($js);

// Add SweetAlert2 for nice alerts
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<style>
    .tax-import-index .card {
        border-radius: 10px;
        overflow: hidden;
    }
    .tax-import-index .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .tax-import-index .btn {
        border-radius: 5px;
        font-weight: 500;
    }
    .tax-import-index .table th, .tax-import-index .table td {
        white-space: nowrap;
    }
    .tax-import-index .table th {
        background-color: #f1f4f6;
        border-top: none;
    }
    .tax-import-index .form-group label {
        font-weight: 600;
        color: #495057;
    }
    .tax-import-index .form-control {
        border-radius: 5px;
        border: 1px solid #ced4da;
    }
    .tax-import-index .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
</style>
