<?php

use yii\helpers\Url;
use miloschuman\highcharts\Highcharts;

$this->title = 'ภาพรวมระบบ';
$dash_date = null;
if ($f_date != null && $t_date != null) {
    $dash_date = date('d/m/Y', strtotime($f_date)) . ' - ' . date('d/m/Y', strtotime($t_date));
}

//echo \backend\models\Stockjournal::getLastNo(1,1);

$html = '';
$check_pull_price = 0;
if (false) {
    $url = \Yii::$app->basePath . '/web/api_con/simple_html_dom.php';
    include $url;

    $domain = 'https://xn--42cah7d0cxcvbbb9x.com/%E0%B8%A3%E0%B8%B2%E0%B8%84%E0%B8%B2%E0%B8%99%E0%B9%89%E0%B8%B3%E0%B8%A1%E0%B8%B1%E0%B8%99%E0%B8%A7%E0%B8%B1%E0%B8%99%E0%B8%99%E0%B8%B5%E0%B9%89/';
    $html = '';
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36\r\n" .
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8\r\n" .
                "Accept-Language: th-TH,th;q=0.9,en-US;q=0.8,en;q=0.7\r\n" .
                "Cache-Control: max-age=0\r\n" .
                "Connection: keep-alive\r\n"
        ]
    ];

    $context = stream_context_create($opts);

    $target = file_get_html($domain, false, $context);
    $i = 0;

    $fuel_data = ['แก๊สโซฮอล์ 95', 'แก๊สโซฮอล์ 91', 'แก๊สโซฮอล์ E20', 'แก๊สโซฮอล์ E85', 'เบนซิน 95', 'ดีเซล', 'ดีเซล B7', 'ดีเซล B20', 'ดีเซลพรีเมี่ยม', 'แก๊ส NGV'];

    $current_loop = '';
    $is_start = 0;
    $completed_data = [];
    $check_pull_price = 0;
    if ($target) {
        foreach ($target->find('.gtoday table tbody tr td') as $el) {
            if (in_array(trim($el->plaintext), $fuel_data)) {
                $is_start = 1;
                //echo $el->plaintext.'<br />';
                $current_loop = $el->plaintext;
            } else {
                // echo $is_start;
                if ($is_start == 1) {
                    // echo $el->plaintext.'<br />';
                    $is_start = 0;

                    array_push($completed_data, ['name' => $current_loop, 'price' => $el->plaintext]);

                }
            }
        }
        $target->clear();
        unset($target);
    }

    $html .= '<table style="border: 1px solid black;" class="table table-striped table-bordered">';
    if (count($completed_data) > 0) {
        for ($xx = 0; $xx <= count($completed_data) - 1; $xx++) {
            $html .= '<tr>';
            $html .= '<td style="padding: 10px;"><input type="hidden" name="line_name[]" value="' . $completed_data[$xx]['name'] . '">';
            $html .= $completed_data[$xx]['name'];
            $html .= '</td>';
            $html .= '<td style="padding: 10px;"><input type="hidden" name="line_price[]" value="' . $completed_data[$xx]['price'] . '">';
            $html .= $completed_data[$xx]['price'];
            $html .= '</td>';

            $html .= '</tr>';
        }
    }
    $html .= '</table>';
}

