<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Routeplan $model */
/** @var yii\widgets\ActiveForm $form */

$dropoff_place_data = \common\models\DropoffPlace::find()->all();

$car_type_data = \common\models\CarType::find()->all();
$car_trail_type_data = \common\models\Car::find()->where(['type_id'=>2])->all();

//print_r($dropoff_place_data);return;

?>

    <div class="routeplan-form">

        <?php $form = ActiveForm::begin(); ?>
        <input type="hidden" class="remove-list" name="remove_list" value="">
        <input type="hidden" class="remove-list2" name="remove_list2" value="">
        <div class="row">

            <div class="col-lg-4">
                <?= $form->field($model, 'des_province_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Province::find()->all(), 'PROVINCE_ID', function ($data) {
                        return $data->PROVINCE_NAME;
                    }),
                    'options' => [
                        'placeholder' => '--ปลายทาง--'
                    ]
                ]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'customer_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Customer::find()->all(), 'id', 'name'),
                    'options' => [
                        'placeholder' => '--ลูกค้า--'
                    ]
                ]) ?>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'total_distanct')->textInput() ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'total_distance_back')->textInput() ?>
                <?php //echo $form->field($model, 'hp')->textInput() ?>
            </div>
            <?php //echo $form->field($model, 'oil_rate_qty')->textInput() ?>
            <div class="col-lg-4">
                <?= $form->field($model, 'item_back_id')->Widget(\kartik\select2\Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\Item::find()->all(), 'id', 'name'),
                    'options' => [
                        'placeholder' => '--ของนำกลับ--'
                    ]
                ]) ?>
            </div>
        </div>

        <!--        <div class="row">-->
        <!--            <div class="col-lg-4">-->
        <?php //echo $form->field($model, 'car_type_id')->Widget(\kartik\select2\Select2::className(), [
        //                    'data' => \yii\helpers\ArrayHelper::map(\backend\models\CarType::find()->all(), 'id', 'name'),
        //                    'options' => [
        //                        'placeholder' => '--ประเภทรถ--'
        //                    ]
        //                ]) ?>
        <!--            </div>-->
        <!--            <div class="col-lg-4">-->
        <!--                --><?php ////echo $form->field($model, 'labour_price')->textInput() ?>
        <!--            </div>-->
        <!--            <div class="col-lg-4">-->
        <!--                --><?php ////echo $form->field($model, 'express_road_price')->textInput() ?>
        <!--            </div>-->
        <!--        </div>-->

        <?php echo $form->field($model, 'status')->widget(\toxor88\switchery\Switchery::className(), ['options' => ['label' => '', 'class' => 'form-control']])->label() ?>
        <h5>เรทน้ำมัน</h5>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered table-striped" id="table-list">
                    <thead>
                    <th>จุดขึ้นสินค้า</th>
                    <th style="width: 10%">ประเภทรถ</th>
                    <th>แรงรถ</th>
                    <th>เรทน้ำมันหนัก</th>
                    <th style="width: 10%">จำนวนบวกเพิ่ม (ลิตร)</th>
                    <th style="width: 10%">เรทน้ำมันเบา</th>
                    <th style="width: 10%">จำนวนลิตร (ไป)</th>
                    <th style="width: 10%">จำนวนลิตร (กลับ)</th>
                    <th style="width: 10%">รวมจำนวนลิตร</th>
                    <th style="width: 5%"></th>
                    </thead>
                    <tbody>
                    <?php if ($model->isNewRecord): ?>
                        <tr>
                            <td style="width: 10%">
                                <select name="drop_off_place[]" class="form-control drop-off-place" id=""
                                        onchange="getDropoffinfo($(this))">
                                    <option value="0">--จุดขึ่นสินค้า--</option>
                                    <?php for ($i = 0; $i <= count($dropoff_place_data) - 1; $i++) : ?>
                                        <option value="<?= $dropoff_place_data[$i]['id'] ?>"><?= $dropoff_place_data[$i]['name'] ?></option>
                                    <?php endfor; ?>
                                </select>
                                <!--                                <input type="text" class="form-control drop-off-place" name="drop_off_place[]">-->
                            </td>
                            <td style="width: 10%">
                                <select name="car_type[]" class="form-control car-type" id=""
                                        onchange="">
                                    <option value="0">--ประเภทรถ--</option>
                                    <?php for ($i = 0; $i <= count($car_type_data) - 1; $i++) : ?>
                                        <option value="<?= $car_type_data[$i]['id'] ?>"><?= $car_type_data[$i]['name'] ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td style="width: 10%">
                                <input type="text" class="form-control hp" name="hp[]" readonly>
                            </td>
                            <td style="width: 10%">
                                <input type="text" class="form-control oil-rate" name="oil_rate[]"
                                       onchange="callLine($(this))">
                            </td>
                            <td style="width: 10%">
                                <input type="text" class="form-control drop-off-qty" name="drop_off_qty[]"
                                       onchange="callLine($(this))">
                            </td>

                            <td style="width: 10%">
                                <input type="text" class="form-control lite-oil-rate" name="lite_oil_rate[]"
                                       onchange="callLine($(this))">
                            </td>
                            <td style="width: 10%">
                                <input type="text" class="form-control count-go" name="count_go[]"
                                       onchange="callLine($(this))" readonly>
                            </td>
                            <td style="width: 10%">
                                <input type="text" class="form-control count-back" name="count_back[]"
                                       onchange="callLine($(this))" readonly>
                            </td>
                            <td style="width: 10%">
                                <input type="text" class="form-control total" name="total[]">
                            </td>
                            <td>
                                <div class="btn btn-danger btn-sm" onclick="removeline($(this))"><i
                                            class="fa fa-trash"></i></div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php if (count($model_line)): ?>
                            <?php foreach ($model_line as $value) : ?>
                                <?php $data = \backend\models\DropoffPlace::getinfo($value->dropoff_place_id) ?>
                                <tr data-var="<?= $value->id ?>">
                                    <td>
                                        <input type="hidden" class="rec-id" name="rec_id[]" value="<?= $value->id ?>">
                                        <select name="drop_off_place[]" class="form-control drop-off-place"
                                                onchange="getDropoffinfo($(this))">
                                            <option value="0">--จุดขึ่นสินค้า--</option>
                                            <?php for ($i = 0; $i <= count($dropoff_place_data) - 1; $i++) : ?>
                                                <?php
                                                $selected = '';
                                                if ($dropoff_place_data[$i]['id'] == $value->dropoff_place_id) {
                                                    $selected = 'selected';
                                                }
                                                ?>
                                                <option value="<?= $dropoff_place_data[$i]['id'] ?>" <?= $selected ?>><?= $dropoff_place_data[$i]['name'] ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                    <td style="width: 10%">
                                        <select name="car_type[]" class="form-control car-type" id=""
                                                onchange="">
                                            <option value="0">--ประเภทรถ--</option>
                                            <?php for ($i = 0; $i <= count($car_type_data) - 1; $i++) : ?>
                                                <?php $selected = '';
                                                if ($car_type_data[$i]['id'] == $value->car_type) $selected = 'selected';
                                                ?>

                                                <option value="<?= $car_type_data[$i]['id'] ?>" <?= $selected ?>><?= $car_type_data[$i]['name'] ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                    <td>

                                        <?php //echo $data != null ? $data[0]['hp'] : 0 ?>
                                        <input type="text" class="form-control hp" name="hp[]"
                                               value="<?= $value->hp ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control oil-rate" name="oil_rate[]"
                                               value="<?= $value->oil_rate ?>" onchange="callLine($(this))">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control drop-off-qty" name="drop_off_qty[]"
                                               onchange="callLine($(this))"
                                               value="<?= $value->dropoff_qty ?>">
                                    </td>

                                    <td style="width: 10%">
                                        <input type="text" class="form-control lite-oil-rate" name="lite_oil_rate[]"
                                               value="<?= $value->lite_oil_rate ?>" onchange="callLine($(this))">
                                    </td>
                                    <td style="width: 10%">
                                        <input type="text" class="form-control count-go" name="count_go[]"
                                               onchange="callLine($(this))" readonly
                                               value="<?= $value->count_go ?>">
                                    </td>
                                    <td style="width: 10%">
                                        <input type="text" class="form-control count-back" name="count_back[]"
                                               onchange="callLine($(this))" readonly
                                               value="<?= $value->count_back ?>">
                                    </td>
                                    <td style="width: 10%">
                                        <input type="text" class="form-control total" name="total[]" value="<?= $value->total ?>">
                                    </td>
                                    <td>
                                        <div class="btn btn-danger btn-sm" onclick="removeline($(this))"><i
                                                    class="fa fa-trash"></i></div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td>
                                    <select name="drop_off_place[]" class="form-control drop-off-place" id=""
                                            onchange="getDropoffinfo($(this))">
                                        <option value="0">--จุดขึ่นสินค้า--</option>
                                        <?php for ($i = 0; $i <= count($dropoff_place_data) - 1; $i++) : ?>
                                            <option value="<?= $dropoff_place_data[$i]['id'] ?>"><?= $dropoff_place_data[$i]['name'] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td style="width: 10%">
                                    <select name="car_type[]" class="form-control car-type" id=""
                                            onchange="">
                                        <option value="0">--ประเภทรถ--</option>
                                        <?php for ($i = 0; $i <= count($car_type_data) - 1; $i++) : ?>
                                            <option value="<?= $car_type_data[$i]['id'] ?>"><?= $car_type_data[$i]['name'] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control hp" name="hp[]">
                                </td>
                                <td>
                                    <input type="text" class="form-control oil-rate" name="oil_rate[]"
                                           onchange="callLine($(this))">
                                </td>
                                <td>
                                    <input type="text" class="form-control drop-off-qty" name="drop_off_qty[]"
                                           onchange="callLine($(this))">
                                </td>
                                <td style="width: 10%">
                                    <input type="text" class="form-control lite-oil-rate" name="lite_oil_rate[]"
                                           onchange="callLine($(this))">
                                </td>
                                <td style="width: 10%">
                                    <input type="text" class="form-control count-go" name="count_go[]"
                                           onchange="callLine($(this))" readonly>
                                </td>
                                <td style="width: 10%">
                                    <input type="text" class="form-control count-back" name="count_back[]"
                                           onchange="callLine($(this))" readonly>
                                </td>
                                <td style="width: 10%">
                                    <input type="text" class="form-control total" name="total[]">
                                </td>
                                <td>
                                    <div class="btn btn-danger btn-sm" onclick="removeline($(this))"><i
                                                class="fa fa-trash"></i></div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4">
                            <div class="btn btn-primary"
                                 onclick="addline($(this))">
                                <i class="fa fa-plus-circle"></i>
                            </div>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>

        </div>

        <br/>
        <h5>กำหนดค่าเที่ยว</h5>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered table-striped" id="table-list2">
                    <thead>
                    <th>ประเภทรถ</th>
                    <th>ค่าเที่ยว</th>
                    <th>ทะเบียนหาง</th>
                    <th>ค่าเที่ยวพิเศษ</th>
                    <th>ค่าทางด่วน</th>
                    <th>คลุมผ้าใบ</th>
                    <th>ค้างคืน</th>
                    <th>บวกคลัง</th>
                    <th>ค่าพิเศษอื่นๆ</th>
                    <th></th>
                    </thead>
                    <tbody>
                    <?php if ($model->isNewRecord): ?>
                        <tr>
                            <td>
                                <select name="car_type_id[]" class="form-control car-type-id" id="">
                                    <option value="0">--ประเภทรถ--</option>
                                    <?php for ($i = 0; $i <= count($car_type_data) - 1; $i++) : ?>
                                        <option value="<?= $car_type_data[$i]['id'] ?>"><?= $car_type_data[$i]['name'] ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="labour_price_line[]" class="form-control labour-price-line"
                                       id="">
                            </td>
                            <td>
                                <select name="car_trail_id[]" class="form-control car-trail-id" id="">
                                    <option value="0">--ทะเบียนหาง--</option>
                                    <?php for ($i = 0; $i <= count($car_trail_type_data) - 1; $i++) : ?>
                                        <option value="<?= $car_trail_type_data[$i]['id'] ?>"><?= $car_trail_type_data[$i]['name'] ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="trail_labour_price_line[]" class="form-control trail-labour-price-line"
                                       id="">
                            </td>
                            <td>
                                <input type="number" name="express_road_price_line[]"
                                       class="form-control express-road-price-line" id="">
                            </td>
                            <td>
                                <input type="number" name="cover_sheet_price_line[]"
                                       class="form-control cover-sheet-price-line" id="">
                            </td>
                            <td>
                                <input type="number" name="overnight_price_line[]"
                                       class="form-control overnight-price-line" id="">
                            </td>
                            <td>
                                <input type="number" name="warehouse_plus_price_line[]"
                                       class="form-control warehouse-plus-price-line" id="">
                            </td>
                            <td>
                                <input type="number" name="other_price_line[]"
                                       class="form-control other-price-line" id="">
                            </td>
                            <td>
                                <div class="btn btn-danger btn-sm" onclick="removeline2($(this))"><i
                                            class="fa fa-trash"></i></div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php if (count($model_line2)): ?>
                            <?php foreach ($model_line2 as $key): ?>
                                <tr data-var="<?= $key->id ?>">
                                    <td>
                                        <input type="hidden" class="rec-id" name="rec_id[]" value="<?= $key->id ?>">
                                        <select name="car_type_id[]" class="form-control car-type-id" id="">
                                            <option value="0">--ประเภทรถ--</option>
                                            <?php for ($i = 0; $i <= count($car_type_data) - 1; $i++) : ?>
                                                <?php
                                                $selected = "";
                                                if ($car_type_data[$i]['id'] == $key->car_type_id) {
                                                    $selected = 'selected';
                                                }
                                                ?>
                                                <option value="<?= $car_type_data[$i]['id'] ?>" <?= $selected ?>><?= $car_type_data[$i]['name'] ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="labour_price_line[]"
                                               class="form-control labour-price-line" id=""
                                               value="<?= $key->labour_price ?>">
                                    </td>
                                    <td>
                                        <select name="car_trail_id[]" class="form-control car-trail-id" id="">
                                            <option value="0">--ทะเบียนหาง--</option>
                                            <?php for ($i = 0; $i <= count($car_trail_type_data) - 1; $i++) : ?>
                                                <?php
                                                $selected = "";
                                                if ($car_trail_type_data[$i]['id'] == $key->trail_id) {
                                                    $selected = 'selected';
                                                }
                                                ?>
                                                <option value="<?= $car_trail_type_data[$i]['id'] ?>" <?=$selected?>><?= $car_trail_type_data[$i]['name'] ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="trail_labour_price_line[]" class="form-control trail-labour-price-line"
                                               id="" value="<?= $key->trail_labour_price ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="express_road_price_line[]"
                                               class="form-control express-road-price-line" id=""
                                               value="<?= $key->express_road_price ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="cover_sheet_price_line[]"
                                               class="form-control cover-sheet-price-line" id=""
                                               value="<?= $key->cover_sheet_price ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="overnight_price_line[]"
                                               class="form-control overnight-price-line" id=""
                                               value="<?= $key->overnight_price ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="warehouse_plus_price_line[]"
                                               class="form-control warehouse-plus-price-line" id=""
                                               value="<?= $key->warehouse_plus_price ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="other_price_line[]"
                                               class="form-control other-price-line" id=""
                                               value="<?= $key->other_price ?>">
                                    </td>
                                    <td>
                                        <div class="btn btn-danger btn-sm" onclick="removeline2($(this))"><i
                                                    class="fa fa-trash"></i></div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td>
                                    <select name="car_type_id[]" class="form-control car-type-id" id="">
                                        <option value="0">--ประเภทรถ--</option>
                                        <?php for ($i = 0; $i <= count($car_type_data) - 1; $i++) : ?>
                                            <option value="<?= $car_type_data[$i]['id'] ?>"><?= $car_type_data[$i]['name'] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="labour_price_line[]"
                                           class="form-control labour-price-line" id="">
                                </td>
                                <td>
                                    <select name="car_trail_id[]" class="form-control car-trail-id" id="">
                                        <option value="0">--ทะเบียนหาง--</option>
                                        <?php for ($i = 0; $i <= count($car_trail_type_data) - 1; $i++) : ?>
                                            <option value="<?= $car_trail_type_data[$i]['id'] ?>"><?= $car_trail_type_data[$i]['name'] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="trail_labour_price_line[]" class="form-control trail-labour-price-line"
                                           id="">
                                </td>
                                <td>
                                    <input type="number" name="express_road_price_line[]"
                                           class="form-control express-road-price-line" id="">
                                </td>
                                <td>
                                    <input type="number" name="cover_sheet_price_line[]"
                                           class="form-control cover-sheet-price-line" id="">
                                </td>
                                <td>
                                    <input type="number" name="overnight_price_line[]"
                                           class="form-control overnight-price-line" id="">
                                </td>
                                <td>
                                    <input type="number" name="warehouse_plus_price_line[]"
                                           class="form-control warehouse-plus-price-line" id="">
                                </td>
                                <td>
                                    <input type="number" name="other_price_line[]"
                                           class="form-control other-price-line" id="">
                                </td>
                                <td>
                                    <div class="btn btn-danger btn-sm" onclick="removeline2($(this))"><i
                                                class="fa fa-trash"></i></div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>

                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4">
                            <div class="btn btn-primary"
                                 onclick="addline2($(this))">
                                <i class="fa fa-plus-circle"></i>
                            </div>
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

