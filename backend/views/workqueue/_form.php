<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Workqueue $model */
/** @var yii\widgets\ActiveForm $form */
$plate_no = "";
$hp = "";
$car_type = "";
$driver_id = "";
$driver_name = "";

$t_back_plate = '';
$t_plate = '';

if (!$model->isNewRecord) {
    $plate_no = \backend\models\Car::getPlateno($model->car_id);
    $hp = \backend\models\Car::getHp($model->car_id);
    $car_type = \backend\models\Car::getCartype($model->car_id);
    $driver_id = \backend\models\Car::getDriver($model->car_id);
    $driver_name = \backend\models\Employee::findFullName($driver_id);

    $t_plate = \backend\models\Car::getPlateno($model->tail_id);
    $t_back_plate = \backend\models\Car::getPlateno($model->tail_back_id);
}
$dropoff_list = [];
if ($w_dropoff != null) {
    foreach ($w_dropoff as $v) {
        array_push($dropoff_list, $v->dropoff_id);
    }
}
$itemback_list = [];
if ($w_itemback != null) {
    foreach ($w_itemback as $v) {
        array_push($itemback_list, $v->item_back_id);
    }
}
$current_route_plan_id = 0;
$current_route_plan_id = $model->route_plan_id;
//print_r($current_route_plan_id); return
$is_newpage = $model->isNewRecord ? 0 : 1;
$current_car_type_id = 0;
if (!$model->isNewRecord) {
    $current_car_type_id = \backend\models\Car::getCartypeId($model->car_id);
}
//print_r($dropoff_list);
//print_r($model->route_plan_id);
$dropoff_data = \common\models\DropoffPlace::find()->all();

$charter_data = [['id' => 0, 'name' => 'No'], ['id' => 1, 'name' => 'Yes']];
?>

