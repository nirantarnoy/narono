<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$model_province = \backend\models\Province::find()->all();
$model_cityzone = \backend\models\Cityzone::find()->all();
$model_dropoff = \backend\models\DropoffPlace::find()->all();

/** @var yii\web\View $this */
/** @var backend\models\Quotationtitle $model */
/** @var yii\widgets\ActiveForm $form */

$model_quotation_dropoff = null;
$selected_dropoff = [];

$price_type_data = [['id' => 0, 'name' => 'ไม่เหมา'], ['id' => 1, 'name' => 'ราคาเหมา']];
?>

    <div class="quotationtitle-form">

        <?php $form = ActiveForm::begin(); ?>
        <?php
        $model_quotation_dropoff = \common\models\QuotationDropoff::find()->select(['dropoff_id'])->where(['quotation_rate_id' => $model->id])->all();
        if($model_quotation_dropoff != null){
            foreach ($model_quotation_dropoff as $value) {
                array_push($selected_dropoff,$value->dropoff_id);
            }
        }
        ?>
        <input type="hidden" class="remove-line-list" value="">
        <div class="row">
            <div class="col-lg-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'car_type_id')->widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\CarType::find()->all(), 'id', 'name'),
                    'options' => [
                        'placeholder' => '--เลือกประเภทรถ--'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]

                ]) ?>
            </div>
            <div class="col-lg-3">
                <?php $model->created_at_display = $model->created_at != null ? date('d-m-Y H:i:s', $model->created_at) : '' ?>
                <?= $form->field($model, 'created_at_display')->textInput(['readonly' => 'readonly']) ?>
            </div>
            <div class="col-lg-3">
                <?php $model->created_by_display = $model->created_by != null ? \backend\models\User::findName($model->created_by) : '' ?>
                <?= $form->field($model, 'created_by_display')->textInput(['readonly' => 'readonly']) ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'fuel_rate')->textInput() ?>
            </div>
            <div class="col-lg-3">
                <!--            <select name="xx" class="form-control input-lg" data-live-search="true" id="provice-selected">-->
                <!--                <option>Mustard</option>-->
                <!--                <option>Ketchup</option>-->
                <!--                <option>Barbecue</option>-->
                <!--            </select>-->
            </div>

        </div>
        <div class="row">
            <div class="col-lg-1"><label for="">จุดขึ้นสินค้า</label></div>
            <div class="col-lg-11">
                <?php
                echo \kartik\select2\Select2::widget([
                    'name' => 'drop_off_id',
                    'value' => $selected_dropoff,
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\DropoffPlace::find()->where(['status' => 1])->all(), 'id', 'name'),
                    'options' => ['placeholder' => 'เลือกจุดขึ้นสินค้า', 'id' => 'drop-off-id'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true,
                    ]
                ]);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'price_type_id')->widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map($price_type_data, 'id', 'name'),
                    'options' => [
                        'placeholder' => '--เลือกประเภทราคา--'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]

                ]) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    <?php if ($model_line != null): ?>
                        <div class="btn btn-warning" onclick="printquotationview()"><i class="fa fa-print"></i> พิมพ์
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
                <br/>
        <div class="row">
            <div class="col-lg-12">
                <h4>รายละเอียด</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered table-striped" id="table-list">
                    <thead>
                    <tr>
                        <th>จังหวัด</th>
                        <th>Route</th>
                        <th>โซนพื้นที่</th>
<!--                        <th>ที่ขึ้นสินค้า</th>-->
                        <th>ระยะทาง</th>
                        <th>ปริมาณเฉลี่ยตัน/ปี</th>
                        <th>ราคาที่เสนอ</th>
                        <th>ประเภทราคา</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($model->isNewRecord): ?>
                        <tr data-var="">
                            <td>
                                <div class="form-group">
                                    <select name="line_warehouse_id[]" class="form-control line-warehouse-id input-lg"
                                            data-live-search="true" id="provice-selected"
                                            onchange="updatevalidate($(this))">
                                        <option value="-1">--เลือกจังหวัด--</option>
                                        <?php foreach ($model_province as $valuex): ?>
                                            <?php
                                            $selected = '';
                                            if ($valuex->PROVINCE_ID == 1) {
                                                $selected = 'selected';
                                            }
                                            ?>
                                            <option value="<?php echo $valuex->PROVINCE_ID ?>" <?php echo $selected ?>><?php echo $valuex->PROVINCE_NAME ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control line-route" name="line_route[]">
                            </td>
                            <td>
                                <select name="line_zone_id[]" class="form-control line-zone-id" id="">
                                    <option value="-1">--เลือกโซน--</option>
                                </select>
                            </td>
