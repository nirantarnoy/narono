<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Customerinvoice $model */
/** @var yii\widgets\ActiveForm $form */
?>

    <div class="customerinvoice-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'invoice_no')->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?>
            </div>
            <div class="col-lg-3">
                <?php $model->invoice_date = $model->isNewRecord ? date('d-m-Y') : date('d-m-Y', strtotime($model->invoice_date)); ?>
                <?= $form->field($model, 'invoice_date')->widget(\kartik\date\DatePicker::className(), [
                    'value' => date('d-m-Y'),
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                    ]
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?php $model->invoice_target_date = $model->isNewRecord ? date('d-m-Y') : date('d-m-Y', strtotime($model->invoice_target_date)); ?>
                <?= $form->field($model, 'invoice_target_date')->widget(\kartik\date\DatePicker::className(), [
                    'value' => date('d-m-Y'),
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                    ]
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'sale_id')->widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->all(), 'id', function ($data) {
                        return $data->fname . ' ' . $data->lname;
                    }),
                    'options' => [
                        'placeholder' => 'เลือกพนักงาน',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'customer_id')->widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Customer::find()->all(), 'id', 'name'),
                    'options' => [
                        'placeholder' => 'เลือกลูกค้า',
                        'onchange' => 'checkcustomer($(this))'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'vat_per')->widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\helpers\VatperType::asArrayObject(), 'id', 'name'),
                    'options' => [
                    //    'placeholder' => 'เลือก % Vat',
                        'onchange' => 'calinvoiceall()'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered" id="table-list">
                    <thead>
                    <tr>
                        <th style="width: 5%;text-align: center;"><b>#</b></th>
                        <th><b>รายละเอียด</b></th>
                        <th style="width: 15%;text-align: right;"><b>จำนวน</b></th>
                        <th style="width: 15%;text-align: right;"><b>ราคาต่อหน่วย</b></th>
                        <th style="width: 15%;text-align: right;"><b>ยอดรวม</b></th>
                        <th style="width: 5%;text-align: center;"><b>-</b></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($model->isNewRecord): ?>
                        <tr>
                            <td style="width: 5%;text-align: center;"></td>
                            <td>
                                <textarea name="line_text[]" class="form-control line-text" id="" cols="30"
                                          rows="2"></textarea>
                                <input type="hidden" class="line-order-no" name="line_order_no[]" value="">
                                <input type="hidden" class="line-order-id" name="line_order_id[]" value="">
                            </td>
                            <td>
                                <input type="number" style="text-align: right;" class="form-control line-qty"
                                       name="line_qty[]" value="" onchange="calinvoice($(this))">
                            </td>
                            <td>
                                <input type="number" style="text-align: right;" class="form-control line-price"
                                       name="line_price[]" value="" min="0" onchange="calinvoice($(this))">
                            </td>
                            <td>
                                <input type="hidden" style="text-align: right;"
                                       class="form-control line-total-cal"
                                       name="" value="">
                                <input type="text" style="text-align: right;" class="form-control line-total"
                                       name="line_total[]" value="" readonly>
                            </td>
                            <td>
                                <div class="btn btn-danger" onclick="removeline($(this))">ลบ</div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php if ($modelline != null): ?>
                            <?php $line_num = 0; ?>
                            <?php foreach ($modelline as $value): ?>
                                <?php $line_num += 1; ?>
                                <tr>
                                    <td style="width: 5%;text-align: center;"><?= $line_num ?></td>
                                    <td>
                                        <textarea name="line_text[]" class="form-control line-text" id="" cols="30"
                                                  rows="2"><?= $value->item_name; ?></textarea>
                                        <input type="hidden" class="line-order-no" name="line_order_no[]" value="">
                                        <input type="hidden" class="line-order-id" name="line_order_id[]"
                                               value="<?= $value->item_work_id ?>">
                                        <input type="hidden" name="line_edit_id[]" class="line-edit-id" value="<?=$value->id?>">
                                    </td>
                                    <td>
                                        <input type="number" style="text-align: right;" class="form-control line-qty"
                                               name="line_qty[]" value="<?= $value->qty ?>"
                                               onchange="calinvoice($(this))">
                                    </td>
                                    <td>
                                        <input type="number" style="text-align: right;" class="form-control line-price"
                                               name="line_price[]" value="<?= $value->price ?>" min="0"
                                               onchange="calinvoice($(this))">
                                    </td>
                                    <td>
                                        <input type="hidden" style="text-align: right;"
                                               class="form-control line-total-cal"
                                               name="" value="<?= $value->qty * $value->price ?>">
                                        <input type="text" style="text-align: right;" class="form-control line-total"
                                               name="line_total[]"
                                               value="<?= number_format($value->qty * $value->price) ?>" readonly>
                                    </td>
                                    <td>
                                        <div class="btn btn-danger" onclick="removeline($(this))">ลบ</div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td style="width: 5%;text-align: center;"></td>
                                <td>
                                    <textarea name="line_text[]" class="form-control line-text" id="" cols="30"
                                              rows="2"></textarea>
                                    <input type="hidden" class="line-order-no" name="line_order_no[]" value="">
                                    <input type="hidden" class="line-order-id" name="line_order_id[]" value="">
                                    <input type="hidden" name="line_edit_id[]" class="line-edit-id" value="">
                                </td>
                                <td>
                                    <input type="number" style="text-align: right;" class="form-control line-qty"
                                           name="line_qty[]" value="" onchange="calinvoice($(this))">
                                </td>
                                <td>
                                    <input type="number" style="text-align: right;" class="form-control line-price"
                                           name="line_price[]" value="" min="0" onchange="calinvoice($(this))">
                                </td>
                                <td>
                                    <input type="hidden" style="text-align: right;"
                                           class="form-control line-total-cal"
                                           name="" value="<?= $value->qty * $value->price ?>">
                                    <input type="text" style="text-align: right;" class="form-control line-total"
                                           name="line_total[]" value="" readonly>
                                </td>
                                <td>
                                    <div class="btn btn-danger" onclick="removeline($(this))">ลบ</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td style="text-align: center;">
                            <div class="btn btn-primary btn-sm btn-addline" onclick="finditem($(this))"><i
                                        class="fa fa-plus"></i></div>
                        </td>
                        <td colspan="5">

                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;font-size: 18px;">รวมเป็นเงิน</td>
                        <td>
                            <?= $form->field($model, 'total_amount')->textInput(['readonly' => 'readonly', 'style' => 'text-align: right'])->label(false) ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;font-size: 18px;">จำนวนรวมเงินทั้งสิ้น</td>
                        <td>
                            <?= $form->field($model, 'total_all_amount')->textInput(['readonly' => 'readonly', 'style' => 'text-align: right'])->label(false) ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;font-size: 18px;">หักภาษี ณ ที่จ่าย <span class="vat-per-display"></span> %</td>
                        <td>
                            <?= $form->field($model, 'vat_amount')->textInput(['readonly' => 'readonly', 'style' => 'text-align: right'])->label(false) ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;font-size: 18px;">ยอดชำระ</td>
                        <td>
                            <?= $form->field($model, 'final_amount')->textInput(['readonly' => 'readonly', 'style' => 'text-align: right'])->label(false) ?>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>


        <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'remark2')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'customer_extend_remark')->textInput(['maxlength' => true]) ?>

        <?php // $form->field($model, 'company_extend_remark')->textInput(['maxlength' => true]) ?>

        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <div class="col-lg-3"></div>
            <div class="col-lg-3"></div>
            <div class="col-lg-3" style="text-align: right;">
                <?php if (!$model->isNewRecord): ?>
                    <div class="btn btn-warning" onclick="printdoc('print-area')">Print</div>
                <?php endif; ?>
            </div>
        </div>


        <?php ActiveForm::end(); ?>

    </div>

    <div id="findModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h3>รายการใบงาน</h3>
                </div>
                <!--            <div class="modal-body" style="white-space:nowrap;overflow-y: auto">-->
                <!--            <div class="modal-body" style="white-space:nowrap;overflow-y: auto;scrollbar-x-position: top">-->

                <div class="modal-body">
                    <input type="hidden" name="line_qc_product" class="line_qc_product" value="">
                    <table class="table table-bordered table-striped table-find-list" width="100%">
                        <thead>
                        <tr>
                            <th style="width:10%;text-align: center">เลือก</th>
                            <th style="width: 20%;text-align: center">เลขที่</th>
                            <th style="width: 20%;text-align: center">วันที่</th>
                            <th>หัวข้อ</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-outline-success btn-emp-selected" data-dismiss="modalx" disabled><i
                                class="fa fa-check"></i> ตกลง
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                class="fa fa-close text-danger"></i> ปิดหน้าต่าง
                    </button>
                </div>
            </div>

        </div>
    </div>

    <div id="print-area" style="display: none;">
        <div class="row">
            <div class="col-lg-6"></div>
            <div class="col-lg-6" style="text-align: center;">
                <h4>ใบวางบิล/ใบแจ้งหนี้</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table style="border: none;width: 100%">
                    <tr>
                        <td style="width: 50%;">
                            <table style="border: none;width: 100%">
                                <tr>
                                    <td>
                                        บริษัท ณโรโน่โลจิสติกส์ จำกัด <br/>
                                        3/6 หมู่ 6 ตำบลวังศาลา อำเภอท่าม่วง จังหวัดกาญจนบุรี 71110 <br/>
                                        0715564002320 (สาขาสำนักงานใหญ่)
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        ลูกค้า
                                        <br/>
                                        <?=\backend\models\Customer::findCusName($model->customer_id)?>
                                        <?=\backend\models\Customer::findAddress($model->customer_id);?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="border: none;width: 100%">
                                <tr>
                                    <td style="text-align: right;width: 40%">เลขที่</td>
                                    <td style="width: 5%"></td>
                                    <td><?=$model->invoice_no?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">วันที่</td>
                                    <td style="width: 5%"></td>
                                    <td><?=date('d-m-Y',strtotime($model->invoice_date))?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">ครบกำหนด</td>
                                    <td style="width: 5%"></td>
                                    <td><?=date('d-m-Y',strtotime($model->invoice_target_date))?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">ผู้ขาย</td>
                                    <td style="width: 5%"></td>
                                    <td><?=\backend\models\Employee::findFullName($model->sale_id)?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;">ชื่องาน</td>
                                    <td style="width: 5%"></td>
                                    <td></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="height: 25px;"></div>
        <div class="row">
            <div class="col-lg-12">
                <table style="width: 100%">
                    <thead>
                    <tr>
                        <th style="text-align: center;width:10%;border-top: 1px solid gray;border-bottom: 1px solid gray;padding: 10px 0px 10px 0px;">#</th>
                        <th style="text-align: center;width:45%;border-top: 1px solid gray;border-bottom: 1px solid gray;padding: 10px 0px 10px 0px;">รายละเอียด</th>
                        <th style="text-align: right;width:15%;border-top: 1px solid gray;border-bottom: 1px solid gray;padding: 10px 0px 10px 0px;">จำนวน</th>
                        <th style="text-align: right;width:15%;border-top: 1px solid gray;border-bottom: 1px solid gray;padding: 10px 0px 10px 0px;">ราคาต่อหน่วย</th>
                        <th style="text-align: right;width:15%;border-top: 1px solid gray;border-bottom: 1px solid gray;padding: 10px 0px 10px 0px;">ยอดรวม</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                      $total = 0;
                      $total_all_amt = 0;
                      $vat_amt = 0;
                      $final_amt = 0;
                    ?>
                    <?php if ($modelline != null): ?>
                        <?php $line_num = 0; ?>
                        <?php foreach ($modelline as $value): ?>
                            <?php
                            $line_num += 1;
                            $total = $total + ($value->qty * $value->price);
                            $total_all_amt = $total;
                            ?>
                            <tr>
                                <td style="width: 5%;text-align: center;padding: 20px 0px 20px 0px;vertical-align: top;"><?= $line_num ?></td>
                                <td>
                                    <?= $value->item_name; ?> <br />xxx
                                </td>
                                <td style="text-align: right;">
                                    <?= $value->qty ?>
                                </td>
                                <td style="text-align: right;">
                                    <?= number_format($value->price,2) ?>
                                </td>
                                <td style="text-align: right;">
                                    <?= number_format($value->qty * $value->price,2) ?>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php
                    $vat_amt = ($total *1) /100;
                    $final_amt = $total - $vat_amt;
                    ?>
                    <?php endif; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right;font-size: 18px;padding: 10px 0px 10px 0px;border-top: 1px solid grey;">รวมเป็นเงิน</td>
                        <td style="text-align: right;border-top: 1px solid grey;">
                            <?=number_format($total,2)?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: left;"><h5>(<?=$model->total_text?>)</h5></td>
                        <td colspan="2" style="text-align: right;font-size: 18px;padding: 10px 0px 10px 0px;">จำนวนรวมเงินทั้งสิ้น</td>
                        <td style="text-align: right"><?=number_format($total_all_amt,2)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2" style="text-align: right;font-size: 18px;padding: 10px 0px 10px 0px;border-top: 1px solid grey;">หักภาษี ณ ที่จ่าย</td>
                        <td style="text-align: right;border-top: 1px solid grey;"><?=number_format($vat_amt,2)?></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;font-size: 18px;padding: 10px 0px 10px 0px;">ยอดชำระ</td>
                        <td style="text-align: right"><?=number_format($final_amt,2)?></td>
                    </tr>
                    <tr>
                        <td colspan="5"></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="border: 0px;padding: 10px 0px 10px 0px;"><b>หมายเหตุ</b></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="border: none;">
                            <?=$model->remark?> <br />
                            <?=$model->remark2?> <br />
                            <?=$model->customer_extend_remark?>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div style="height: 500px;"></div>
        <div class="row">
            <div class="col-lg-12">
                <table style="border: none;width: 100%">
                    <tr>
                        <td style="width: 50%">
                            <table style="width: 100%">
                                <tr>
                                    <td style="text-align: center;">.....................................................<br/>ผู้รับสินค้า/บริการ</td>
                                    <td style="text-align: left;">...........................................<br/>วันที่</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="width: 100%">
                                <tr>
                                    <td style="text-align: center;">.....................................................<br/>ผู้อนุมัติ</td>
                                    <td style="text-align: left;">...........................................<br/>วันที่</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

