<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Preinvoice $model */
/** @var yii\widgets\ActiveForm $form */
?>

    <div class="preinvoice-form">

        <?php $form = ActiveForm::begin(); ?>
        <input type="hidden" class="remove-list" name="removelist" value="">
        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'journal_no')->textInput(['maxlength' => true, 'readonly' => 'readonly'])  ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'name')->textarea(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-3">
                <?php $model->from_date = $model->isNewRecord ? date('d-m-Y') : date('d-m-Y', strtotime($model->from_date)); ?>
                <?= $form->field($model, 'from_date')->widget(\kartik\date\DatePicker::className(), [
                    'value' => date('d-m-Y'),
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                    ]
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?php $model->to_date = $model->isNewRecord ? date('d-m-Y') : date('d-m-Y', strtotime($model->to_date)); ?>
                <?= $form->field($model, 'to_date')->widget(\kartik\date\DatePicker::className(), [
                    'value' => date('d-m-Y'),
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
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
                <label for="">สถานะ</label>
                <input type="text" class="form-control preinvoice-status" readonly value="<?=\backend\helpers\OrderStatus::getTypeById($model->isNewRecord?1:$model->status)?>">
            </div>
        </div>

        <br/>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered" id="table-list">
                    <thead>
                    <tr>
                        <th style="width: 5%;text-align: center;">#</th>
                        <th style="text-align: center;">เลขที่ใบตั้ง</th>
                        <th style="width: 15%;text-align: center;">น้ำหนัก</th>
                        <th style="width: 15%;text-align: center;">ยอดเงิน</th>
                        <th style="width: 5%;text-align: center;">-</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($model->isNewRecord): ?>
                        <tr>
                            <td style="text-align: center;"></td>
                            <td>
                                <input type="hidden" class="form-control line-work-queue-id"
                                       name="line_work_queue_id[]">
                                <input type="text" class="form-control line-work-queue-no" name="line_work_queue_no[]"
                                       readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control line-work-queue-weight" name="line_work_queue_weight[]" value="" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control line-work-queue-amount"
                                       name="line_work_queue_amount[]" min="0" onchange="calinvoiceall();">
                            </td>
                            <td style="text-align: center;">
                                <div class="btn btn-danger" onclick="removeline($(this))">ลบ</div>
                            </td>
                        </tr>
                    <?php else: ?>
                    <?php $line_num =0;?>
                        <?php if ($model_line != null): ?>
                            <?php foreach ($model_line as $value): ?>
                                <?php $line_num +=1;?>
                                <tr data-var="<?=$value->id;?>">
                                    <td style="text-align: center;"><?=$line_num?></td>
                                    <td>
                                        <input type="hidden" class="line-rec-id" name="line_rec_id[]" value="<?=$value->id?>">
                                        <input type="hidden" class="form-control line-work-queue-id"
                                               name="line_work_queue_id[]" value="<?=$value->work_queue_id?>">
                                        <input type="text" class="form-control line-work-queue-no"
                                               name="line_work_queue_no[]" value="<?=\backend\models\Workqueue::findNo($value->work_queue_id)?>"
                                               readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control line-work-queue-weight" name="line_work_queue_weight[]" value="" readonly>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control line-work-queue-amount"
                                               name="line_work_queue_amount[]" min="0" value="<?=$value->total_amount?>" onchange="calinvoiceall();">
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="btn btn-danger" onclick="removeline($(this))">ลบ</div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr data-var="">
                                <td style="text-align: center;"></td>
                                <td>
                                    <input type="hidden" class="line-rec-id" name="line_rec_id[]" value="0">
                                    <input type="hidden" class="form-control line-work-queue-id"
                                           name="line_work_queue_id[]">
                                    <input type="text" class="form-control line-work-queue-no"
                                           name="line_work_queue_no[]"
                                           readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control line-work-queue-weight" name="line_work_queue_weight[]" value="" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control line-work-queue-amount"
                                           name="line_work_queue_amount[]" min="0" onchange="calinvoiceall();">
                                </td>
                                <td style="text-align: center;">
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
                        <td style="text-align: right;" colspan="2">รวมเงิน</td>
                        <td>
                            <input type="text" class="form-control all-total" value="" readonly>
                            <input type="hidden" class="all-total-save" name="all_total" value="">
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>


        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <div id="findModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h3>รายการใบงาน (เลขที่ใบตั้ง)</h3>
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
                            <th>น้ำหนัก</th>
                            <th>จำนวนเงิน</th>
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
<?php
//$url_to_find_workqueue = \yii\helpers\Url::to(['preinvoice/findworkqueue'], true);
$url_to_find_workqueue = \yii\helpers\Url::to(['preinvoice/findworkqueue2'], true);
$js = <<<JS
var selecteditem = [];
var selectedorderlineid = [];
var selecteditemgroup = [];
var customer_id = 0;
var removelist = [];

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
     //   alert(customer_id);
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
            calinvoiceall();
        }
}

function addselecteditem(e) {
        var id = e.attr('data-var');
        var order_id = e.closest('tr').find('.line-find-order-id').val();
      
        ///// add new 
         var order_line_work_type_name = e.closest('tr').find('.line-find-work-type-name').val();
         var order_no = e.closest('tr').find('.line-find-order-no').val();
         var order_line_weight = e.closest('tr').find('.line-find-work-queue-weight').val();
         var order_line_amount = e.closest('tr').find('.line-find-work-queue-amount').val();
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
                obj['line_order_weight'] = order_line_weight;
                obj['line_order_amount'] = order_line_amount;
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
               //  var new_text = selecteditem[i]['line_work_type_name'] + "\\n" + "Order No."+selecteditem[i]['line_order_no'];
                   if (tr.closest("tr").find(".line-work-queue-id").val() == "") {
                  //  alert(line_prod_code);
            
                    tr.closest("tr").find(".line-work-queue-id").val(selecteditem[i]['order_id']);
                    tr.closest("tr").find(".line-work-queue-no").val(selecteditem[i]['line_order_no']);
                    tr.closest("tr").find(".line-work-queue-weight").val(selecteditem[i]['line_order_weight']);
                    tr.closest("tr").find(".line-work-queue-amount").val(selecteditem[i]['line_order_amount']);
                    //console.log(line_prod_code);
                    } else {
                       
                        var clone = tr.clone();
                        clone.closest("tr").find(".line-rec-id").val('0');
                        clone.closest("tr").find(".line-work-queue-id").val(selecteditem[i]['order_id']);
                        clone.closest("tr").find(".line-work-queue-no").val(selecteditem[i]['line_order_no']);
                        clone.closest("tr").find(".line-work-queue-weight").val(selecteditem[i]['line_order_weight']);
                        clone.closest("tr").find(".line-work-queue-amount").val(selecteditem[i]['line_order_amount']);
                      
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
  
      $("#table-list tbody tr").each(function () {
           var line_amt = $(this).find('.line-work-queue-amount').val();
         //  alert(line_amt);
           if(line_amt != null){
               total_amt = parseFloat(total_amt) + parseFloat(line_amt);
              
           }
      });
   
    $(".all-total").val(parseFloat(total_amt).toFixed(2));
    $(".all-total-save").val(parseFloat(total_amt).toFixed(2));
   
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