<div class="workqueue-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <input type="hidden" class="remove-list" name="remove_list" value="">
    <input type="hidden" class="remove-list1" name="remove_list1" value="">
    <input type="hidden" class="remove-list2" name="remove_list2" value="">
    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($model, 'work_queue_no')->textInput(['maxlength' => true, 'readonly' => 'readonly', 'value' => $model->isNewRecord ? 'Draft' : $model->work_queue_no]) ?>
        </div>
        <div class="col-lg-3">
            <?php $model->work_queue_date = $model->isNewRecord ? date('d-m-Y') : date('d-m-Y', strtotime($model->work_queue_date)) ?>
            <?= $form->field($model, 'work_queue_date')->widget(\kartik\date\DatePicker::className(), [
                'value' => date('d/m/Y'),
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true,
                    'todayBtn' => true,
                ]
            ]) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'customer_id')->Widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Customer::find()->all(), 'id', function ($data) {
                    return $data->name;
                }),
                'options' => [
                    'id' => 'customer-selected-id',
                    'placeholder' => '--ลูกค้า--'
                ]
            ]) ?>
        </div>

        <div class="col-lg-3">
            <label for="">ลูกค้าวางบิล</label>
            <input type="text" class="form-control customer-invoice-name" readonly
                   value="<?= $model->isNewRecord ? '' : \backend\models\Customer::findCustomerInvoiceName($model->customer_id) ?>">
        </div>

    </div>


    <div class="row">

        <div class="col-lg-3">
            <?= $form->field($model, 'dp_no')->textInput(['maxlength' => true]) ?>
        </div>
        <!--        <div class="col-lg-3">-->
        <!--            --><?php //echo $form->field($model, 'work_option_type_id')->Widget(\kartik\select2\Select2::className(), [
        //                'data' => \yii\helpers\ArrayHelper::map(\common\models\WorkOptionType::find()->all(), 'id', 'name'),
        //                'options' => [
        //                    'id'=>'work-option-selected-id',
        //                    'placeholder' => '--เลือก--'
        //                ]
        //            ]) ?>
        <!--        </div>-->
        <div class="col-lg-3">
            <?= $form->field($model, 'car_id')->Widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Car::find()->where(['type_id' => '1'])->all(), 'id', function ($data) {
                    return $data->name;
                }),
                'options' => [
                    'id' => 'car-selected-id',
                    'placeholder' => '--รถ--',
                    'onchange' => 'getCarinfo($(this))',
                ]
            ]) ?>
        </div>
        <div class="col-lg-3">
            <?php $model->item_back_id = !$model->isNewRecord ? $itemback_list : null ?>
            <?= $form->field($model, 'item_back_id')->Widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Item::find()->all(), 'id', function ($data) {
                    return $data->name;
                }),
                'options' => [
                    'placeholder' => '--ของนำกลับ--',
                ],
                'pluginOptions' => [
                    'multiple' => true,
                ]

            ])->label('ของนำกลับ') ?>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-3">
            <label for="">ทะเบียน</label>
            <input type="text" class="form-control car-plate-no" value="<?= $plate_no ?>" readonly>
        </div>
        <div class="col-lg-3">
            <label for="">ประเภทรถ</label>
            <input type="text" class="form-control car-type" value="<?= $car_type ?>" readonly>
        </div>
        <div class="col-lg-3">
            <label for="">แรงม้า</label>
            <input type="text" class="form-control hp" value="<?= $hp ?>" readonly>
        </div>
        <div class="col-lg-3">
            <label for="">พนักงานขับรถ</label>
            <input type="text" class="form-control emp-assign-driver-id" value="<?= $driver_name ?>" readonly>
            <?= $form->field($model, 'emp_assign')->hiddenInput(['id' => 'emp-assign', 'value' => $driver_id])->label(false) ?>

            <?php //echo $form->field($model, 'emp_assign')->Widget(\kartik\select2\Select2::className(), [
            //                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Employee::find()->all(), 'id', function ($data) {
            //                    return $data->fname . ' ' . $data->lname;
            //                }),
            //                'options' => [
            //                    'id' => 'driver-id',
            //                    'readonly'=> true,
            //                    'placeholder' => '--พนักงาน--'
            //                ]
            //            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'tail_id')->Widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Car::find()->where(['type_id' => '2'])->all(), 'id', function ($data) {
                    return $data->name;
                }),
                'options' => [
                    'placeholder' => '--พ่วง--',
                    'onchange' => 'getTailinfo($(this))',
                ]
            ]) ?>
        </div>
        <div class="col-lg-4">
            <label for="">ทะเบียน</label>
            <input type="text" class="form-control tail-plate-no" value="<?= $t_plate ?>" readonly>
        </div>
        <div class="col-lg-4"></div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'weight_on_go')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'weight_go_deduct')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'go_deduct_reason')->textarea(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'weight_on_back')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'back_deduct')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'back_reason')->textarea(['maxlength' => true]) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($model, 'tail_back_id')->Widget(\kartik\select2\Select2::className(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Car::find()->where(['type_id' => '2'])->all(), 'id', function ($data) {
                    return $data->name;
                }),
                'options' => [
                    'placeholder' => '--พ่วง--',
                    'onchange' => 'getTailinfo1($(this))',
                ]
            ]) ?>
        </div>
        <div class="col-lg-3">
            <label for="">ทะเบียน</label>
            <input type="text" class="form-control tail-back-plate-no" value="<?= $t_back_plate ?>" readonly>
        </div>
        <div class="col-lg-3">
            <label for="">ระยะทางไป-กลับ</label>
            <input type="text" class="form-control total-distance" name="total_distance"
                   value="<?= $model->total_distance ?>">
        </div>

    </div>
    <div class="row">
        <div class="col-lg-3">
            <label for="">ราคาน้ำมัน</label>
            <input type="text" class="form-control oil-daily-price" name="oil_daily_price"
                   value="<?= $model->oil_daily_price ?>">
        </div>
        <div class="col-lg-3">
            <label for="">รวมจำนวน(ลิตร)</label>
            <input type="text" class="form-control total-lite" name="total_lite" value="<?= $model->total_lite ?>">
        </div>
        <div class="col-lg-3">
            <label for="">รวมจำนวน(บาท)</label>
            <input type="text" class="form-control total-amount" name="total_amount" value="<?= $model->total_amount ?>"
                   readonly>
        </div>

    </div>
    <div style="height: 10px;"></div>
    <div class="row">
        <div class="col-lg-3">
            <label for="">ราคาน้ำมันปั๊มนอก</label>
            <input type="text" class="form-control oil-out-price" name="oil_out_price"
                   value="<?= $model->oil_out_price ?>">
        </div>
        <div class="col-lg-3">
            <label for="">รวมจำนวนปั๊มนอก(ลิตร)</label>
            <input type="text" class="form-control total-out-lite" name="total_out_lite"
                   value="<?= $model->total_out_lite ?>">
        </div>
        <div class="col-lg-3">
            <label for="">รวมปั๊มนอก(บาท)</label>
            <input type="text" class="form-control total-amount-2" name="total_amount2"
                   value="<?= $model->total_amount2 ?>" readonly>
        </div>
    </div>
    <div style="height: 10px;"></div>
    <div class="row">
        <div class="col-lg-3">
            <?php echo $form->field($model, 'status')->widget(\toxor88\switchery\Switchery::className(), ['options' => ['label' => '', 'class' => 'form-control']])->label() ?>
        </div>
        <div class="col-lg-3">
            <?php echo $form->field($model, 'is_labur')->widget(\toxor88\switchery\Switchery::className(), ['options' => ['label' => '', 'class' => 'form-control', 'onchange' => 'enableLabour($(this))']])->label() ?>
        </div>
        <div class="col-lg-3"> <?php echo $form->field($model, 'is_express_road')->widget(\toxor88\switchery\Switchery::className(), ['options' => ['label' => '', 'class' => 'form-control', 'onchange' => 'enableExpressroad($(this))']])->label() ?></div>
        <div class="col-lg-3"> <?php echo $form->field($model, 'is_other')->widget(\toxor88\switchery\Switchery::className(), ['options' => ['label' => '', 'class' => 'form-control', 'onchange' => 'enableOther($(this))']])->label() ?></div>
    </div>
    <div style="height: 10px;"></div>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'labour_price')->textinput(['maxlength' => true, 'id' => 'labour-price',]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'express_road_price')->textInput(['maxlength' => true, 'id' => 'express-road-price',]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'other_price')->textInput(['maxlength' => true, 'id' => 'other-price',]) ?>
        </div>
    </div>
    <div style="height: 10px;"></div>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'cover_sheet_price')->textinput(['maxlength' => true, 'id' => 'cover-sheet-price',]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'overnight_price')->textInput(['maxlength' => true, 'id' => 'overnight-price',]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'warehouse_plus_price')->textInput(['maxlength' => true, 'id' => 'warehouse-plus-price',]) ?>
        </div>
    </div>
    <div style="height: 10px;"></div>
    <div class="row">
        <div class="col-lg-4"><?= $form->field($model, 'test_price')->textinput(['maxlength' => true, 'id' => 'test-price',]) ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'damaged_price')->textinput(['maxlength' => true, 'id' => 'damaged-price',]) ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'deduct_other_price')->textinput(['maxlength' => true, 'id' => 'deduct-other-price',]) ?></div>
    </div>
    <div style="height: 10px;"></div>
    <div class="row">
        <div class="col-lg-4"><?= $form->field($model, 'work_double_price')->textinput(['maxlength' => true, 'id' => 'work-double-price',]) ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'towing_price')->textinput(['maxlength' => true, 'id' => 'work-towing-price',]) ?></div>
        <div class="col-lg-4"><?= $form->field($model, 'other_amt')->textinput(['maxlength' => true, 'id' => 'work-other-amt',]) ?></div>
    </div>

    <br/>


    <br/>
    <h5>จุดขึ้นสินค้า</h5>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-striped" id="table-list2">
                <thead>
                <th>สถานที่ขึ้นสินค้า</th>
                <th>RouteNo</th>
                <th>เลขที่ใบตั้ง</th>
                <th>จำนวนม้วน</th>
                <th>เหมา</th>
                <th>น้ำหนัก</th>
                <th>ราคาบาท/ตัน</th>
                <th>จำนวนเงิน</th>
                <th></th>
                </thead>
                <tbody>
                <?php if ($model->isNewRecord): ?>
                    <tr>
                        <td>
                            <select name="dropoff_id[]" class="form-control dropoff-id" id="" onchange="getpriceroutefromquotation($(this))">
                                <option value="0">--สถานที่ชื้นสินค้า--</option>
                                <?php for ($i = 0; $i <= count($dropoff_data) - 1; $i++) : ?>
                                    <option value="<?= $dropoff_data[$i]['id'] ?>"><?= $dropoff_data[$i]['name'] ?></option>
                                <?php endfor; ?>
                            </select>
                        </td>
                        <td>
                            <select class="form-control line-route-no" name="line_route_no[]"  onchange="getpricefromquotation($(this))">
                            </select>
                        </td>
                        <td>
                            <input type="text" name="dropoff_no[]"
                                   class="form-control dropoff-no" id="">
                        </td>
                        <td>
                            <input type="number" name="qty[]"
                                   step="any"
                                   class="form-control qty" id="">
                        </td>
                        <td>
                            <select name="is_charter[]" id="" class="form-control is-charter"
                                    onchange="checkcharter($(this))">
                                <?php for ($z = 0; $z <= count($charter_data) - 1; $z++): ?>
                                    <option value="<?= $charter_data[$z]['id'] ?>"><?= $charter_data[$z]['name'] ?></option>
                                <?php endfor; ?>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="weight[]"
                                   step="any"
                                   class="form-control weight" id="" onchange="calpriceperton($(this))">
                        </td>
                        <td>
                            <input type="text" class="form-control price-per-ton" name="price_per_ton[]"
                                   onchange="calpriceperton($(this))">
                        </td>
                        <td>
                            <input type="text" class="form-control price-line-total" name="price_line_total[]" readonly>
                        </td>
                        <td>
                            <div class="btn btn-danger btn-sm" onclick="removeline1($(this))"><i
                                        class="fa fa-trash"></i></div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php if (count($w_dropoff)): ?>
                        <?php foreach ($w_dropoff as $key): ?>
                        <?php $route_no_data = \common\models\QueryQuotationPricePerTon::find()->where(['dropoff_id' => $key->dropoff_id, 'car_type_id' => $current_car_type_id])->all(); ?>
                            <tr data-var="<?= $key->id ?>">
                                <td>
                                    <select name="dropoff_id[]" class="form-control dropoff-id" id="" onchange="getpriceroutefromquotation($(this))">
                                        <option value="0">--สถานที่ชื้นสินค้า--</option>
                                        <?php for ($i = 0; $i <= count($dropoff_data) - 1; $i++) : ?>
                                            <?php
                                            $selected = "";
                                            if ($dropoff_data[$i]['id'] == $key->dropoff_id) {
                                                $selected = 'selected';
                                            }
                                            ?>
                                            <option value="<?= $dropoff_data[$i]['id'] ?>" <?= $selected ?>><?= $dropoff_data[$i]['name'] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control line-route-no" name="line_route_no[]"  onchange="getpricefromquotation($(this))">
                                        <?php for ($j = 0; $j <= count($route_no_data) - 1; $j++): ?>
                                            <?php
                                            $selected = "";
                                            if ($route_no_data[$j]['id'] == $key->quotation_route_no) {
                                                $selected = 'selected';
                                            }
                                            ?>
                                            <option value="<?= $route_no_data[$j]['route_no'] ?>" <?= $selected ?>><?= $route_no_data[$j]['route_no'] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="dropoff_no[]"
                                           class="form-control dropoff-no" id=""
                                           value="<?= $key->dropoff_no ?>">
                                </td>
                                <td>
                                    <input type="number" name="qty[]"
                                           class="form-control qty" id=""
                                           step="any"
                                           value="<?= $key->qty ?>">
                                </td>
                                <td>
                                    <select name="is_charter[]" id="" class="form-control is-charter"
                                            onchange="checkcharter($(this))">
                                        <?php for ($z = 0; $z <= count($charter_data) - 1; $z++): ?>
                                            <?php
                                            $selected = $charter_data[$z]['id'] == $key->is_charter ? "selected" : "";
                                            ?>
                                            <option value="<?= $charter_data[$z]['id'] ?>" <?= $selected ?>><?= $charter_data[$z]['name'] ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="weight[]"
                                           class="form-control weight" id=""
                                           step="any"
                                           value="<?= $key->weight ?>"
                                           onchange="calpriceperton($(this))" <?= $key->is_charter == 1 ? 'readonly' : '' ?>>
                                </td>

                                <td>
                                    <input type="text" class="form-control price-per-ton" name="price_per_ton[]"
                                           value="<?= $key->price_per_ton ?>" onchange="calpriceperton($(this))">
                                </td>
                                <td>
                                    <input type="text" class="form-control price-line-total" name="price_line_total[]"
                                           value="<?= $key->price_line_total ?>" readonly>
                                </td>
                                <td>
                                    <div class="btn btn-danger btn-sm" onclick="removeline1($(this))"><i
                                                class="fa fa-trash"></i></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td>
                                <select name="dropoff_id[]" class="form-control dropoff-id" id="" onchange="getpriceroutefromquotation($(this))">
                                    <option value="0">--สถานที่ชื้นสินค้า--</option>
                                    <?php for ($i = 0; $i <= count($dropoff_data) - 1; $i++) : ?>
                                        <option value="<?= $dropoff_data[$i]['id'] ?>"><?= $dropoff_data[$i]['name'] ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-control line-route-no" name="line_route_no[]"  onchange="getpricefromquotation($(this))">
                                </select>
                            </td>
                            <td>
                                <input type="text" name="dropoff_no[]"
                                       class="form-control dropoff-no" id="">
                            </td>
                            <td>
                                <input type="number" name="qty[]"
                                       class="form-control qty"
                                       step="any"
                                       id="">
                            </td>
                            <td>
                                <select name="is_charter[]" id="" class="form-control is-charter"
                                        onchange="checkcharter($(this))">
                                    <?php for ($z = 0; $z <= count($charter_data) - 1; $z++): ?>
                                        <option value="<?= $charter_data[$z]['id'] ?>"><?= $charter_data[$z]['name'] ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="weight[]"
                                       step="any"
                                       class="form-control weight" id="" onchange="calpriceperton($(this))">
                            </td>
                            <td>
                                <input type="text" class="form-control price-per-ton" name="price_per_ton[]"
                                       onchange="calpriceperton($(this))">
                            </td>
                            <td>
                                <input type="text" class="form-control price-line-total" name="price_line_total[]"
                                       readonly>
                            </td>
                            <td>
                                <div class="btn btn-danger btn-sm" onclick="removeline1($(this))"><i
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
                             onclick="addline1($(this))">
                            <i class="fa fa-plus-circle"></i>
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>


    <h6>แนบเอกสาร</h6>

    <?php if ($model_line_doc == null): ?>

        <div class="row">
            <div class="col-lg-12">
                <table class="table table-striped table-bordered" id="table-list">
                    <tbody>
                    <tr>
                        <td>
                            <input type="hidden" class="rec-id" name="rec_id[]" value="0">
                            <input type="text" class="form-control line-doc-name" name="line_doc_name[]" value="">
                        </td>
                        <td>
                            <input type="file" class="line-file-name" name="line_file_name[]">
                        </td>
                        <td>
                            <div class="btn btn-danger" onclick="removeline($(this))">ลบ</div>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>
                            <div class="btn btn-primary" onclick="addline($(this))">เพิ่ม</div>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-striped table-bordered" id="table-list">
                    <tbody>
                    <?php foreach ($model_line_doc as $val): ?>
                        <tr data-var="<?= $val->id ?>">
                            <td>
                                <input type="hidden" class="rec-id" name="rec_id[]" value="<?= $val->id ?>">
                                <input type="text" class="form-control line-doc-name" name="line_doc_name[]"
                                       value="<?= $val->description ?>">
                            </td>
                            <td>
                                <a href="<?= \Yii::$app->getUrlManager()->getBaseUrl() . '/uploads/workqueue_doc/' . $val->doc ?>"
                                   target="_blank">ดูเอกสาร</a></td>
                            </td>
                            <td>
                                <div class="btn btn-danger" onclick="removeline($(this))">ลบ</div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td>
                            <input type="hidden" class="rec-id" name="rec_id[]" value="0">
                            <input type="text" class="form-control line-doc-name" name="line_doc_name[]" value="">
                        </td>
                        <td>
                            <input type="file" class="line-file-name" name="line_file_name[]">
                        </td>
                        <td>
                            <div class="btn btn-danger" onclick="removeline($(this))">ลบ</div>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>
                            <div class="btn btn-primary" onclick="addline($(this))">เพิ่ม</div>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    </tfoot>

                </table>
            </div>
        </div>

    <?php endif; ?>


    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                <?php if (!$model->isNewRecord && $model->approve_status != 1): ?>
                    <a class="btn btn-primary"
                       href="<?= \yii\helpers\Url::to(['workqueue/approvejob', 'id' => $model->id, 'approve_id' => 1], true) ?>">อนุมัติจำนวน</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-6" style="text-align: right">
            <?php if (!$model->isNewRecord): ?>
                <div class="btn-group">
                    <a href="<?= \yii\helpers\Url::to(['workqueue/exportdoc', 'id' => $model->id], true) ?>"
                       class="btn btn-default">Export</a>
                    <a href="<?= \yii\helpers\Url::to(['workqueue/printdocx', 'id' => $model->id], true) ?>"
                       class="btn btn-warning">พิมพ์</a>
                </div>

            <?php endif; ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>