<?php
$url_to_find_workqueue = \yii\helpers\Url::to(['customerinvoice/findpreinvioce'], true);
$js = <<<JS
var selecteditem = [];
var selectedorderlineid = [];
var selecteditemgroup = [];
var customer_id = 0;

$(function(){
    calinvoiceall();
});
function checkcustomer(e){
  //  alert(e.val());
    if(e.val()!=null){
        customer_id = e.val();
    }
}

function finditem(e){
    if(customer_id > 0){
        $.ajax({
          type: 'post',
          dataType: 'html',
          url:'$url_to_find_workqueue',
          async: false,
          data: {'customer_id': customer_id},
          success: function(data){
             // alert(data);
              $(".table-find-list tbody").html(data);
              $("#findModal").modal("show");
          },
          error: function(err){
              alert(err);
          }
          
        });
    }
    
}

function addline(e){
    var tr = $("#table-list tbody tr:last");
                    var clone = tr.clone();
                    //clone.find(":text").val("");
                    // clone.find("td:eq(1)").text("");
                   clone.find(".line-text").val("0");
                  clone.find(".line-order-no").val("");
                   clone.find(".line-qty").val("0");
                   clone.find(".line-price").val("0");
                   clone.find(".line-total").val("0");
                   clone.find(".line-total-cal").val("0");
                   
                    clone.attr("data-var", "");
                    clone.find('.rec-id').val("0");
                   
                    tr.after(clone);
    
}
function removeline(e) {
        if (confirm("ต้องการลบรายการนี้ใช่หรือไม่?")) {
            if (e.parent().parent().attr("data-var") != '') {
                removelist.push(e.parent().parent().attr("data-var"));
                $(".remove-list").val(removelist);
            }
            // alert(removelist);
            // alert(e.parent().parent().attr("data-var"));

            if ($("#table-list tbody tr").length == 1) {
                $("#table-list tbody tr").each(function () {
                    $(this).find(":text").val("");
                   // $(this).find(".line-prod-photo").attr('src', '');
                    $(this).find(".line-file-name").val('');
                    // cal_num();
                });
            } else {
                e.parent().parent().remove();
            }
            // cal_linenum();
            // cal_all();
        }
}