<!--                            <td>-->
<!--                                <select name="line_drop_off_id[]" class="form-control line-drop-off-id" id="">-->
<!--                                    <option value="-1">--ที่ขั้นสินค้า--</option>-->
<!--                                </select>-->
<!--                            </td>-->

                            <td>
                                <input type="number" class="form-control line-distance" name="line_distance[]" min="0">
                            </td>
                            <td>
                                <input type="number" class="form-control line-average" name="line_average[]" min="0">
                            </td>
                            <td>
                                <input type="text" class="form-control line-quotation-price"
                                       name="line_quotation_price[]">
                            </td>
                            <td>
                                <select name="line_quotation_price_type_id[]" class="form-control" id="">
                                    <?php for ($i = 0; $i <= count($price_type_data) - 1; $i++): ?>

                                        <option value="<?= $price_type_data[$i]['id'] ?>"><?= $price_type_data[$i]['name'] ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td>
                                <div class="btn btn-danger btn-sm" onclick="removeline($(this))">ลบ</div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php if ($model_line != null): ?>
                            <?php foreach ($model_line as $value): ?>
                                <tr data-var="<?= $value->id ?>">
                                    <td>
                                        <input type="hidden" class="line-rec-id" value="<?= $value->id ?>">
                                        <select name="line_warehouse_id[]" class="form-control line-warehouse-id" id=""
                                                onchange="updatevalidate($(this))">
                                            <option value="-1">--เลือกจังหวัด--</option>
                                            <?php foreach ($model_province as $valuex): ?>
                                                <?php
                                                $selected = '';
                                                if ($valuex->PROVINCE_ID == $value->province_id) {
                                                    $selected = 'selected';
                                                }
                                                ?>
                                                <option value="<?= $valuex->PROVINCE_ID ?>" <?= $selected ?>><?= $valuex->PROVINCE_NAME ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control line-route" name="line_route[]"
                                               value="<?= $value->route_code ?>">
                                    </td>
                                    <td>
                                        <select name="line_zone_id[]" class="form-control line-zone-id" id="">
                                            <option value="-1">--เลือกโซน--</option>
                                            <?php foreach ($model_cityzone as $valuex): ?>
                                                <?php
                                                $selected = '';
                                                if ($valuex->id == $value->zone_id) {
                                                    $selected = 'selected';
                                                }
                                                ?>
                                                <option value="<?= $valuex->id ?>" <?= $selected ?>><?= getCityzonedetail($valuex->id) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
<!--                                    <td>-->
<!--                                        --><?php
//                                        $dropoff_active = 'btn-outline-success';
//                                        $cnt_dropoff = countDropoff($value->id);
//                                        if($cnt_dropoff > 0){
//                                            $dropoff_active = 'btn-success';
//                                        }
//                                        ?>
<!--                                        <div class="btn btn-sm --><?php //=$dropoff_active?><!--" data-var="--><?php //= $value->id ?><!--"-->
<!--                                             onclick="adddropoff($(this))">ที่ขึ้นสินค้า-->
<!--                                        </div>-->
<!--                                    </td>-->
                                    <td>
                                        <input type="number" class="form-control line-distance" name="line_distance[]"
                                               min="0" value="<?= $value->distance ?>">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control line-average" name="line_average[]"
                                               min="0"
                                               value="<?= $value->load_qty ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control line-quotation-price"
                                               name="line_quotation_price[]" value="<?= $value->price_current_rate ?>">
                                    </td>
                                    <td>
                                        <select name="line_quotation_price_type_id[]" class="form-control" id="">
                                            <?php for ($i = 0; $i <= count($price_type_data) - 1; $i++): ?>
                                                <?php
                                                $selected = '';
                                                if ($value->price_type_id == $price_type_data[$i]['id']) {
                                                    $selected = 'selected';
                                                }
                                                ?>
                                                <option value="<?= $price_type_data[$i]['id'] ?>" <?= $selected ?>><?= $price_type_data[$i]['name'] ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="btn btn-danger btn-sm" onclick="removeline($(this))">ลบ</div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr data-var="">
                                <td>
                                    <select name="line_warehouse_id[]" class="form-control line-warehouse-id" id=""
                                            onchange="updatevalidate($(this))">
                                        <option value="-1">--เลือกจังหวัด--</option>
                                        <?php foreach ($model_province as $valuex): ?>
                                            <?php
                                            $selected = '';
                                            if ($valuex->PROVINCE_ID == 1) {
                                                $selected = 'selected';
                                            }
                                            ?>
                                            <option value="<?= $valuex->PROVINCE_ID ?>" <?= $selected ?>><?= $valuex->PROVINCE_NAME ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control line-route" name="line_route[]" value="xx">
                                </td>
                                <td>
                                    <select name="line_zone_id[]" class="form-control line-zone-id" id="">
                                        <option value="-1">--เลือกโซน--</option>
                                    </select>
                                </td>
