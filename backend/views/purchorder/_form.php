<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Purchorder $model */
/** @var yii\widgets\ActiveForm $form */
$qty_all_total = 0;
$all_total = 0;
$warehouse_data = \backend\models\Warehouse::find()->where(['status' => 1])->all();
?>

    <div class="purchorder-form">

        <?php $form = ActiveForm::begin(); ?>
        <input type="hidden" name="removelist" class="remove-list" value="">
        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'purch_no')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-3">
                <?php $model->trans_date = $model->isNewRecord ? date('Y-m-d') : date('d-m-Y', strtotime($model->trans_date)) ?>
                <?= $form->field($model, 'trans_date')->widget(\kartik\date\DatePicker::className(), [
                    'value' => date('Y-m-d'),
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy'
                    ]
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'vendor_id')->widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Vendor::find()->all(), 'id', function ($data) {
                        return $data->name;
                    }),
                    'options' => [
                        'placeholder' => '--เลือกผู้ขาย--'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'purch_by')->widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->all(), 'id', function ($data) {
                        return $data->fname . ' ' . $data->lname;
                    }),
                    'options' => [
                        'placeholder' => '--เลือกผู้สั่งซื้อ--'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'reason')->textarea(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-3">
                <label for="">สถานะ</label>
                <input type="text" class="form-control"
                       value="<?= $model->isNewRecord ? 'Open' : \backend\helpers\PurchStatus::getTypeById($model->status) ?>"
                       readonly>
            </div>
        </div>

        <br/>

        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered" id="table-list">
                    <thead>
                    <tr>
                        <th style="width: 5%;text-align: center;">#</th>
                        <th style="width: 15%;text-align: center;">รหัสสินค้า</th>
                        <th>รายละเอียด</th>
                        <th style="width:12%;text-align: right;">จำนวน</th>
                        <th style="width:12%;text-align: right;">ราคา</th>
                        <th style="width:12%;text-align: right;">รวม</th>
                        <th style="width: 5%;text-align: center;">-</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($model->isNewRecord): ?>
                        <tr>
                            <td style="text-align: center;"></td>
                            <td>
                                <input type="hidden" class="form-control line-item-id" name="line_product_id[]"
                                       value="">
                                <input type="text" class="form-control line-item-code" name="line_product_code[]"
                                       value="" readonly>
                            </td>
                            <td><input type="text" class="form-control line-item-name" name="line_product_name[]"
                                       value="" readonly></td>
                            <td><input type="number" class="form-control line-item-qty" name="line_qty[]" value=""
                                       onchange="linecal($(this))"></td>
                            <td><input type="number" class="form-control line-item-price" name="line_price[]" value=""
                                       onchange="linecal($(this))">
                            </td>
                            <td><input type="text" class="form-control line-item-total" name="line_total[]" value=""
                                       readonly></td>
                            <td style="text-align: center;">
                                <div class="btn btn-sm btn-danger" onclick="removeline($(this))"><i
                                            class="fa fa-trash"></i>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $line_num = 0; ?>
                        <?php if ($model_line != null): ?>
                            <?php foreach ($model_line as $value): ?>
                                <?php
                                $line_num += 1;
                                $qty_all_total += $value->qry;
                                $all_total += $value->line_total;
                                ?>
                                <tr data-var="<?= $value->id ?>">
                                    <td style="text-align: center;"><?= $line_num ?></td>
                                    <td>
                                        <input type="hidden" class="form-control line-item-id" name="line_product_id[]"
                                               value="<?= $value->product_id ?>">
                                        <input type="text" class="form-control line-item-code"
                                               name="line_product_code[]"
                                               value="<?= \backend\models\Product::findCode($value->product_id) ?>"
                                               readonly>
                                    </td>
                                    <td><input type="text" class="form-control line-item-name"
                                               name="line_product_name[]"
                                               value="<?= \backend\models\Product::findName($value->product_id) ?>"
                                               readonly></td>
                                    <td><input type="number" class="form-control line-item-qty" name="line_qty[]"
                                               value="<?= $value->qry ?>" onchange="linecal($(this))"></td>
                                    <td><input type="number" class="form-control line-item-price" name="line_price[]"
                                               value="<?= $value->line_price ?>" onchange="linecal($(this))">
                                    </td>
                                    <td><input type="text" class="form-control line-item-total" name="line_total[]"
                                               value="<?= $value->line_total ?>"
                                               readonly></td>
                                    <td style="text-align: center;">
                                        <div class="btn btn-sm btn-danger" onclick="removeline($(this))"><i
                                                    class="fa fa-trash"></i>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td style="text-align: center;"></td>
                                <td>
                                    <input type="hidden" class="form-control line-item-id" name="line_product_id[]"
                                           value="">
                                    <input type="text" class="form-control line-item-code" name="line_product_code[]"
                                           value="" readonly>
                                </td>
                                <td><input type="text" class="form-control line-item-name" name="line_product_name[]"
                                           value="" readonly></td>
                                <td><input type="number" class="form-control line-item-qty" name="line_qty[]" value=""
                                           onchange="linecal($(this))">
                                </td>
                                <td><input type="number" class="form-control line-item-price" name="line_price[]"
                                           value="" onchange="linecal($(this))">
                                </td>
                                <td><input type="text" class="form-control line-item-total" name="line_total[]" value=""
                                           readonly></td>
                                <td style="text-align: center;">
                                    <div class="btn btn-sm btn-danger" onclick="removeline($(this))"><i
                                                class="fa fa-trash"></i>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>

                    <?php endif; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td style="text-align: center;">
                            <div class="btn btn-sm btn-primary" onclick="finditem();"><i class="fa fa-plus"></i></div>
                        </td>
                        <td colspan="2" style="text-align: right">รวม</td>
                        <td>
                            <input type="text" class="form-control qty-all-total" value="<?= $qty_all_total ?>"
                                   readonly>
                        </td>
                        <td></td>
                        <td>
                            <input type="text" class="form-control all-total" value="<?= $all_total ?>" readonly>
                        </td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="btn-group">

                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    <?php if ($model->status == 1): ?>
                        <div class="btn btn-warning" onclick="showporec();">รับสินค้า</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <div id="findModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h3>รายการสินค้า/อะไหล่</h3>
                </div>
                <!--            <div class="modal-body" style="white-space:nowrap;overflow-y: auto">-->
                <!--            <div class="modal-body" style="white-space:nowrap;overflow-y: auto;scrollbar-x-position: top">-->

                <div class="modal-body">
                    <input type="hidden" name="line_qc_product" class="line_qc_product" value="">
                    <table class="table table-bordered table-striped table-find-list" width="100%">
                        <thead>
                        <tr>
                            <th style="width:10%;text-align: center">เลือก</th>
                            <th style="width: 20%;text-align: center">รหัส</th>
                            <th style="width: 20%;text-align: center">รายละเอียด</th>
                            <th>คงเหลือ</th>
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
    <div id="receiveModal"
         class="modal fade"
         role="dialog">
        <div class="modal-dialog" style="max-width: 95%">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1aa67d;">
                    <div class="row"
                         style="width: 100%">
                        <div class="col-lg-11">
                            <h4 style="color: white;">
                                รับใบสั่งซื้อ</h4>
                        </div>
                        <div class="col-lg-1">
                            <button type="button"
                                    class="close"
                                    data-dismiss="modal">
                                &times;
                            </button>
                        </div>
                    </div>

                </div>
                <!--            <div class="modal-body" style="white-space:nowrap;overflow-y: auto">-->
                <!--            <div class="modal-body" style="white-space:nowrap;overflow-y: auto;scrollbar-x-position: top">-->

                <div class="modal-body">
                    <form action="<?= \yii\helpers\Url::to(['purchorder/savereceive'], true) ?>" method="post"
                          id="form-receive">
                        <input type="hidden" class="po-id" name="po_id" value="<?= $model->id; ?>">
                        <table class="table table-bordered table-striped table-receive-list"
                               width="100%">
                            <thead>
                            <tr>
                                <th>
                                    รหัสสินค้า
                                </th>
                                <th>
                                    รายละเอียด
                                </th>
                                <th style="width: 8%">
                                    จำนวนซื้อ
                                </th>
                                <th style="text-align: right;width: 8%">
                                    ค้างรับ
                                </th>
                                <th style="text-align: right;width: 8%">
                                    รับเข้า
                                </th>
                                <th style="text-align: right;width: 15%">
                                    คลังสินค้า
                                </th>
                                <th style="text-align: right;width: 8%">
                                    ราคารับเข้า
                                </th>
                                <th>
                                    ยกเลิก
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $line_count = 0 ?>
                            <?php if (!$model->isNewRecord): ?>

                                <?php foreach ($model_line as $xvalue): ?>
                                    <?php if ($xvalue->remain_qty <= 0) continue; ?>
                                    <?php $line_count += 1 ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" class="line-receive-id" name="line_receive_id[]"
                                                   value="<?= $xvalue->id ?>">
                                            <input type="hidden" class="line-receive-product-id"
                                                   name="line_receive_product_id[]" value="<?= $xvalue->product_id ?>">
                                            <input type="text" class="form-control"
                                                   value="<?= \backend\models\Product::findCode($xvalue->product_id) ?>"
                                                   readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control"
                                                   value="<?= \backend\models\Product::findName($xvalue->product_id) ?>"
                                                   readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="<?= $xvalue->qry ?>">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="<?= $xvalue->remain_qty ?>" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="line_receive_qty[]"
                                                   value="<?= $xvalue->remain_qty ?>">
                                        </td>
                                        <td>
                                            <select name="line_warehouse_id[]" class="form-control" id="">
                                                <?php foreach ($warehouse_data as $xvalue): ?>
                                                    <option value="<?=$xvalue->id?>"><?=$xvalue->name;?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="line_receive_price[]"
                                                   value="0">
                                        </td>
                                        <td>
                                            <div class="btn btn-secondary" onclick="cancelrecline($(this))">ยกเลิก</div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </form>
                </div>

                <div class="modal-footer">
                    <?php if ($line_count > 0): ?>
                        <button class="btn btn-outline-success btn-receive"
                                data-dismiss="modalx" onclick="savereceive();">
                            <i
                                    class="fa fa-check"></i>
                            ตกลง
                        </button>
                    <?php endif; ?>
                    <button type="button"
                            class="btn btn-default"
                            data-dismiss="modal">
                        <i
                                class="fa fa-close text-danger"></i>
                        ปิดหน้าต่าง
                    </button>
                </div>
            </div>

        </div>
    </div>
<?php
//$url_to_find_workqueue = \yii\helpers\Url::to(['preinvoice/findworkqueue'], true);
$url_to_find_item = \yii\helpers\Url::to(['purchorder/finditem'], true);
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
function showporec(){
    $("#receiveModal").modal('show');
}
function savereceive(){
    $("form#form-receive").submit();
}
function finditem(){
     //   alert(customer_id);
        $.ajax({
          type: 'post',
          dataType: 'html',
          url:'$url_to_find_item',
          async: false,
          data: {},
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
                        $(this).find(".line-item-qty").val('');
                        $(this).find(".line-item-price").val('');
                        $(this).find(".line-item-total").val('');
                        // cal_num();
                    });
                } else {
                    e.parent().parent().remove();
                }
                // cal_linenum();
                // cal_all();
                calall();
            }
        
        
}