</div>

<form id="form-delete-doc" action="<?= \yii\helpers\Url::to(['workqueue/removedoc'], true) ?>" method="post">
    <input type="hidden" name="work_queue_id" value="<?= $model->id ?>">
    <input type="hidden" class="work-queue-doc-delete" name="doc_name" value="">
</form>
<input type="hidden" id="is-page-new" value="<?= $is_newpage ?>">
<input type="hidden" id="route-plan-id" value="<?= $current_route_plan_id ?>">
<input type="hidden" id="car-type-selected" value="<?= $current_car_type_id ?>">
<input type="hidden" id="labour-price-checked" value="0">
<input type="hidden" id="labour-price-plan" value="<?= $model->labour_price ?>">
<input type="hidden" id="express-road-price-checked" value="0">
<input type="hidden" id="express-road-price-plan" value="<?= $model->express_road_price ?>">
<input type="hidden" id="other-price-checked" value="0">
<input type="hidden" id="other-price-plan" value="<?= $model->other_price ?>">
<input type="hidden" id="cover-sheet-price-checked" value="0">
<input type="hidden" id="cover-sheet-price-plan" value="<?= $model->cover_sheet_price ?>">
<input type="hidden" id="overnight-price-checked" value="0">
<input type="hidden" id="overnight-price-plan" value="<?= $model->overnight_price ?>">
<input type="hidden" id="warehouse-plus-price-checked" value="0">
<input type="hidden" id="warehouse-plus-price-plan" value="<?= $model->warehouse_plus_price ?>">
<?php
$url_to_getCardata = \yii\helpers\Url::to(['car/getcarinfo'], true);
$url_to_routeplan = \yii\helpers\Url::to(['car/getrouteplan'], true);
$url_to_find_item = \yii\helpers\Url::to(['item/finditem'], true);
$url_to_customer_invoice = \yii\helpers\Url::to(['customer/getcustomerinvoice'], true);
$url_to_getpricefromquotation = \yii\helpers\Url::to(['workqueue/getpricefromquotation'], true);
$url_to_getquotation_route = \yii\helpers\Url::to(['workqueue/getquotationrouote'], true);
$js = <<<JS
var removelist = [];
var removelist2 = [];
var loop = 0;
var loop2 = 0;
var loop3 = 0;

