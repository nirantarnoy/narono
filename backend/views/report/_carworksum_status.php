<?php

use kartik\date\DatePicker;

$this->title = 'รายงานสรุปน้ำมันแยกคัน';



$display_date = date('d-m-Y');
$display_to_date = date('d-m-Y');
$find_date = date('Y-m-d');
$find_to_date = date('Y-m-d');
if ($search_date != null) {
    $find_date = date('Y-m-d', strtotime($search_date));
    $display_date = date('d-m-Y', strtotime($search_date));
}
if ($search_to_date != null) {
    $find_to_date = date('Y-m-d', strtotime($search_to_date));
    $display_to_date = date('d-m-Y', strtotime($search_to_date));
}
$model = null;

if($car_search != null){
    $model = \backend\models\Workqueue::find()->innerJoin('car','work_queue.car_id = car.id')
        ->where(['like', 'car.plate_no', $car_search])->orderBy(['id' => SORT_ASC])
        ->andFilterWhere(['AND',['>=','date(work_queue_date)',$find_date],['<=','date(work_queue_date)',$find_to_date]])
        ->all();
}


?>
<form action="<?=\yii\helpers\Url::to(['report/report2'],true)?>" method="post">
    <div class="row">
        <div class="col-lg-6">
            <div class="input-group">
                <input type="text" class="form-control" name="car_search" placeholder="เลขทะเบียน" value="<?=$car_search?>">
                <?php
                echo DatePicker::widget([
                    'name' => 'search_date',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => $display_date,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy'
                    ]
                ]);
                echo DatePicker::widget([
                    'name' => 'search_to_date',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => $display_to_date,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy'
                    ]
                ]);
                ?>
                <button class="btn btn-info">ค้นหา</button>
            </div>
        </div>
    </div>
</form>
<br/>
<div id="print-area">
    <div class="row">
        <div class="col-lg-12">
            <b>รายงานเที่ยววิ่ง</b>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <b>บริษัท</b>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <b>เดือน xx ประจำปี xxxx</b>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <b>ประเภทรถ XX ล้อ ทะเบียน XX</b>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-lg-6">
            <span><b>ชื่อพนักงานขับรถ :</b></span>
        </div>
        <div class="col-lg-6">
            <span><b>อายุรถ :</b></span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table id="table-data" class="table table-bordered">

                <tbody>
                <tr>
                    <td>จำนวนเที่ยวไป</td>
                    <td>0</td>
                    <td>จำนวนเที่ยวกลับ</td>
                    <td>0</td>
                    <td>% จำนวนเที่ยวไปต่อจำนวนเที่ยวกลับ</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td>จำนวนตันเที่ยวไป</td>
                    <td>0</td>
                    <td>จำนวนตันเที่ยวกลับ</td>
                    <td>0</td>
                    <td>% จำนวนน้ำหนักเที่ยวไปต่อจำนวนน้ำหนักเที่ยวกลับ</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td>ระยะทางเริ่มต้น (ต้นเดือน)</td>
                    <td>0</td>
                    <td>กิโลเมตร</td>
                    <td>จำนวนสิ้นสุด(ปลายเดือน)</td>
                    <td>0</td>
                    <td>บาท</td>
                </tr>
                <tr>
                    <td>เป้าหมายรายได้</td>
                    <td>0</td>
                    <td>บาท</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>รวมระยะทางวิ่ง</td>
                    <td>0</td>
                    <td>กิโลเมตร</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>จำนวนน้ำมันทั้งหมดที่ใช้เติมทั้งเดือน</td>
                    <td>0</td>
                    <td>ลิตร</td>
                    <td>ราคาค่าน้ำมัน</td>
                    <td></td>
                    <td>บาท</td>
                </tr>
                <tr>
                    <td>อัตราสิ้นเปลือง</td>
                    <td>0</td>
                    <td>กิโลเมตร/ลิตร</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>รายได้เที่ยวไป</td>
                    <td>0</td>
                    <td>บาท</td>
                    <td>รายได้เที่ยวกลับ</td>
                    <td>0</td>
                    <td>บาท</td>
                </tr>
                <tr>
                    <td>รายได้รวม</td>
                    <td></td>
                    <td>บาท</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>รายจ่ายรวม</td>
                    <td></td>
                    <td>บาท</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>% รายจ่ายต่อรายได้</td>
                    <td></td>
                    <td></td>
                    <td>% น้ำมันต่อรายได้</td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-lg-12">
        <div class="btn btn-default btn-print" onclick="printContent('print-area')">พิมพ์</div>
        <div class="btn btn-info" id="btn-export-excel">Export Excel</div>
    </div>
</div>

<?php
$this->registerJsFile(\Yii::$app->request->baseUrl . '/js/jquery.table2excel.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$js = <<<JS
$("#btn-export-excel").click(function(){
  $("#table-data").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "Excel Document Name"
  });
});
function printContent(el)
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

