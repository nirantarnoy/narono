<?php

use backend\models\CostTitle;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var backend\models\CostTitleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'รายการค่าใช้จ่าย';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fixcost-title-index">


    <div class="row">
        <div class="col-lg-10">
            <p>
                <?= Html::a(Yii::t('app', '<i class="fa fa-plus"></i> สร้างใหม่'), ['create'], ['class' => 'btn btn-success']) ?>
                <?= Html::a(Yii::t('app', '<i class="fa fa-download"></i> Export Pattern'), ['export-pattern'], ['class' => 'btn btn-outline-primary']) ?>
                <?= Html::button('<i class="fas fa-file-import"></i> Import Excel', [
                    'class' => 'btn btn-outline-info',
                    'data-toggle' => 'modal',
                    'data-target' => '#importModal'
                ]) ?>
            </p>
        </div>
        <div class="col-lg-2" style="text-align: right">
            <form id="form-perpage" class="form-inline" action="<?= Url::to(['costtitle/index'], true) ?>"
                  method="post">
                <div class="form-group">
                    <label>แสดง </label>
                    <select class="form-control" name="perpage" id="perpage">
                        <option value="20" <?= (isset($perpage) && $perpage == '20') || !isset($perpage) ? 'selected' : '' ?>>20</option>
                        <option value="50" <?= isset($perpage) && $perpage == '50' ? 'selected' : '' ?> >50</option>
                        <option value="100" <?= isset($perpage) && $perpage == '100' ? 'selected' : '' ?>>100</option>
                    </select>
                    <label> รายการ</label>
                </div>
            </form>
        </div>
    </div>

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'emptyCell' => '-',
        'layout' => "{items}\n{summary}\n<div class='text-center'>{pager}</div>",
        'summary' => "แสดง {begin} - {end} ของทั้งหมด {totalCount} รายการ",
        'showOnEmpty' => false,
        'striped' => true,
        'hover' => true,
        'bordered' => true,
        'id' => 'product-grid',
        'emptyText' => '<div style="color: red;text-align: center;"> <b>ไม่พบรายการไดๆ</b> <span> เพิ่มรายการโดยการคลิกที่ปุ่ม </span><span class="text-success">"สร้างใหม่"</span></div>',
        'columns' => [
            [
                'class' => 'kartik\grid\SerialColumn',
                'contentOptions' => ['style' => 'text-align: center'],
            ],
            'name',
            'description',
            [
                'attribute' => 'account_id',
                'value' => function($data){
                    $account = \common\models\ChartOfAccount::findOne($data->account_id);
                    return $account ? '['.$account->account_code.'] '.$account->name : '-';
                }
            ],
            [
                'attribute' => 'type_id',
                'format' => 'html',
                'value' => function ($data) {
                  return  \backend\helpers\FixcostType::getTypeById($data->type_id);
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'contentOptions' => ['style' => 'text-align: center'],
                'headerOptions' => ['style' => 'text-align: center'],
                'value' => function ($data) {
                    if ($data->status == 1) {
                        return '<div class="badge badge-success" >ใช้งาน</div>';
                    } else {
                        return '<div class="badge badge-secondary" >ไม่ใช้งาน</div>';
                    }
                }
            ],
            [
                'header' => 'ตัวเลือก',
                'headerOptions' => ['style' => 'text-align:center;', 'class' => 'activity-view-link',],
                'class' => 'kartik\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'template' => '{view} {update}{delete}',
                'buttons' => [
                    'view' => function ($url, $data, $index) {
                        return Html::a('<span class="fas fa-eye btn btn-xs btn-default"></span>', $url, [
                            'title' => 'ดูรายละเอียด',
                            'data-pjax' => '0',
                        ]);
                    },
                    'update' => function ($url, $data, $index) {
                        return Html::a('<span class="fas fa-edit btn btn-xs btn-default"></span>', $url, [
                            'title' => 'แก้ไข',
                            'data-pjax' => '0',
                        ]);
                    },
                    'delete' => function ($url, $data, $index) {
                        return Html::a('<span class="fas fa-trash-alt btn btn-xs btn-default"></span>', 'javascript:void(0)', [
                            'title' => 'ลบ',
                            'data-url' => $url,
                            'data-var' => $data->id,
                            'onclick' => 'recDelete($(this));'
                        ]);
                    }
                ]
            ],
        ],
        'pager' => ['class' => \yii\widgets\LinkPager::className()],
    ]); ?>



    <?php Pjax::end(); ?>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">นำเข้าข้อมูลจาก Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?= Html::beginForm(['import'], 'post', ['enctype' => 'multipart/form-data']) ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>เลือกไฟล์ Excel (.xlsx, .xls)</label>
                        <?= Html::fileInput('import_file', null, ['class' => 'form-control', 'accept' => '.xlsx, .xls', 'required' => true]) ?>
                        <small class="text-muted mt-2 d-block">
                            * ระบบจะเริ่มอ่านข้อมูลจากแถวที่ 2<br>
                            * รูปแบบ: ชื่อ, รายละเอียด, ประเภทรับจ่าย(1,2), สถานะ(0,1)
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">เริ่มนำเข้าข้อมูล</button>
                </div>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>

</div>