$(function(){
     //alert($("#is-page-new").val());
     $(".dropoff-id").select2();
     $("#customer-selected-id").on('change',function(){
         var customer_id = $(this).val();
         getCustomerInvoiceName(customer_id);
     });
   
    if($("#is-page-new").val() == 0){
        $("#labour-price-checked").val($("#workqueue-is_labur").val());
        $("#express-road-price-checked").val($("#workqueue-is_express_road").val());
        $("#other-price-checked").val($("#workqueue-is_other").val());
        
        $("#cover-sheet-price-checked").val($("#workqueue-is_other").val());
        $("#overnight-price-checked").val($("#workqueue-is_other").val());
        $("#warehouse-plus-price-checked").val($("#workqueue-is_other").val());
    }else{
      //  alert();
        getRouteplan();
    }
    
    $(".oil-daily-price").change(function(){
        var oil_price = parseFloat($(this).val()).toFixed(2);
        var oil_total = parseFloat($(".total-lite").val()).toFixed(2);
        var total_amount = oil_price * oil_total;
        $(".total-amount").val(parseFloat(total_amount).toFixed(2));
    });
    
    $(".oil-out-price").change(function(){
        var oil_price = parseFloat($(this).val()).toFixed(2);
        var oil_total = parseFloat($(".total-out-lite").val()).toFixed(2);
        var total_amount = oil_price * oil_total;
        $(".total-amount-2").val(parseFloat(total_amount).toFixed(2));
    });
    $(".total-lite").change(function(){
        var oil_total = parseFloat($(this).val()).toFixed(2);
        var oil_price = parseFloat($(".oil-daily-price").val()).toFixed(2);
        var total_amount = oil_price * oil_total;
        $(".total-amount").val(parseFloat(total_amount).toFixed(2));
    });
    $(".total-out-lite").change(function(){
        var oil_total = parseFloat($(this).val()).toFixed(2);
        var oil_price = parseFloat($(".oil-out-price").val()).toFixed(2);
        var total_amount = oil_price * oil_total;
        $(".total-amount-2").val(parseFloat(total_amount).toFixed(2));
    });
});