?>
<br/>
<!--<input type="text" class="form-control qr-read" value="" onchange="showqr($(this))">-->
<!--<p class="show-qr-code"></p>-->
<div class="site-index">
    <div class="body-content">
        <?php
        if(!empty(\Yii::$app->session->getFlash('success'))){
            $check_pull_price = \Yii::$app->session->getFlash('success');
        }
        ?>
        <input type="hidden" class="check-pull-price" value="<?=$check_pull_price?>">
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= number_format($car_qty) ?></h3>
                        <p>จำนวนรถทั้งหมด</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="<?= Url::to(['product/index'], true) ?>" class="small-box-footer">รายละเอียด <i
                                class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= number_format($work_queue_qty) ?></h3>
                        <!--                        <sup style="font-size: 20px">%</sup>-->
                        <p>จำนวนใบงานทั้งหมด</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="<?= Url::to(['deliveryroute/index'], true) ?>" class="small-box-footer">รายละเอียด <i
                                class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= number_format($work_queue_not_complete_qty) ?></h3>
                        <p>จำนวนไม่รับงาน</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="<?= Url::to(['car/index'], true) ?>" class="small-box-footer">รายละเอียด <i
                                class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-fuchsia">
                    <div class="inner">
                        <h3><?= number_format($work_queue_complete_qty) ?></h3>
                        <p>จำนวนงานเสร็จ</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="<?= Url::to(['orders/index'], true) ?>" class="small-box-footer">รายละเอียด <i
                                class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>

    </div>
    <form id="form-dashboard" action="<?= Url::to(['site/index'], true) ?>" method="post">
        <div class="row">
            <div class="col-lg-4">
                <div class="label">เลือกดูตามช่วงวันที่</div>
                <?php
                echo \kartik\daterange\DateRangePicker::widget([
                    'name' => 'dashboard_date',
                    'value' => $dash_date,
                    'pluginOptions' => [
                        'format' => 'DD/MM/YYYY',
                        'locale' => [
                            'format' => 'DD/MM/YYYY'
                        ],
                    ],
                    'presetDropdown' => true,
                    'options' => [
                        'class' => 'form-control',
                        'onchange' => '$("#form-dashboard").submit();'
                    ]
                ]);
                ?>
            </div>
        </div>
    </form>
    <br>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <!--                    <div class="card">-->
                    <!--                        <div class="card-header border-0">-->
                    <!--                            <div class="d-flex justify-content-between">-->
                    <!--                                <h3 class="card-title">กราฟแสดงรายรับ-รายจ่าย</h3>-->
                    <!--                                <a href="javascript:void(0);">รายละเอียด</a>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                        <div class="card-body">-->
                    <!--                            <div class="d-flex">-->
                    <!--                                <p class="d-flex flex-column">-->
                    <!--                                    <span class="text-bold text-lg">82,000</span>-->
                    <!--                                    <span>มูลค่า</span>-->
                    <!--                                </p>-->
                    <!--                                <p class="ml-auto d-flex flex-column text-right">-->
                    <!--                    <span class="text-success">-->
                    <!--                      <i class="fas fa-arrow-up"></i> 12.5%-->
                    <!--                    </span>-->
                    <!--                                    <span class="text-muted">Since last week</span>-->
                    <!--                                </p>-->
                    <!--                            </div>-->
                    <!--                            <!-- /.d-flex -->
                    <!---->
                    <!--                            <div class="position-relative mb-4">-->
                    <!--                                <canvas id="visitors-chart" height="200"></canvas>-->
                    <!--                            </div>-->
                    <!---->
                    <!--                            <div class="d-flex flex-row justify-content-end">-->
                    <!--                  <span class="mr-2">-->
                    <!--                    <i class="fas fa-square text-primary"></i> เดือนนี้-->
                    <!--                  </span>-->
                    <!---->
                    <!--                                <span>-->
                    <!--                    <i class="fas fa-square text-gray"></i> เดือนที่แล้ว-->
                    <!--                  </span>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <!-- /.card -->
                    <!--                    <div class="card">-->
                    <!--                        <div class="card-header border-0">-->
                    <!--                            <div class="d-flex justify-content-between">-->
                    <!--                                <h3 class="card-title">ยอดขายแยกประเภทขาย</h3>-->
                    <!--                                <a href="javascript:void(0);">รายละเอียด</a>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                        <div class="card-body">-->
                    <!--                            <div class="d-flex">-->
                    <!--                                <p class="d-flex flex-column">-->
                    <!--                                    <span class="text-bold text-lg">18,230.00</span>-->
                    <!--                                    <span>มูลค่า</span>-->
                    <!--                                </p>-->
                    <!--                            </div>-->
                    <!-- /.d-flex -->
                    <!---->
                    <!--                            <div class="position-relative mb-12">-->
                    <!--                                --><?php
                    //                                echo Highcharts::widget([
                    //                                    'setupOptions' => [
                    //                                        'lang' => [
                    //                                            'numericSymbols' => null,
                    //                                            'thousandsSep' => ','
                    //                                        ]
                    //                                    ],
                    //                                    'options' => [
                    //                                        'title' => ['text' => ''],
                    //                                        'subtitle' => ['text' => ''],
                    //                                        'tooltip' => [
                    //                                            'pointFormat' => "<b style='color: red;font-weight: bold'>{point.y:,.0f}</b> บาท"
                    //                                        ],
                    //                                        'xAxis' => [
                    //                                            'categories' => $category
                    //                                        ],
                    //                                        'yAxis' => [
                    //                                            'title' => ['text' => 'ยอดเงิน']
                    //                                        ],
                    //                                        'series' => $data_by_type
                    //                                    ]
                    //                                ]);
                    //                                ?>
                    <!--                            </div>-->
                    <!---->
                    <!--                        </div>-->
                    <!--                    </div>-->

                    <!--                     -------->

                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">รายการใบงานล่าสุด</h3>
                            <div class="card-tools">
                                <a href="#" class="btn btn-tool btn-sm">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="#" class="btn btn-tool btn-sm">
                                    <i class="fas fa-bars"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                <tr>
                                    <th>เลขที่</th>
                                    <th>วันที่</th>
                                    <th>ลูกค้า</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col-md-6 -->
                <div class="col-lg-6">
                    <!--                    <div class="card">-->
                    <!--                        <div class="card-header border-0">-->
                    <!--                            <div class="d-flex justify-content-between">-->
                    <!--                                <h3 class="card-title">ยอดขายแยกตามประเภทสินค้า</h3>-->
                    <!--                                <a href="javascript:void(0);">รายละเอียด</a>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                        <div class="card-body">-->
                    <!--                            <div class="d-flex">-->
                    <!--                                <p class="d-flex flex-column">-->
                    <!--                                    <span class="text-bold text-lg">18,230.00</span>-->
                    <!--                                    <span>มูลค่า</span>-->
                    <!--                                </p>-->
                    <!--                                <p class="ml-auto d-flex flex-column text-right">-->
                    <!--                    <span class="text-success">-->
                    <!--                      <i class="fas fa-arrow-up"></i> 33.1%-->
                    <!--                    </span>-->
                    <!--                                    <span class="text-muted">Since last month</span>-->
                    <!--                                </p>-->
                    <!--                            </div>-->
                    <!-- /.d-flex -->
                    <!---->
                    <!--                            <div class="position-relative mb-4">-->
                    <!--                                --><?php
                    //                                //  print_r($data_by_prod_type);
                    //                                echo Highcharts::widget([
                    //                                    'setupOptions' => [
                    //                                        'lang' => [
                    //                                            'numericSymbols' => null,
                    //                                            'thousandsSep' => ','
                    //                                        ]
                    //                                    ],
                    //                                    'options' => [
                    //                                        'chart' => [
                    //                                            'type' => 'pie',
                    //                                        ],
                    //                                        'tooltip' => [
                    //                                            'pointFormat' => "<b style='color: red;font-weight: bold'>{point.y:,.0f}</b> บาท"
                    //                                        ],
                    //                                        'allowPointSelect' => true,
                    //                                        'title' => ['text' => ''],
                    //                                        'subtitle' => ['text' => ''],
                    //                                        'showInLegend' => true,
                    //                                        'xAxis' => [
                    //                                            'categories' => ''
                    //                                        ],
                    //                                        'yAxis' => [
                    //                                            'title' => ['text' => 'ยอดเงิน']
                    //                                        ],
                    //                                        'series' => [
                    //                                            $data_by_prod_type[0]
                    //                                        ]
                    //                                    ]
                    //                                ]);
                    //                                ?>
                    <!--                            </div>-->
                    <!---->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <!-- /.card -->

                    <!--
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">ราคาน้ำมัน ปตท. วันนี้</h3>
                            <div class="card-tools">
                                <a href="#" class="btn btn-sm btn-tool">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-tool">
                                    <i class="fas fa-bars"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="form-update-price" action="<?= Url::to(["fuel/activeprice"], true) ?>"  method="post">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php
                                        echo $html;
                                        ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3">
                                        <button class="btn btn-info btn-pull-daily">อัพเดทราคาน้ำมัน</button>
                                    </div>
                                </div>


                            </form>
                        </div>
                    </div>
                    -->
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
</div>
<br/>
<!--<div class="row">-->
<!--    <div class="col-lg-12">-->
<!--        <form action="--><? //=Url::to(['site/getcominfo'],true)?><!--">-->
<!--            <button class="btn btn-success">Get Mac</button>-->
<!--        </form>-->
<!--    </div>-->
<!--</div>-->
<!--<button onclick="takeshot()">-->
<!--    Take Screenshot-->
<!--</button>-->
<!--<div id="div1">-->
<!--    niran tarlek-->
<!--    <table class="table">-->
<!--        <tr>-->
<!--            <td>dfdfd</td>-->
<!--            <td>fdfd</td>-->
<!--            <td>fdfdfd</td>-->
<!--        </tr>-->
<!--    </table>-->
<!--</div>-->
<?php
$this->registerJsFile(\Yii::$app->request->baseUrl . '/js/jquery.html2canvas.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$url_to_save_screenshort = \yii\helpers\Url::to(['site/createscreenshort'], true);
$js = <<<JS
$(function(){
    //aleret();
    /*
    if($(".check-pull-price").val() == 0){
        $(".btn-pull-daily").trigger("click");
    }
    */
   
});
function takeshot() {
    const input = document.getElementById('div1');
    const area = input.getBoundingClientRect()
      html2canvas(input,{
          useCORS: true,
          scrollX: 0,
          scrollY: 0,
          width: area.width,
          height: area.height
      }).then((canvas) => {
            console.log("done ... ");
            var img = canvas.toDataURL("image/png");//here set the image extension and now image data is in var img that will send by our ajax call to our api or server site page
              $.ajax({
                    type: 'POST',
                    url: "$url_to_save_screenshort",//path to send this image data to the server site api or file where we will get this data and convert it into a file by base64
                    data:{
                      "img":img
                    },
                    success:function(data){
                        
                    alert(data);
                    //$("#dis").html(data);
                    }
              });
        });
        
     }
     
     function showqr(e){
       var res = e.val().split(',');
       $(".show-qr-code").html(res[0]);
     }
JS;

$this->registerJs($js, static::POS_END);
?>
