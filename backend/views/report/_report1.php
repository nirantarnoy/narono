<?php

use miloschuman\highcharts\Highcharts;

$this->title = 'รายงานจำนวนเที่ยววิ่ง';
//echo date('d/m/Y', strtotime($from_date));
?>
    <form action="<?= \yii\helpers\Url::to(['report/report1'], true); ?>" method="post">
        <input type="hidden" name="viewday_amt" class="viewday-amt" value="<?=$viewday_amt?>">
        <div class="row">
            <div class="col-lg-3">
                <div class="label">ตั้งแต่วันที่</div>
                <?php
                echo \kartik\date\DatePicker::widget([
                    'value' => date('d/m/Y', strtotime($from_date)),
                    'name' => 'from_date',
                    'options' => [
                        'format' => 'dd/mm/yyyy',
                    ],
                    'pluginOptions' => [

                    ]
                ]);
                ?>
            </div>
            <div class="col-lg-3">
                <div class="label">ถึงวันที่</div>
                <?php
                echo \kartik\date\DatePicker::widget([
                    'value' => date('d/m/Y', strtotime($to_date)),
                    'name' => 'to_date',
                    'options' => [
                        'format' => 'dd/mm/yyyy',
                    ],
                    'pluginOptions' => [

                    ]
                ]);
                ?>
            </div>
            <div class="col-lg-3">
                <div class="label" style="color: white;">ค้นหา</div>
                <button class="btn btn-primary btn-search">ค้นหา</button>
            </div>
        </div>
    </form>
    <br/>
    <div class="row">
        <div class="col-lg-12">
            <div class="btn-group">
                <div class="btn btn-success btn-3d" id="btn-3d" data-var="3" onclick="changefilter($(this))">3 Days</div>
                <div class="btn btn-default btn-5d" id="btn-5d" data-var="5" onclick="changefilter($(this))">5 Days</div>
                <div class="btn btn-default btn-7d" id="btn-7d" data-var="7" onclick="changefilter($(this))">7 Days</div>
                <div class="btn btn-default btn-15d" id="btn-15d" data-var="15" onclick="changefilter($(this))">15 Days</div>
                <div class="btn btn-default btn-30d" id="btn-30d" data-var="30" onclick="changefilter($(this))">30 Days</div>
                <div class="btn btn-default btn-1y" id="btn-1y" data-var="365" onclick="changefilter($(this))">1 Year</div>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-12">
            <!--        <h6>กราฟแสดงจำนวนเที่ยว</h6>-->
        </div>
    </div>
<?php
$date_data = ['1/8', '2/8', '3/8', '4/8', '5/8'];
//$date_data = [];
$date_data_filter = [];
$data_series = [
      ['name' => '7XXX', 'data' => [10, 5, 4,5,9]],
      ['name' => '8XXX', 'data' => [5, 7, 3,6,5]],
      ['name' => '9XXX', 'data' => [4, 12, 8,7,10]],
      ['name' => '6XXX', 'data' => [8, 2, 8,9,11]],
      ['name' => '5XXX', 'data' => [9, 8, 15,2,5]]
  ];
//$xyz = [10, 5, 4,5,9];
//$data_series = [];
$model = null;
if ($from_date != null && $to_date != null) {
    $model = \backend\models\Workqueue::find()->where(['>=', 'date(work_queue_date)', $from_date])->andFilterWhere(['<=', 'date(work_queue_date)', $to_date])->groupBy(['date(work_queue_date)'])->all();
} else {
    $model = \backend\models\Workqueue::find()->where(['date(work_queue_date)' => date('Y-m-d')])->groupBy(['date(work_queue_date)'])->all();
}

//// $model_car = \backend\models\Car::find()->all();
//if ($model) {
//    foreach ($model as $value) {
//        array_push($date_data, date('d/m', strtotime($value->work_queue_date)));
//        array_push($date_data_filter, date('Y-m-d', strtotime($value->work_queue_date)));
//    }
//
//    // if($model_car){
//    $series_for_data = [];
//    //   foreach ($model_car as $valuex){
//    $xdata = [];
//    for ($i = 0; $i <= count($date_data_filter) - 1; $i++) {
//        //  $modelxx = \backend\models\Workqueue::find()->where(['car_id'=>$valuex->id,'date(work_queue_date)'=>$date_data_filter[$i]])->count();
//        $modelxx = \backend\models\Workqueue::find()->where(['date(work_queue_date)' => $date_data_filter[$i]])->count();
//        array_push($xdata, (int)$modelxx);
//    }
//    //  print_r($xdata);
//    array_push($data_series, ['name' => '', 'data' => $xdata]);
//    //  }
//    // array_push($data_series,$series_for_data);
//    // }
//
//}
//print_r($date_data)."<br />";
//print_r($xyz);
?>
    <div class="row">
        <div class="col-lg-12">
            <?php
            echo Highcharts::widget([
                'options' => [
                    'title' => ['text' => 'กราฟแสดงจำนวนเที่ยว'],
                    'xAxis' => [
                        'categories' => $date_data
                    ],
                    'yAxis' => [
                        'title' => ['text' => 'เที่ยว']
                    ],
                    'series' => $data_series
                ]
            ]);
            ?>
        </div>
    </div>

<?php
$js = <<<JS
$(function(){
    var view_day_amt = $(".viewday-amt").val();
    if(view_day_amt == 0){
        $(".btn-group div").each(function(){
      
          if($(this).hasClass("btn-success")){
                $(this).removeClass("btn-success");
                $(this).addClass("btn-default")
            }else{
               $(this).addClass("btn-default")
            }
       
        });
    }else{
        $(".btn-group div").each(function(){
          var var_id = $(this).attr("data-var");
          if(view_day_amt == var_id){
                $(this).removeClass("btn-default");
                $(this).addClass("btn-success")
            }else{
               $(this).addClass("btn-default")
            }
       
        });
    }
});
function changefilter(e){
    var c_btn = e.attr("id");
    if(e.hasClass("btn-default")){
        e.removeClass("btn-default");
        e.addClass("btn-success");
        var day_amt = e.attr("data-var");
        $(".viewday-amt").val(day_amt);
        $(".btn-search").trigger("click");
    }
    $(".btn-group div").each(function(){
       var loop_id = $(this).attr("id");
       if(loop_id != c_btn){
          if($(this).hasClass("btn-success")){
                $(this).removeClass("btn-success");
                $(this).addClass("btn-default")
            }else{
               $(this).addClass("btn-default")
            }
       }
    });
}
JS;

$this->registerJs($js, static::POS_END);

?>