function addselecteditem(e) {
        var id = e.attr('data-var');
        var order_id = e.closest('tr').find('.line-find-order-id').val();
      
        ///// add new 
         var order_line_work_type_name = e.closest('tr').find('.line-find-work-type-name').val();
         var order_no = e.closest('tr').find('.line-find-order-no').val();
         var order_line_qty = e.closest('tr').find('.line-find-qty').val();
         var order_line_price = e.closest('tr').find('.line-find-price').val();
        ///////
        if (id) {
            // if(checkhasempdaily(id)){
            //     alert("คุณได้ทำการจัดรถให้พนักงานคนนี้ไปแล้ว");
            //     return false;
            // }
            if (e.hasClass('btn-outline-success')) {
                var obj = {};
                obj['id'] = id;
                obj['order_id'] = order_id;
                obj['line_work_type_name'] = order_line_work_type_name;
                obj['line_order_no'] = order_no;
                obj['qty'] = order_line_qty;
                obj['price'] = order_line_price;
                obj['total'] = (order_line_qty * order_line_price);
                selecteditem.push(obj);
                selectedorderlineid.push(obj['id']);
                    // var obj_after = {};
                    // obj_after['qty'] = order_line_qty;
                    // obj_after['price'] = order_line_price;
                    // obj_after['discount'] = 0;
                    // obj_after['total'] = (order_line_qty * order_line_price);
                    //
                    // alert(obj_after['product_group_id']);
                    // alert(obj_after['product_group_name']);
                    // alert(obj_after['qty']);
                    
            
                e.removeClass('btn-outline-success');
                e.addClass('btn-success');
                disableselectitem();
                console.log(selecteditem);
            } else {
                //selecteditem.pop(id);
                $.each(selecteditem, function (i, el) {
                    if (this.id == id) {
                        var qty = this.qty;
                        var product_group_id = this.product_group_id;
                        selecteditem.splice(i, 1);
                        selectedorderlineid.splice(i,1);
                        deleteorderlineselected(product_group_id, qty); // update data in selected list
                        console.log(selecteditemgroup);
                        caltablecontent(); // refresh table below
                    }
                });
                e.removeClass('btn-success');
                e.addClass('btn-outline-success');
                
                disableselectitem();
                console.log(selecteditem);
                console.log(selectedorderlineid);
                console.log(selecteditemgroup);
            }
        }
        $(".orderline-id-list").val(selectedorderlineid);
}