function checkcharter(e){
    var checked_yesno = e.val();
    if(parseInt(checked_yesno) == 1){
        e.closest("tr").find(".weight").val(0).prop("readonly", true);
        var per_ton_price = e.closest("tr").find(".price-per-ton").val();
        e.closest("tr").find(".price-line-total").val(parseFloat(per_ton_price).toFixed(2));
    }else{
       e.closest("tr").find(".weight").prop("readonly", false);  
    }
}


function enableLabour(e){
    if(loop >= 1){
        loop = 0;
        return false;
    }
   // alert('loop is' + loop);
  // alert($("#labour-price-checked").val());
   if($("#labour-price-checked").val() == 1){
      // alert("has 1");
       $("#labour-price-checked").val(0);
       if($("#labour-price-checked").val() == 0){
           $("#labour-price-checked").val(0)
       }
         loop +=1;
         if($("#labour-price-checked").val() == 1){
           var labour = $('#labour-price-plan').val();
           $("#labour-price").val(labour);
           }else{
                $("#labour-price").val(0);
           }
         
       return false;
   }
 
   if($("#labour-price-checked").val() == 0){
      // alert("has 0");
       $("#labour-price-checked").val(1);
       if($("#labour-price-checked").val() == 0){
           $("#labour-price-checked").val(1)
       }
        loop +=1;
       if($("#labour-price-checked").val() == 1){
           getRouteplan();
           var labour = $('#labour-price-plan').val();
           $("#labour-price").val(labour);
           }else{
                $("#labour-price").val(0);
           }
      
       // loop = 0;
       return  false;
   }
  
 
}
function enableExpressroad(e){
    if(loop2 >= 1){
        loop2 = 0;
        return false;
    }
   if($("#express-road-price-checked").val() == 1){
     //  alert("has 1");
       $("#express-road-price-checked").val(0);
       if($("#express-road-price-checked").val() == 0){
           $("#express-road-price-checked").val(0)
       }
         loop2 +=1;
         if($("#express-road-price-checked").val() == 1){
          
           var labour = $('#express-road-price-plan').val();
           $("#express-road-price").val(labour);
           }else{
                $("#express-road-price").val(0);
           }
       return false;
   }
 
   if($("#express-road-price-checked").val() == 0){
      // alert("has 0");
       $("#express-road-price-checked").val(1);
       if($("#express-road-price-checked").val() == 0){
           $("#express-road-price-checked").val(1)
       }
        loop2 +=1;
       if($("#express-road-price-checked").val() == 1){
           getRouteplan();
           var labour = $('#express-road-price-plan').val();
           $("#express-road-price").val(labour);
           }else{
                $("#express-road-price").val(0);
           }
      
       // loop = 0;
       return  false;
   }
  
 
}


