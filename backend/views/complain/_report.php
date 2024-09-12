<?php

use kartik\date\DatePicker;
use miloschuman\highcharts\Highcharts;

$display_date = date('d-m-Y');
$find_date = date('Y-m-d');


$m_name = [['id' => 1, 'name' => 'มกราคม'], ['id' => 2, 'name' => 'กุมภาพันธ์'], ['id' => 3, 'name' => 'มีนาคม'], ['id' => 4, 'name' => 'เมษายน'], ['id' => 5, 'name' => 'พฤษภาคม'], ['id' => 6, 'name' => 'มิถุนายน'], ['id' => 7, 'name' => 'กรกฎาคม'], ['id' => 8, 'name' => 'สิงหาคม'], ['id' => 9, 'name' => 'กันยายน'], ['id' => 10, 'name' => 'ตุลาคม'], ['id' => 11, 'name' => 'พฤศจิกายน'], ['id' => 12, 'name' => 'ธันวาคม']];
$model_title = \common\models\ComplainTitle::find()->orderBy(['id' => SORT_ASC])->all();
?>
    <form action="<?= \yii\helpers\Url::to(['complain/report'], true) ?>" method="post">

    </form>
    <br/>
    <div id="print-area">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center;"><h3><b>รายงานคอมเพลน</b></h3></td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>วันที่ <?= date('d/m/Y'); ?></b></td>
            </tr>
        </table>
        <br>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 8%;text-align: center;">หัวข้อ</th>
                <?php for ($x = 0; $x < count($m_name); $x++): ?>
                    <th style="width: 8%;text-align: center;"><?= $m_name[$x]['name'] ?></th>
                <?php endfor; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model_title as $title): ?>
                <tr>
                    <td><?= $title->name ?></td>
                    <?php for ($i = 0; $i < count($m_name); $i++): ?>
                        <td style="text-align: center;">
                            <?php
                            $model = \common\models\Complain::find()
                                ->where(['year(trans_date)' => date('Y'), 'complain_title_id' => $title->id, 'MONTH(trans_date)' => $m_name[$i]['id']])
                                ->count();
                            echo $model;
                            ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>

            </tfoot>

        </table>
    </div>

<br />
    <div class="row">
        <div class="col-lg-12">
            <?php
            echo Highcharts::widget([
                'options' => [
                    'title' => ['text' => 'กราฟแสดงจำนวนคอมเพลน'],
                    'xAxis' => [
                        'categories' => ['มค.', 'กพ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.']
                    ],
                    'yAxis' => [
                        'title' => ['text' => 'เที่ยว']
                    ],
                    'series' => [['name' => 'สลากฉีกขาด', 'data' => [1,1,2,3,4,5,6,7,8,9,9,9]],['name' => 'สินค้าเปียกน้ำ', 'data' => [8,1,2,3,4,15,0,7,8,9,5,1]]]
                ]
            ]);
            ?>
        </div>
    </div>



    <div class="row">
        <div class="col-lg-4">
            <div class="btn btn-warning btn-print" onclick="printContent('print-area')">พิมพ์</div>
        </div>
    </div>
<?php
$js = <<<JS
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