<?php
$url_to_Dropoffdata = \yii\helpers\Url::to(['dropoffplace/getdropoffdata'], true);

$js = <<<JS
var removelist = [];
var removelist2 = [];

$(function(){
    // $('.start-date').datepicker({dateformat: 'dd-mm-yy'});
    // $('.expire-date').datepicker({dateFormat: 'dd-mm-yy'});
});

function addline(e){
    var tr = $("#table-list tbody tr:last");
                    var clone = tr.clone();
                    //clone.find(":text").val("");
                    // clone.find("td:eq(1)").text("");
                    clone.find(".drop-off-place").val("");
                    clone.find(".hp").val("");
                    clone.find(".oil-rate").val("");
                    clone.find(".drop-off-qty").val("");
                    clone.find(".car-type").val(0);
                    clone.find(".lite-oil-rate").val(0);
                    clone.find(".count-go").val(0);
                    clone.find(".count-back").val(0);
                    clone.find(".total").val(0);
                    
                  
                    clone.attr("data-var", "");
                    clone.find('.rec-id').val("");
                    
                    tr.after(clone);
     
}
function addline2(e){
    var tr = $("#table-list2 tbody tr:last");
                    var clone = tr.clone();
                    //clone.find(":text").val("");
                    // clone.find("td:eq(1)").text("");
                    clone.find(".car-type-id").val("");
                    clone.find(".labour-price-line").val("0");
                    clone.find(".express-road-price-line").val("0");
                    clone.find(".cover-sheet-price-line").val("0");
                   clone.find(".overnight-price-line").val("0");
                   clone.find(".warehouse-plus-price-line").val("0");
                   clone.find(".other-price-line").val("0");
                    
                  
                    clone.attr("data-var", "");
                    clone.find('.rec-id').val("");
                    
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
                    $(this).find(".line-price").val(0);
                    // cal_num();
                });
            } else {
                e.parent().parent().remove();
            }
            // cal_linenum();
            // cal_all();
        }
    }
    function removeline2(e) {
        if (confirm("ต้องการลบรายการนี้ใช่หรือไม่?")) {
            if (e.parent().parent().attr("data-var") != '') {
                removelist2.push(e.parent().parent().attr("data-var"));
                $(".remove-list2").val(removelist2);
            }
            // alert(removelist);
            // alert(e.parent().parent().attr("data-var"));

            if ($("#table-list2 tbody tr").length == 1) {
                $("#table-list2 tbody tr").each(function () {
                    $(this).find(":text").val("");
                   // $(this).find(".line-prod-photo").attr('src', '');
                   
                    $(this).find(".labour-price-line").val(0);
                    $(this).find(".express-road-price-line").val(0);
                    $(this).find(".cover-sheet-price-line").val(0);
                    $(this).find(".overnight-price-line").val(0);
                    $(this).find(".warehouse-plus-price-line").val(0);
                    $(this).find(".other-price-line").val(0);
                    // cal_num();
                });
            } else {
                e.parent().parent().remove();
            }
            // cal_linenum();
            // cal_all();
        }
    }
    
    function getDropoffinfo(e){
    // alert(e.val());
    if(e.val() != ''){
        $.ajax({
            'type': 'post',
            'dataType': 'json',
            'url': '$url_to_Dropoffdata',
            'data': {'drop_off_id': e.val()},
            // alert(data)
            'success': function(data){
                if(data != null){
                    var distant_go = $("#routeplan-total_distanct").val();
                    var distant_back = $("#routeplan-total_distance_back").val();
                    // alert(distant_go);
                
                    
                    
                    // alert(data[0]['oil_rate']);
                    var oil_rate = data[0]['oil_rate'];
                    var hp = data[0]['hp'];
                    var oil_rate_1 = data[0]['oil_rate_1']
                    var car_type_id  = data[0]['car_type_id']
                    
                    var line_count_go = ( parseFloat(distant_go) / parseFloat(oil_rate) );
                    var line_count_back = ( parseFloat(distant_back) / parseFloat(oil_rate_1) );
                    
                    e.closest('tr').find('.oil-rate').val(oil_rate);
                    e.closest('tr').find('.hp').val(hp);
                    e.closest('tr').find('.lite-oil-rate').val(oil_rate_1);
                    e.closest('tr').find('.car-type').val(car_type_id).change();
                    
                    e.closest('tr').find('.count-go').val(parseFloat(line_count_go).toFixed(2)).change();
                    e.closest('tr').find('.count-back').val(parseFloat(line_count_back).toFixed(2)).change();
                }
            },
            'error': function(data){
                 alert(data);//return;
            }
        });
    }
}

function callLine(e){
   
    var oil_rate_qty = e.closest("tr").find(".drop-off-qty").val();
    var oil_rate = e.closest("tr").find(".oil-rate").val();
    var oil_rate_1 = e.closest("tr").find(".lite-oil-rate").val();
    
    var distant_go = $("#routeplan-total_distanct").val();
    var distant_back = $("#routeplan-total_distance_back").val();
    
    var line_count_go = ( parseFloat(distant_go) / parseFloat(oil_rate) );
    var line_count_back = ( parseFloat(distant_back) / parseFloat(oil_rate_1) );
    
    e.closest('tr').find('.count-go').val(parseFloat(line_count_go).toFixed(2));
    e.closest('tr').find('.count-back').val(parseFloat(line_count_back).toFixed(2));
    //alert();
    
    var count_go = e.closest("tr").find(".count-go").val();
    var count_back = e.closest("tr").find(".count-back").val();
    
    var line_total = 0;
    
    line_total = (parseFloat(count_go) + parseFloat(count_back) + parseFloat(oil_rate_qty));
    
    e.closest("tr").find(".total").val(line_total);
}


JS;

$this->registerJs($js, static::POS_END);

?>