function disableselectitem() {
        if (selecteditem.length > 0) {
            $(".btn-emp-selected").prop("disabled", "");
            $(".btn-emp-selected").removeClass('btn-outline-success');
            $(".btn-emp-selected").addClass('btn-success');
        } else {
            $(".btn-emp-selected").prop("disabled", "disabled");
            $(".btn-emp-selected").removeClass('btn-success');
            $(".btn-emp-selected").addClass('btn-outline-success');
        }
}

$(".btn-emp-selected").click(function () {
        var linenum = 0;
        var line_count = 0;
      
        if(selecteditem.length >0){
             var tr = $("#table-list tbody tr:last");
             
             for(var i=0;i<=selecteditem.length-1;i++){
                 var new_text = selecteditem[i]['line_work_type_name'] + "\\n" + "Order No."+selecteditem[i]['line_order_no'];
                   if (tr.closest("tr").find(".line-text").val() == "") {
                  //  alert(line_prod_code);
                    tr.closest("tr").find(".line-text").val(new_text);
                    tr.closest("tr").find(".line-order-id").val(selecteditem[i]['order_id']);
                    tr.closest("tr").find(".line-qty").val(selecteditem[i]['qty']);
                    tr.closest("tr").find(".line-price").val(selecteditem[i]['price']);
                    tr.closest("tr").find(".line-total").val(selecteditem[i]['total']);
                    tr.closest("tr").find(".line-total-cal").val(selecteditem[i]['total']);
                    //console.log(line_prod_code);
                    } else {
                       
                        var clone = tr.clone();
                        clone.closest("tr").find(".line-text").val(new_text);
                        clone.closest("tr").find(".line-order-id").val(selecteditem[i]['order_id']);
                        clone.closest("tr").find(".line-qty").val(selecteditem[i]['qty']);
                        clone.closest("tr").find(".line-price").val(selecteditem[i]['price']);
                        clone.closest("tr").find(".line-total").val(selecteditem[i]['total']);
                        clone.closest("tr").find(".line-total-cal").val(selecteditem[i]['total']);
                        tr.after(clone);
                    } 
             }
                
          
        }
        
        $("#table-list tbody tr").each(function () {
           linenum += 1;
            $(this).closest("tr").find("td:eq(0)").text(linenum);
            // $(this).closest("tr").find(".line-prod-code").val(line_prod_code);
        });
        
        selecteditem = [];
        selectedorderlineid = [];
        selecteditemgroup = [];

        $("#table-find-list tbody tr").each(function () {
            $(this).closest("tr").find(".btn-line-select").removeClass('btn-success');
            $(this).closest("tr").find(".btn-line-select").addClass('btn-outline-success');
        });
        
        $(".btn-emp-selected").removeClass('btn-success');
        $(".btn-emp-selected").addClass('btn-outline-success');
        $("#findModal").modal('hide');
        
        calinvoiceall();
});