function enableOther(e){
   
    if(loop3 >= 1){
        loop3 = 0;
        return false;
    }
  
   if($("#other-price-checked").val() == 1){
     //  alert("has 1");
       $("#other-price-checked").val(0);
       if($("#other-price-checked").val() == 0){
           $("#other-price-checked").val(0)
       }
         loop3 +=1;
         if($("#other-price-checked").val() == 1){
           var labour = $('#other-price-plan').val();
           $("#other-price").val(labour);
           }else{
                $("#other-price").val(0);
           }
       return false;
   }
 
   if($("#other-price-checked").val() == 0){
      // alert("has 0");
       $("#other-price-checked").val(1);
       if($("#other-price-checked").val() == 0){
           $("#other-price-checked").val(1)
       }
        loop3 +=1;
       if($("#other-price-checked").val() == 1){
           getRouteplan();
           var labour = $('#other-price-plan').val();
           $("#other-price").val(labour);
         
       
           var cover_sheet_price = $('#cover-sheet-price-plan').val();
           $("#cover-sheet-price").val(cover_sheet_price);
          
           var overnight_price = $('#overnight-price-plan').val();
           $("#overnight-price").val(overnight_price);
          
          
           var warehouse_plus_price = $('#warehouse-plus-price-plan').val();
           $("#warehouse-plus-price").val(warehouse_plus_price);
          
       }else{
                $("#other-price").val(0);
                $("#cover-sheet-price").val(0);
                 $("#overnight-price").val(0);
                  $("#warehouse-plus-price").val(0);
           }
       // loop = 0;
       return  false;
   }
  
 
}
function getCarinfo(e){
    // alert(e.val());
    if(e.val() != ''){
        $.ajax({
            'type': 'post',
            'dataType': 'json',
            'url': '$url_to_getCardata',
            'data': {'car_id': e.val()},
            'success': function(data){
                // alert(data);
                if(data != null){
                    // alert(data[0]['plate_no']);
                    // alert(data[0]['fuel_price']);
                    var plat_no = data[0]['plate_no'];
                    var hp = data[0]['hp'];
                    var car_type = data[0]['car_type'];
                    var car_type_id = data[0]['car_type_id'];
                    var driver_id = data[0]['driver_id'];
                    var driver_name  = data[0]['driver_name'];
                    var price  = data[0]['fuel_price'];
                    
                  //  alert(car_type_id);
                    $('.car-plate-no').val(plat_no);
                    $('.car-type').val(car_type);
                    $('.hp').val(hp);
                    $("#emp-assign").val(driver_id);
                    $(".emp-assign-driver-id").val(driver_name);
                    $("#car-type-selected").val(car_type_id);
                    $(".oil-daily-price").val(price);
                    
                    
                    getRouteplan();
                }
            },
            'error': function(data){
                 alert(data);//return;
            }
            
        });
    }
}
function getCustomerInvoiceName(id){
    // alert(e.val());
    if(id !=null){
        $.ajax({
            'type': 'post',
            'dataType': 'html',
            'url': '$url_to_customer_invoice',
            'data': {'customer_id': id},
            'success': function(data){
                // alert(data);
                 $('.customer-invoice-name').val(data);
            },
            'error': function(data){
                 alert(data);//return;
            }
            
        });
    }
}