<!--                                <td>-->
<!--                                    <select name="line_drop_off_id[]" class="form-control line-drop-off-id" id="">-->
<!--                                        <option value="-1">--ที่ขั้นสินค้า--</option>-->
<!--                                    </select>-->
<!--                                </td>-->
                                <td>
                                    <input type="number" class="form-control line-distance" name="line_distance[]"
                                           min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control line-average" name="line_average[]"
                                           min="0">
                                </td>
                                <td>
                                    <input type="text" class="form-control line-quotation-price"
                                           name="line_quotation_price[]">
                                </td>
                                <td>
                                    <select name="line_quotation_price_type_id[]" class="form-control" id="">
                                        <?php for ($i = 0; $i <= count($price_type_data) - 1; $i++): ?>

                                            <option value="<?= $price_type_data[$i]['id'] ?>"><?= $price_type_data[$i]['name'] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td>
                                    <div class="btn btn-danger btn-sm" onclick="removeline($(this))">ลบ</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>

                    </tbody>
                    <tfoot>
                    <tr>
                        <td>
                            <div class="btn btn-primary btn-sm" onclick="addline($(this))"><i class="fa fa-plus"></i>
                                เพิ่มรายการ
                            </div>
                        </td>
                        <td></td>
                        <td></td>
<!--                        <td></td>-->
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <form id="form-print" action="<?= \yii\helpers\Url::to(['quotationtitle/printquotationview'], true) ?>"
          method="post">
        <input type="hidden" value="<?= $model->id ?>" name="quotation_id">
    </form>

    <div id="finddropoffModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                    <h3>เพิ่มจุดขึ้นสินค้า</h3>
                </div>
                <!--            <div class="modal-body" style="white-space:nowrap;overflow-y: auto">-->
                <!--            <div class="modal-body" style="white-space:nowrap;overflow-y: auto;scrollbar-x-position: top">-->
                <form action="<?= \yii\helpers\Url::to(['quotationtitle/savedropoff'], true) ?>" method="post">
                    <input type="hidden" class="current-quote-rate-id" name="quotation_rate_id" value="">
                    <input type="hidden" class="current-quote-id" name="quotation_id" value="<?=$model->id;?>">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-3"><label for="">จุดขึ้นสินค้า</label></div>
                            <div class="col-lg-9">
                                <?php
                                echo \kartik\select2\Select2::widget([
                                    'name' => 'drop_off_id',
                                    'value' => '',
                                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\DropoffPlace::find()->where(['status' => 1])->all(), 'id', 'name'),
                                    'options' => ['placeholder' => 'เลือกจุดขึ้นสินค้า', 'id' => 'drop-off-id'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'multiple' => true,
                                    ]
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-success btn-emp-selected" data-dismiss="modalx"><i
                                    class="fa fa-check"></i> ตกลง
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                    class="fa fa-close text-danger"></i> ปิดหน้าต่าง
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
<?php

function getCityzonedetail($city_zone_id)
{
    $name = '';
    if ($city_zone_id) {
        $model = \common\models\CityzoneLine::find()->where(['cityzone_id' => $city_zone_id])->all();
        if ($model) {
            foreach ($model as $value) {
                $name .= \backend\models\Amphur::findAmphurName($value->city_id) . ',';
            }
        }
    }
    return $name;
}
function countDropoff($quote_rate_id){
    $cnt = 0;
    if($quote_rate_id){
        $cnt = \common\models\QuotationDropoff::find()->where(['quotation_rate_id' => $quote_rate_id])->count();
    }
    return $cnt;
}

?>
<?php
$url_to_getzone = \yii\helpers\Url::to(['quotationtitle/getcityzone'], true);
$url_to_getprovince = \yii\helpers\Url::to(['quotationtitle/getprovince'], true);
$url_to_get_quote_rate_dropoff = \yii\helpers\Url::to(['quotationtitle/getquoteratedropoff'], true);
$js = <<<JS
var removelist = [];
$(function(){
    
   // $('#provice-selected').selectpicker();
   //loadselectdata();
});
function loadselectdata(){
    $.ajax({
      type: "post",
      dataType: "html",
      url: "$url_to_getprovince",
      // async: false,
      success: function(data){
         // alert(data);
              $('#provice-selected').html(data);   
              $('#provice-selected').selectpicker('refresh');
         }
      }); 
}
function loadselectdata2(line_id){
    $.ajax({
      type: "post",
      dataType: "html",
      url: "$url_to_getprovince",
      // async: false,
      success: function(data){
         // alert(data);
              $('#'+line_id).html(data);   
              $('#'+line_id).selectpicker('refresh');
         }
      }); 
}
function addline(e){
    var tr = $("#table-list tbody tr:last");

                if (tr.closest("tr").find(".line-warehouse-id").val() == "-1") {
                   tr.closest("tr").find(".line-warehouse-id").css("border-color","red");
                    
                } else {
                    var clone = tr.clone();
                    clone.closest("tr").find(".line-warehouse-id").val("-1").change();
                    clone.attr("data-var", "");
                    clone.find('.line-rec-id').val("");
                    
                    clone.find(":text").val("");
                    clone.find(':input[type="number"]').val("");
                    clone.closest("tr").find(".line-warehouse-id").attr("id","id2");
                    
                    clone.find(".line-quotation-price").on("keypress", function (event) {
                        $(this).val($(this).val().replace(/[^0-9\.]/g, ""));
                        if ((event.which != 46 || $(this).val().indexOf(".") != -1) && (event.which < 48 || event.which > 57)) {
                            event.preventDefault();
                        }
                    });

                    tr.after(clone);
                    // loadselectdata2("id2")
                    //cal_num();
                }
}
function removeline(e) {
        // alert();
        if (confirm("ต้องการลบรายการนี้ใช่หรือไม่?")) {
            if (e.parent().parent().attr("data-var") != '') {
                removelist.push(e.parent().parent().attr("data-var"));
                $(".remove-line-list").val(removelist);
            }

            // alert(removelist);

            if ($("#table-list tbody tr").length == 1) {
                $("#table-list tbody tr").each(function () {
                    $(this).find(":text").val("");
                    $(this).find(':input[type="number"]').val("");
                    // $(this).find(".line-prod-photo").attr('src', '');
                    // $(this).find(".line-qty").val(1);
                    // cal_num();
                });
            } else {
                e.parent().parent().remove();
            }
        }
    }
 function updatevalidate(e){
    if(e.val() != "-1"){
        e.css("border-color","");
       
        var province_id = e.val();
        if(province_id > 0){
            // alert(province_id);
                     $.ajax({
                       type: "post",
                       dataType: "html",
                       url: "$url_to_getzone",
                       async: false,
                       data: {'province_id': province_id},
                       success: function(data){
                           //if(data != ''){
                                 // alert('created do');
                            e.closest("tr").find(".line-zone-id").html(data);
                           
                           //}
                       }
                    }); 
        }
        
    }
 }   
 function printquotationview(){
    $("form#form-print").submit();
 }
 
 function adddropoff(e){
     $(".current-quote-rate-id").val(e.attr('data-var'));
     var id = e.attr('data-var');
     if(id !=''){
         $.ajax({
             type: "post",
             dataType: "html",
             url: "$url_to_get_quote_rate_dropoff",
             async: true,
             data: {'quote_rate_id': id},
             success: function(data){
                 //alert(data);
                 $("#drop-off-id").html(data);
             }
     });
     }
     $("#finddropoffModal").modal("show");
     
 }
JS;
$this->registerJs($js, static::POS_END);
?>