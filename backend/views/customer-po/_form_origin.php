<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Customer;
use backend\models\CustomerPo;

/** @var yii\web\View $this */
/** @var backend\models\CustomerPo $model */
/** @var yii\widgets\ActiveForm $form */
?>

    <div class="customer-po-form">

        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"input-group\">{input}\n{error}</div>\n{hint}",
            ],
        ]); ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">ข้อมูลพื้นฐาน PO</h3>
                    </div>
                    <div class="card-body">

                        <?= $form->field($model, 'po_number')->textInput([
                            'maxlength' => true,
                            'placeholder' => 'กรอกเลขที่ PO'
                        ]) ?>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'po_date')->widget(DatePicker::class, [
                                    'options' => ['placeholder' => 'เลือกวันที่สร้าง PO'],
                                    'pluginOptions' => [
                                        'format' => 'yyyy-mm-dd',
                                        'todayHighlight' => true,
                                        'autoclose' => true,
                                    ]
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'po_target_date')->widget(DatePicker::class, [
                                    'options' => ['placeholder' => 'เลือกวันที่หมดอายุ'],
                                    'pluginOptions' => [
                                        'format' => 'yyyy-mm-dd',
                                        'todayHighlight' => true,
                                        'autoclose' => true,
                                        'startDate' => 'today',
                                    ]
                                ]) ?>
                            </div>
                        </div>

                        <?= $form->field($model, 'customer_id')->widget(Select2::class, [
                            'data' => ArrayHelper::map(Customer::find()->orderBy('name')->all(), 'id', 'name'),
                            'options' => [
                                'placeholder' => 'เลือกลูกค้า...',
                                'id' => 'customerpo-customer_id'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'escapeMarkup' => new \yii\web\JsExpression('function (markup) { return markup; }'),
                            ],
                        ]) ?>

                        <?= $form->field($model, 'work_name')->textarea([
                            'rows' => 4,
                            'placeholder' => 'รายละเอียดงาน...'
                        ]) ?>

                        <?= $form->field($model, 'status')->dropDownList(
                            CustomerPo::getStatusOptions(),
                            ['prompt' => 'เลือกสถานะ...']
                        ) ?>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">ข้อมูลมูลค่าและไฟล์แนบ</h3>
                    </div>
                    <div class="card-body">

                        <?= $form->field($model, 'po_amount')->textInput([
                            'type' => 'number',
                            'step' => '0.01',
                            'min' => '0',
                            'placeholder' => '0.00',
                            'id' => 'po-amount',
                            'style' => 'text-align: right;'
                        ]) ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">ยอดวางบิล</label>
                                    <input type="text" class="form-control"
                                           value="<?= number_format($model->billed_amount, 2) ?>"
                                           readonly style="text-align: right; background-color: #f8f9fa;">
                                    <small class="text-muted">คำนวณอัตโนมัติจากใบวางบิลที่เชื่อมโยง</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">คงเหลือ</label>
                                    <input type="text" class="form-control"
                                           value="<?= number_format($model->remaining_amount, 2) ?>"
                                           readonly style="text-align: right; background-color: #f8f9fa;"
                                           id="remaining-amount">
                                    <small class="text-muted">คำนวณอัตโนมัติ (มูลค่างาน - ยอดวางบิล)</small>
                                </div>
                            </div>
                        </div>

                        <?= $form->field($model, 'po_file_upload')->fileInput([
                            'accept' => '.pdf,.doc,.docx,.jpg,.jpeg,.png',
                            'id' => 'po-file-input'
                        ])->hint('ไฟล์ที่รองรับ: PDF, DOC, DOCX, JPG, PNG (ไม่เกิน 10MB)') ?>

                        <?php if ($model->po_file): ?>
                            <div class="alert alert-info">
                                <strong>ไฟล์ปัจจุบัน:</strong>
                                <?= Html::a($model->po_file, ['download', 'id' => $model->id], [
                                    'class' => 'btn btn-sm btn-outline-primary ml-2',
                                    'target' => '_blank'
                                ]) ?>
                            </div>
                        <?php endif; ?>

                        <?= $form->field($model, 'remark')->textarea([
                            'rows' => 3,
                            'placeholder' => 'หมายเหตุเพิ่มเติม...'
                        ]) ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center">
                        <?= Html::submitButton($model->isNewRecord ? '<i class="fas fa-save"></i> บันทึก' : '<i class="fas fa-save"></i> อัพเดต', [
                            'class' => $model->isNewRecord ? 'btn btn-success btn-lg' : 'btn btn-primary btn-lg',
                            'id' => 'submit-btn'
                        ]) ?>
                        <?= Html::a('<i class="fas fa-arrow-left"></i> กลับ', ['index'], [
                            'class' => 'btn btn-secondary btn-lg ml-2'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php
$this->registerJs("
// Auto calculate remaining amount when po_amount changes
$('#po-amount').on('input', function() {
    var poAmount = parseFloat($(this).val()) || 0;
    var billedAmount = " . ($model->billed_amount ?? 0) . ";
    var remaining = poAmount - billedAmount;
    
    $('#remaining-amount').val(remaining.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }));
    
    // Change color based on remaining amount
    if (remaining < 0) {
        $('#remaining-amount').addClass('text-danger').removeClass('text-success');
    } else if (remaining === 0) {
        $('#remaining-amount').addClass('text-success').removeClass('text-danger');
    } else {
        $('#remaining-amount').removeClass('text-danger text-success');
    }
});

// File upload validation
$('#po-file-input').on('change', function() {
    var file = this.files[0];
    var maxSize = 10 * 1024 * 1024; // 10MB
    var allowedTypes = ['application/pdf', 'application/msword', 
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'image/jpeg', 'image/jpg', 'image/png'];
    
    if (file) {
        if (file.size > maxSize) {
            alert('ไฟล์มีขนาดใหญ่เกิน 10MB');
            $(this).val('');
            return;
        }
        
        if (allowedTypes.indexOf(file.type) === -1) {
            alert('ประเภทไฟล์ไม่รองรับ กรุณาเลือกไฟล์ PDF, DOC, DOCX, JPG หรือ PNG');
            $(this).val('');
            return;
        }
        
        // Show file name
        var fileName = file.name;
        if (fileName.length > 30) {
            fileName = fileName.substring(0, 27) + '...';
        }
        $(this).next('.custom-file-label').text(fileName);
    }
});

// Form validation before submit
$('#submit-btn').click(function(e) {
    var isValid = true;
    var errorMessages = [];
    
    // Validate required fields
    if (!$('#customerpo-po_number').val().trim()) {
        errorMessages.push('- กรุณากรอกเลขที่ PO');
        isValid = false;
    }
    
    if (!$('#customerpo-po_date').val()) {
        errorMessages.push('- กรุณาเลือกวันที่สร้าง PO');
        isValid = false;
    }
    
    if (!$('#customerpo-po_target_date').val()) {
        errorMessages.push('- กรุณาเลือกวันที่หมดอายุ');
        isValid = false;
    }
    
    if (!$('#customerpo-customer_id').val()) {
        errorMessages.push('- กรุณาเลือกลูกค้า');
        isValid = false;
    }
    
    if (!$('#customerpo-work_name').val().trim()) {
        errorMessages.push('- กรุณากรอกรายละเอียดงาน');
        isValid = false;
    }
    
    var poAmount = parseFloat($('#po-amount').val()) || 0;
    if (poAmount <= 0) {
        errorMessages.push('- กรุณากรอกมูลค่างานที่ถูกต้อง');
        isValid = false;
    }
    
    // Validate date range
    var poDate = new Date($('#customerpo-po_date').val());
    var targetDate = new Date($('#customerpo-po_target_date').val());
    
    if (targetDate < poDate) {
        errorMessages.push('- วันที่หมดอายุต้องมากกว่าหรือเท่ากับวันที่สร้าง PO');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
        alert('กรุณาตรวจสอบข้อมูลดังต่อไปนี้:\\n\\n' + errorMessages.join('\\n'));
        return false;
    }
    
    // Show loading state
  //  $(this).prop('disabled', true).html('<i class=\"fas fa-spinner fa-spin\"></i> กำลังบันทึก...');
});

// Format number input
$('#po-amount').on('blur', function() {
    var value = parseFloat($(this).val()) || 0;
    $(this).val(value.toFixed(2));
});
");