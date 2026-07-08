<?php
use yii\helpers\Html;
use kartik\select2\Select2;
use backend\models\Customer; // Assuming companies/customers are in Customer or Company model
use backend\models\CarType;

$this->title = 'ข้อมูลน้ำหนักขั้นต่ำ';

// Fetch dynamic car types, excluding "หาง" (Tail), "พนักงาน" (Employee), and "จักรยานยนต์" (Motorcycle)
$carTypes = CarType::find()
    ->where(['status' => 1])
    ->andWhere(['not like', 'name', 'หาง'])
    ->andWhere(['not like', 'name', 'พนักงาน'])
    ->andWhere(['not like', 'name', 'จักรยานยนต์'])
    ->all();
?>
<div class="min-weight-index">
    
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center" style="vertical-align: middle;">รายการ</th>
                            <th rowspan="2" class="text-center" style="vertical-align: middle;">บริษัท</th>
                            <?php foreach ($carTypes as $carType): ?>
                                <!-- Assuming options are ตู้ and เปลือย, you can customize this if some car types only have one option -->
                                <th colspan="2" class="text-center"><?= Html::encode($carType->name) ?></th>
                            <?php endforeach; ?>
                            <th rowspan="2" class="text-center" style="vertical-align: middle;">จัดการ</th>
                        </tr>
                        <tr>
                            <?php foreach ($carTypes as $carType): ?>
                                <th class="text-center">ตู้</th>
                                <th class="text-center">เปลือย</th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Example Row for Input Form -->
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="item_name" placeholder="กรอกรายการ (Free text)">
                            </td>
                            <td>
                                <?php
                                // Use Customer model and asArray() to prevent memory exhaustion
                                echo Select2::widget([
                                    'name' => 'company_id',
                                    'data' => \yii\helpers\ArrayHelper::map(Customer::find()->select(['id', 'name'])->where(['status' => 1])->asArray()->all(), 'id', 'name'),
                                    'options' => ['placeholder' => 'เลือกบริษัท/ลูกค้า...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);
                                ?>
                            </td>
                            <?php foreach ($carTypes as $carType): ?>
                                <td>
                                    <input type="number" class="form-control" name="weight[<?= $carType->id ?>][container]" placeholder="น้ำหนัก">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="weight[<?= $carType->id ?>][flatbed]" placeholder="น้ำหนัก">
                                </td>
                            <?php endforeach; ?>
                            <td class="text-center">
                                <button class="btn btn-success"><i class="fa fa-save"></i> บันทึก</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