function addselecteditem(e) {
        var id = e.attr('data-var');
        var item_id = e.closest('tr').find('.line-find-item-id').val();
      
        ///// add new 
         var item_code = e.closest('tr').find('.line-find-item-code').val();
         var item_name = e.closest('tr').find('.line-find-item-name').val();
         var onhand = e.closest('tr').find('.line-find-onhand-qty').val();
        ///////
        if (id) {
            // if(checkhasempdaily(id)){
            //     alert("คุณได้ทำการจัดรถให้พนักงานคนนี้ไปแล้ว");
            //     return false;
            // }
            if (e.hasClass('btn-outline-success')) {
                var obj = {};
                obj['id'] = id;
                obj['item_id'] = item_id;
                obj['item_code'] = item_code;
                obj['item_name'] = item_name;
                obj['qty'] = onhand;
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
                        selecteditem.splice(i, 1);
                        selectedorderlineid.splice(i,1);
                      //  deleteorderlineselected(product_group_id, qty); // update data in selected list
                        console.log(selecteditemgroup);
                      //  caltablecontent(); // refresh table below
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
                   if (tr.closest("tr").find(".line-item-id").val() == "") {
                  //  alert(line_prod_code);
            
                    tr.closest("tr").find(".line-item-id").val(selecteditem[i]['item_id']);
                    tr.closest("tr").find(".line-item-code").val(selecteditem[i]['item_code']);
                    tr.closest("tr").find(".line-item-name").val(selecteditem[i]['item_name']);
                    tr.closest("tr").find(".line-item-onhand").val(selecteditem[i]['onhand']);
                    //console.log(line_prod_code);
                    } else {
                       
                        var clone = tr.clone();
                        clone.closest("tr").find(".line-rec-id").val('0');
                        clone.closest("tr").find(".line-item-id").val(selecteditem[i]['item_id']);
                        clone.closest("tr").find(".line-item-code").val(selecteditem[i]['item_code']);
                        clone.closest("tr").find(".line-item-name").val(selecteditem[i]['item_name']);
                        clone.closest("tr").find(".line-item-onhand").val(selecteditem[i]['onhand']);
                      
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
function linecal(e){
   var qty = e.closest("tr").find(".line-item-qty").val();
   var price = e.closest("tr").find(".line-item-price").val();
   var line_total = parseFloat(qty) * parseFloat(price);
   e.closest("tr").find(".line-item-total").val(parseFloat(line_total));
   calall();
}
function calall(){
    
    var total_amt = 0;
    var total_qty = 0;
  
      $("#table-list tbody tr").each(function () {
           var line_qty = $(this).find('.line-item-qty').val();
           var line_amt = $(this).find('.line-item-total').val();
         //  alert(line_amt);
           if(line_qty != null){
               total_qty = parseFloat(total_qty) + parseFloat(line_qty);
           }
           if(line_amt != null){
               total_amt = parseFloat(total_amt) + parseFloat(line_amt);
           }
      });
   
    $(".all-total").val(parseFloat(total_amt).toFixed(2));
    $(".qty-all-total").val(parseFloat(total_qty).toFixed(2));
   
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