function getRouteplan(){
    var route_plan_id = $("#route-plan-id").val();
    var customer_id = $("#customer-selected-id").val();
   // alert(route_plan_id);
   var car_type_id = $("#car-type-selected").val();
   
    if(customer_id > 0 && car_type_id > 0){
        
    //   alert(car_type_id);
        $.ajax({
            'type': 'post',
            'dataType': 'json',
            'url': '$url_to_routeplan',
            'data': {'route_plan_id': route_plan_id,'car_type_id': car_type_id,'customer_id': customer_id},
            'success': function(data){
                // alert('has data');
                if(data != null){
                    // alert(data[0]['plate_no']);
                    var distance = data[0]['total_distance'];
                    var rate_qty = data[0]['total_rate_qty'];
                    var dropoff_qty = data[0]['total_dropoff_rate_qty'];
                    var labour_price = data[0]['labour_price'];
                    var express_road_price = data[0]['express_road_price'];
                    var cover_sheet_price = data[0]['cover_sheet_price'];
                    var overnight_price = data[0]['overnight_price'];
                    var warehouse_plus_price = data[0]['warehouse_plus_price'];
                    var other_price = data[0]['other_price'];
                   // alert(other_price);
                    $('.total-distance').val(distance);
                    $('.total-qty').val(parseFloat(rate_qty) + parseFloat(dropoff_qty));
                    $('#labour-price').val(labour_price);
                    $('#labour-price-plan').val(labour_price);
                    $('#express-road-price-plan').val(express_road_price);
                    $('#cover-sheet-price-plan').val(cover_sheet_price);
                    $('#overnight-price-plan').val(overnight_price);
                    $('#warehouse-plus-price-plan').val(warehouse_plus_price);
                    $('#other-price-plan').val(other_price);
                }
            },
            'error': function(data){
                 alert(data);//return;
            }
        });
    }
}
function getTailinfo(e){
    // alert(e.val());
    if(e.val() != ''){
        $.ajax({
            'type': 'post',
            'dataType': 'json',
            'url': '$url_to_getCardata',
            'data': {'car_id': e.val()},
            'success': function(data){
                // alert(data);
                if(data != null){
                    // alert(data[0]['plate_no']);
                    var plat_no = data[0]['plate_no'];
                    $('.tail-plate-no').val(plat_no);
                }
            },
            'error': function(data){
                 alert(data);//return;
            }
        });
    }
}
function getTailinfo1(e){
    // alert(e.val());
    if(e.val() != ''){
        $.ajax({
            'type': 'post',
            'dataType': 'json',
            'url': '$url_to_getCardata',
            'data': {'car_id': e.val()},
            'success': function(data){
                // alert(data);
                if(data != null){
                    // alert(data[0]['plate_no']);
                    var plat_no = data[0]['plate_no'];
                    $('.tail-back-plate-no').val(plat_no);
                }
            },
            'error': function(data){
                 alert(data);//return;
            }
        });
    }
}
function addline(e){
    var tr = $("#table-list tbody tr:last");
                    var clone = tr.clone();
                    //clone.find(":text").val("");
                    // clone.find("td:eq(1)").text("");
                    clone.find(".line-doc-name").val("");
                    clone.find(".line-file-name").val("");
                   
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
function removedoc(e){
    var doc_name = e.attr("data-var");
    $("work-queue-doc-delete").val(doc_name);
    if(doc_name != ''){
        $("form#form-delete-doc").submit();
    }
}

function addline1(e) {
    var tr = $("#table-list2 tbody tr:last");
    var clone = tr.clone();

    // Reset input values
    clone.find(".dropoff-no").val("");
    clone.find(".qty").val(0);
    clone.find(".weight").val(0);
    clone.find(".price-per-ton").val(0);
    clone.find(".price-line-total").val(0);
    clone.attr("data-var", "");
    clone.find('.rec-id').val("0");

    // Destroy select2 to avoid cloning the internal DOM mess
   // clone.find('.dropoff-id').select2('destroy');

    // Clear all input/select values
    clone.find('input, select').val('');

    // Insert the cloned row into DOM
    tr.after(clone);

    // Re-initialize select2 AFTER inserting into DOM
   // clone.find('.dropoff-id').select2();
}
// function addline1(e){
//     var tr = $("#table-list2 tbody tr:last");
//                     var clone = tr.clone();
//                     //clone.find(":text").val("");
//                     // clone.find("td:eq(1)").text("");
//                    // clone.find(".dropoff-id").val("");
//                     clone.find(".dropoff-no").val("");
//                     clone.find(".qty").val(0);
//                     clone.find(".weight").val(0);
//                     clone.find(".price-per-ton").val(0);
//                     clone.find(".price-line-total").val(0);
//                   
//                     clone.attr("data-var", "");
//                     clone.find('.rec-id').val("0");
//                    
//                     clone.find('.dropoff-id').select2('destroy');
//                     clone.find('input, select').val('');
//                     clone.find('.dropoff-id').select2();
//                   
//                     tr.after(clone);
//                    
//                    
//    
// }
function removeline1(e) {
        if (confirm("ต้องการลบรายการนี้ใช่หรือไม่?")) {
            if (e.parent().parent().attr("data-var") != '') {
                removelist2.push(e.parent().parent().attr("data-var"));
                $(".remove-list2").val(removelist2);
            }
            // alert(removelist2);
            // alert(e.parent().parent().attr("data-var"));

            if ($("#table-list2 tbody tr").length == 1) {
                $("#table-list2 tbody tr").each(function () {
                    $(this).find(":text").val("");
                   // $(this).find(".line-prod-photo").attr('src', '');
                    $(this).find(".dropoff-id").val(0);
                     $(this).find(".dropoff-no").val('');
                     $(this).find(".qty").val(0);
                    $(this).find(".weight").val(0);
                    // cal_num();
                });
            } else {
                e.parent().parent().remove();
            }
            // cal_linenum();
            // cal_all();
        }
    }

function calpriceperton(e){
    var line_w = e.closest("tr").find(".weight").val();
    var line_p = e.closest("tr").find(".price-per-ton").val();
    var check_yesno = e.closest("tr").find(".is-charter").val();
    var line_total = 0;
    
    if(parseInt(check_yesno) == 0){
        line_total = parseFloat(line_w) * parseFloat(line_p);
    }else{
        line_total = parseFloat(line_p);
    }
    
    
    
    e.closest("tr").find(".price-line-total").val(parseFloat(line_total).toFixed(2));
}

function getpricefromquotation(e){
    var dropoff_id = e.closest('tr').find(".dropoff-id").val();
    var route_no = e.closest('tr').find(".line-route-no").val();
    var car_id = $("#car-selected-id").val();
  //  alert(route_no);
    
    if(dropoff_id > 0 && car_id > 0 && route_no !=''){
        //alert();
        // alert(dropoff_id);
        //    alert(car_id);
        //    alert(route_no);
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '$url_to_getpricefromquotation',
            async: false,
            data: {'dropoff_id': dropoff_id,'car_id': car_id,'route_no': route_no},
            success: function(data){
              //  alert(data[0]['price']);
                if(data!=null){
                    if(data[0]['price'] == null){
                       // alert('null');
                        e.closest("tr").find(".price-per-ton").val(0);
                    }else{
                       // alert('not null');
                        e.closest("tr").find(".price-per-ton").val(data[0]['price']);
                    }
                    
                   // e.closest("tr").find(".is-charter").val(data.is_charter);
                }else{
                    e.closest("tr").find(".price-per-ton").val(0);
                }
            }
        });
    }
    calpriceperton(e);
}
function getpriceroutefromquotation(e){
    var dropoff_id = e.closest('tr').find(".dropoff-id").val();
    var car_id = $("#car-selected-id").val();
  //  alert(route_no);
    
    if(dropoff_id > 0 && car_id > 0 ){
       // alert(dropoff_id);
       //    alert(car_id);
       //    alert(route_no);
        $.ajax({
            type: 'post',
            dataType: 'html',
            url: '$url_to_getquotation_route',
            async: false,
            data: {'dropoff_id': dropoff_id,'car_id': car_id},
            success: function(data){
              //  alert(data[0]['price']);
                if(data){
                    e.closest("tr").find(".line-route-no").html(data);
                   // e.closest("tr").find(".is-charter").val(data.is_charter);
                }
            }
        });
    }
    calpriceperton(e);
}

JS;
$this->registerJs($js, static::POS_END);
?>
