<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Customer;
use backend\models\CustomerPo;
use wbraganca\dynamicform\DynamicFormWidget;

/** @var yii\web\View $this */
/** @var backend\models\CustomerPo $model */
/** @var backend\models\CustomerPoLine[] $modelsLine */
/** @var yii\widgets\ActiveForm $form */

// ตรวจสอบว่า modelsLine มีค่าหรือไม่
if (empty($modelsLine)) {
    $modelsLine = [new \backend\models\CustomerPoLine()];
}

$this->registerCss("
.dynamicform_wrapper .item { background-color: #f9f9f9; }
.table-responsive { overflow-x: auto; }
.dynamicform_wrapper .panel-heading {
    background: #fff;
}
");
?>
    <!-- Flash Messages -->
<?php if (\Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?= \Yii::$app->session->getFlash('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (\Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?= \Yii::$app->session->getFlash('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
    <div class="customer-po-form">

        <?php $form = ActiveForm::begin([
            'id' => 'dynamic-form',
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">ข้อมูลพื้นฐาน PO</h3>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-3">
                                <?= $form->field($model, 'po_number')->textInput([
                                    'maxlength' => true,
                                    'placeholder' => 'กรอกเลขที่ PO'
                                ]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'po_date')->widget(DatePicker::class, [
                                    'options' => ['placeholder' => 'เลือกวันที่สร้าง PO'],
                                    'pluginOptions' => [
                                        'format' => 'yyyy-mm-dd',
                                        'todayHighlight' => true,
                                        'autoclose' => true,
                                    ]
                                ]) ?>
                            </div>
                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <?= $form->field($model, 'status')->dropDownList(
                                    CustomerPo::getStatusOptions(),
                                    ['prompt' => 'เลือกสถานะ...']
                                ) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'customer_id')->widget(Select2::class, [
                                    'data' => ArrayHelper::map(Customer::find()->orderBy('name')->all(), 'id', 'name'),
                                    'options' => [
                                        'placeholder' => 'เลือกลูกค้า...',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'work_name')->textInput([
                                    'placeholder' => 'ชื่องาน...'
                                ]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'po_file_upload')->fileInput([
                                    'accept' => '.pdf,.doc,.docx,.jpg,.jpeg,.png',
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
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'remark')->textarea([
                                    'rows' => 3,
                                    'placeholder' => 'หมายเหตุเพิ่มเติม...'
                                ]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <?= $form->field($model, 'po_amount')->textInput([
                                    'placeholder' => '0',
                                    'readonly'=> true,
                                ]) ?>
                            </div>
                            <div class="col-lg-3">
                                <?= $form->field($model, 'billed_amount')->textInput([
                                    'placeholder' => '0',
                                    'readonly'=> true,
                                ]) ?>
                            </div>
                            <div class="col-lg-3">
                                <?= $form->field($model, 'remaining_amount')->textInput([
                                    'placeholder' => '0',
                                    'readonly'=> true,
                                ]) ?>
                            </div>
                            <div class="col-lg-3"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- PO Lines -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">รายละเอียด PO</h3>
                    </div>
                    <div class="card-body">

                        <?php DynamicFormWidget::begin([
                            'widgetContainer' => 'dynamicform_wrapper',
                            'widgetBody' => '.container-items',
                            'widgetItem' => '.item',
                            'limit' => 50,
                            'min' => 1,
                            'insertButton' => '.add-item',
                            'deleteButton' => '.remove-item',
                            'model' => $modelsLine[0],
                            'formId' => 'dynamic-form',
                            'formFields' => [
                                'item_name',
                                'description',
                                'qty',
                                'unit',
                                'price',
                            ],
                        ]); ?>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 50px;">ลำดับ</th>
                                    <th style="width: 200px;">ชื่องาน <span class="text-danger">*</span></th>
                                    <th>รายละเอียดงาน</th>
                                    <th style="width: 100px;">จำนวน <span class="text-danger">*</span></th>
                                    <th style="width: 100px;">หน่วยนับ</th>
                                    <th style="width: 120px;">ราคา/หน่วย <span class="text-danger">*</span></th>
                                    <th style="width: 130px;">รวม</th>
                                    <th style="width: 50px;">
                                        <button type="button" class="btn btn-success btn-sm add-item">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="container-items">
                                <?php foreach ($modelsLine as $i => $modelLine): ?>
                                    <tr class="item">
                                        <td class="text-center align-middle">
                                            <span class="line-number"><?= ($i + 1) ?></span>
                                            <?php
                                            // necessary for update action.
                                            if (!$modelLine->isNewRecord) {
                                                echo Html::activeHiddenInput($modelLine, "[{$i}]id");
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?= $form->field($modelLine, "[{$i}]item_name", [
                                                'template' => '{input}{error}',
                                            ])->textInput([
                                                'maxlength' => true,
                                                'placeholder' => 'ชื่องาน',
                                                'class' => 'form-control item-name'
                                            ]) ?>
                                        </td>
                                        <td>
                                            <?= $form->field($modelLine, "[{$i}]description", [
                                                'template' => '{input}{error}',
                                            ])->textarea([
                                                'rows' => 2,
                                                'placeholder' => 'รายละเอียด',
                                                'class' => 'form-control'
                                            ]) ?>
                                        </td>
                                        <td>
                                            <?= $form->field($modelLine, "[{$i}]qty", [
                                                'template' => '{input}{error}',
                                            ])->textInput([
                                                'type' => 'number',
                                                'step' => '0.01',
                                                'min' => '0',
                                                'value' => $modelLine->qty ?: 1,
                                                'placeholder' => '0.00',
                                                'class' => 'form-control text-right line-qty',
                                                'data-index' => $i
                                            ]) ?>
                                        </td>
                                        <td>
                                            <?= $form->field($modelLine, "[{$i}]unit", [
                                                'template' => '{input}{error}',
                                            ])->textInput([
                                                'maxlength' => true,
                                                'placeholder' => 'หน่วย',
                                                'class' => 'form-control'
                                            ]) ?>
                                        </td>
                                        <td>
                                            <?= $form->field($modelLine, "[{$i}]price", [
                                                'template' => '{input}{error}',
                                            ])->textInput([
                                                'type' => 'number',
                                                'step' => '0.01',
                                                'min' => '0',
                                                'placeholder' => '0.00',
                                                'class' => 'form-control text-right line-price',
                                                'data-index' => $i
                                            ]) ?>
                                        </td>
                                        <td>
                                            <input type="text"
                                                   class="form-control text-right line-total"
                                                   id="line-total-<?= $i ?>"
                                                   value="<?= number_format($modelLine->qty * $modelLine->price, 2) ?>"
                                                   readonly
                                                   style="background-color: #e9ecef;">
                                        </td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="6" class="text-right"><strong>มูลค่ารวมทั้งหมด:</strong></td>
                                    <td>
                                        <input type="text"
                                               id="grand-total"
                                               class="form-control text-right font-weight-bold"
                                               value="0.00"
                                               readonly
                                               style="background-color: #fff3cd; font-size: 1.1em;">
                                    </td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <?php DynamicFormWidget::end(); ?>

                    </div>
                </div>
            </div>
        </div>

        <!-- Summary and Submit -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="alert alert-info">
                                    <strong>หมายเหตุ:</strong>
                                    <ul class="mb-0">
                                        <li>มูลค่างาน PO จะคำนวณอัตโนมัติจากรายละเอียดที่เพิ่ม</li>
                                        <li>กรอกข้อมูลให้ครบถ้วนในแต่ละรายการ</li>
                                        <li>สามารถเพิ่มรายการได้สูงสุด 50 รายการ</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <?= Html::submitButton($model->isNewRecord ? '<i class="fas fa-save"></i> บันทึก' : '<i class="fas fa-save"></i> อัพเดต', [
                                    'class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block',
                                    'id' => 'submit-btn'
                                ]) ?>
                                <?= Html::a('<i class="fas fa-arrow-left"></i> กลับ', ['index'], [
                                    'class' => 'btn btn-secondary btn-lg btn-block mt-2'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php
// JavaScript แยกออกมา register ทีหลัง
$js = <<<JS

// ฟังก์ชันคำนวณ
function calculateLineTotal(index) {
    var qty = parseFloat($('.line-qty[data-index="' + index + '"]').val()) || 0;
    var price = parseFloat($('.line-price[data-index="' + index + '"]').val()) || 0;
    var total = qty * price;
    
    $('#line-total-' + index).val(total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    
    calculateGrandTotal();
}

function calculateGrandTotal() {
    var grandTotal = 0;
    $('.line-total').each(function() {
        var value = parseFloat($(this).val().replace(/,/g, '')) || 0;
        grandTotal += value;
    });
    
    $('#grand-total').val(grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
}

function updateLineNumbers() {
    $('.line-number').each(function(index) {
        $(this).text(index + 1);
    });
}

// Document Ready
$(document).ready(function() {
    
    // Event handlers for dynamicform
    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        var lastIndex = $('.item').length - 1;
        
        // Update attributes for new row
        $(item).find('.line-qty').attr('data-index', lastIndex).val(1);
        $(item).find('.line-price').attr('data-index', lastIndex).val(0);
        $(item).find('.line-total').attr('id', 'line-total-' + lastIndex).val('0.00');
        
        updateLineNumbers();
        calculateGrandTotal();
    });

    $(".dynamicform_wrapper").on("afterDelete", function(e) {
        // Re-index all rows
        $('.item').each(function(index) {
            $(this).find('.line-qty').attr('data-index', index);
            $(this).find('.line-price').attr('data-index', index);
            $(this).find('.line-total').attr('id', 'line-total-' + index);
        });
        
        updateLineNumbers();
        calculateGrandTotal();
    });
    
    $(".dynamicform_wrapper").on("limitReached", function(e, item) {
        alert("ถึงจำนวนสูงสุดแล้ว (50 รายการ)");
    });

    // Handle input changes
    $(document).on('input', '.line-qty, .line-price', function() {
        var index = $(this).data('index');
        calculateLineTotal(index);
    });

    // Initialize calculations
    $('.item').each(function(index) {
        $(this).find('.line-qty').attr('data-index', index);
        $(this).find('.line-price').attr('data-index', index);
        $(this).find('.line-total').attr('id', 'line-total-' + index);
        calculateLineTotal(index);
    });
    
    calculateGrandTotal();
});

// Form validation
$('#dynamic-form').on('beforeSubmit', function(e) {
    var isValid = true;
    var errorMessages = [];
    
    // Validate PO fields
    if (!$('#customerpo-po_number').val() || $('#customerpo-po_number').val().trim() === '') {
        errorMessages.push('- กรุณากรอกเลขที่ PO');
        isValid = false;
    }
    
    if (!$('#customerpo-customer_id').val()) {
        errorMessages.push('- กรุณาเลือกลูกค้า');
        isValid = false;
    }
    
    // Check if at least one line exists
    if ($('.item').length === 0) {
        errorMessages.push('- กรุณาเพิ่มรายละเอียด PO อย่างน้อย 1 รายการ');
        isValid = false;
    }
    
    // Validate each line
    $('.item').each(function(index) {
        var itemName = $(this).find('[name*="[item_name]"]').val();
        var qty = parseFloat($(this).find('[name*="[qty]"]').val()) || 0;
        var price = parseFloat($(this).find('[name*="[price]"]').val()) || 0;
        
        // if (!itemName || itemName.trim() === '') {
        //     errorMessages.push('- รายการที่ ' + (index + 1) + ': กรุณากรอกชื่องาน');
        //     isValid = false;
        // }
        //
        // if (qty <= 0) {
        //     errorMessages.push('- รายการที่ ' + (index + 1) + ': จำนวนต้องมากกว่า 0');
        //     isValid = false;
        // }
        //
        // if (price < 0) {
        //     errorMessages.push('- รายการที่ ' + (index + 1) + ': ราคาต้องไม่ติดลบ');
        //     isValid = false;
        // }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('กรุณาตรวจสอบข้อมูลดังต่อไปนี้:\\n\\n' + errorMessages.join('\\n'));
        return false;
    }
    
    $('#submit-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> กำลังบันทึก...');
    
    return true;
});

JS;

$this->registerJs($js, \yii\web\View::POS_READY);
?>