function calinvoice(e){
    var line_qty = e.closest('tr').find(".line-qty").val();
    var line_price = e.closest('tr').find(".line-price").val();
    var line_total = parseFloat(line_qty) * parseFloat(line_price);
    e.closest('tr').find('.line-total').val(parseFloat(line_total).toFixed(2));
    e.closest('tr').find('.line-total-cal').val(parseFloat(line_total).toFixed(2));
    
    
    var total_amt = 0;
    var total_all_amt = 0;
    var final_amt = 0;
    var vat_amt = 0;
    
      $("#table-list tbody tr").each(function () {
           var line_amt = $(this).find('.line-total').val();
           if(line_amt != null){
               total_amt = parseFloat(total_amt) + parseFloat(line_amt);
              
           }
      });
    vat_amt = (total_amt * 1) / 100;
    final_amt = parseFloat(total_amt) - vat_amt;
    $("#customerinvoice-total_amount").val(parseFloat(total_amt).toFixed(2));
    $("#customerinvoice-total_all_amount").val(parseFloat(total_amt).toFixed(2));
    $("#customerinvoice-vat_amount").val(parseFloat(vat_amt).toFixed(2));
    $("#customerinvoice-final_amount").val(parseFloat(final_amt).toFixed(2));
}
function calinvoiceall(){
    
    var total_amt = 0;
    var total_all_amt = 0;
    var final_amt = 0;
    var vat_amt = 0;
    
    var vat_per_cal = $("#customerinvoice-vat_per").val();
    $(".vat-per-display").html(vat_per_cal)
    //alert(vat_per_cal);
    
      $("#table-list tbody tr").each(function () {
           var line_amt = $(this).find('.line-total-cal').val();
           if(line_amt != null){
               total_amt = parseFloat(total_amt) + parseFloat(line_amt);
              
           }
      });
    vat_amt = (total_amt * vat_per_cal) / 100;
    final_amt = parseFloat(total_amt) - vat_amt;
    $("#customerinvoice-total_amount").val(parseFloat(total_amt).toFixed(2));
    $("#customerinvoice-total_all_amount").val(parseFloat(total_amt).toFixed(2));
    $("#customerinvoice-vat_amount").val(parseFloat(vat_amt).toFixed(2));
    $("#customerinvoice-final_amount").val(parseFloat(final_amt).toFixed(2));
}

function printdoc(el)
      {
         var restorepage = document.body.innerHTML;
         var printcontent = document.getElementById(el).innerHTML;
         document.body.innerHTML = printcontent;
         window.print();
         document.body.innerHTML = restorepage;
     }

JS;
$this->registerJs($js, static::POS_END);
?>