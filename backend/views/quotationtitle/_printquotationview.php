<?php
$this->title = "รายละเอียด";
?>
<div id="print-area">
    <div class="row">
        <div class="col-lg-12">
            <h5><?= $model->description ?></h5>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-striped" id="table-list">
                <thead>
                <tr>
                    <th>จากคลังสินค้า</th>
                    <th>Route</th>
                    <th>โซนพื้นที่</th>
                    <th>ระยะทาง</th>
                    <th>ปริมาณเฉลี่ยตัน/ปี</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: right;">ราคาที่เสนอ</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php if ($model_line != null): ?>
                    <?php foreach ($model_line as $value): ?>
                        <tr>
                            <td>

                            </td>
                            <td>
                                <?= $value->route_code ?>
                            </td>
                            <td>

                            </td>
                            <td>
                                <?= $value->distance ?>
                            </td>
                            <td>
                                <?= $value->load_qty ?>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align: right;">
                                <?= $value->price_current_rate ?>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>

            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="btn btn-warning" onclick="printContent('print-area')"><i class="fa fa-print"></i> พิมพ์</div>
        <div class="btn btn-success" onclick=""><i class="fa fa-file-download"></i> Export</div>
    </div>
</div>
<?php
$js = <<<JS
$(function(){
    // var after_save = $(".is-after-save").val();
    // if(after_save == 1){
    //     printContent('print-area');
    //     $("form#form-goto-index").submit();
    // }
});
function printContent(el)
      {
         var restorepage = document.body.innerHTML;
         var printcontent = document.getElementById(el).innerHTML;
         document.body.innerHTML = printcontent;
         window.print();
         document.body.innerHTML = restorepage;
         
         // workqueConfirm(e);
         
     }
     
function confirmwork(){
   
    workqueConfirm();
    // printContent('print-area');
    // if(confirm("ยืนยันการทำรายการใช่หรือไม่ ?")){
    //     $("form#form-confirm").submit();
    // }
}     
JS;
$this->registerJs($js, static::POS_END);
?>