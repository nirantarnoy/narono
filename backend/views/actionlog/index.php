<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap4\LinkPager;
use common\models\ActionLog;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ActionLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $perpage int */

$this->title = 'ประวัติการใช้งานระบบ (Action Log)';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .action-log-index .pagination {
        border-radius: 6px;
        overflow: hidden;
    }
    .action-log-index .pagination .page-item .page-link {
        color: #2b669a;
        font-weight: 500;
        padding: 0.35rem 0.75rem;
        font-size: 0.875rem;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease-in-out;
        line-height: 1.4;
    }
    .action-log-index .pagination .page-item .page-link:hover {
        background-color: #e9f2fb;
        border-color: #b8daff;
        color: #0056b3;
    }
    .action-log-index .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: #ffffff;
        font-weight: 700;
        box-shadow: 0 2px 5px rgba(0, 123, 255, 0.35);
    }
    .action-log-index .pagination .page-item.disabled .page-link {
        color: #adb5bd;
        background-color: #f8f9fa;
        border-color: #dee2e6;
        cursor: not-allowed;
    }
    .action-log-index .pagination .page-item:first-child .page-link {
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
    }
    .action-log-index .pagination .page-item:last-child .page-link {
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
    }
    .action-log-index .card-footer {
        background: linear-gradient(180deg, #ffffff 0%, #fcfcfc 100%);
    }
</style>
<div class="action-log-index">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-history mr-2"></i><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <form method="get" action="<?= Url::to(['actionlog/index']) ?>" class="mb-3">
                <input type="hidden" name="perpage" value="<?= (int)$perpage ?>">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <div class="input-group">
                            <input type="text" name="ActionLogSearch[globalSearch]" class="form-control" 
                                   placeholder="ค้นหาชื่อ, ตาราง, ข้อมูล..." 
                                   value="<?= Html::encode($searchModel->globalSearch) ?>">
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="ActionLogSearch[action]" class="form-control" onchange="this.form.submit()">
                            <option value="">-- ทุกการทำงาน --</option>
                            <option value="<?= ActionLog::ACTION_DELETE ?>" <?= $searchModel->action === ActionLog::ACTION_DELETE ? 'selected' : '' ?>>ลบข้อมูล (DELETE)</option>
                            <option value="<?= ActionLog::ACTION_UPDATE ?>" <?= $searchModel->action === ActionLog::ACTION_UPDATE ? 'selected' : '' ?>>แก้ไขข้อมูล (UPDATE)</option>
                            <option value="<?= ActionLog::ACTION_CREATE ?>" <?= $searchModel->action === ActionLog::ACTION_CREATE ? 'selected' : '' ?>>เพิ่มข้อมูล (CREATE)</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" name="ActionLogSearch[table_name]" class="form-control" 
                                placeholder="ระบุชื่อตาราง..." 
                                value="<?= Html::encode($searchModel->table_name) ?>">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" name="ActionLogSearch[username]" class="form-control" 
                                placeholder="ผู้ใช้งาน..." 
                                value="<?= Html::encode($searchModel->username) ?>">
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i>ค้นหา</button>
                        <a href="<?= Url::to(['actionlog/index']) ?>" class="btn btn-secondary"><i class="fas fa-undo mr-1"></i>รีเซ็ต</a>
                    </div>
                </div>
            </form>

            <?php Pjax::begin(['id' => 'action-log-grid-pjax']); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'emptyCell' => '-',
                'layout' => "<div class='table-responsive'>{items}</div>\n" .
                    "<div class='card-footer border-top py-3 px-3 mt-2'>" .
                        "<div class='row align-items-center'>" .
                            "<div class='col-12 col-md-6 mb-2 mb-md-0 d-flex align-items-center flex-wrap'>" .
                                "<div class='text-muted small mr-3 font-weight-normal'>" .
                                    "<i class='fas fa-list-ol mr-1 text-primary'></i>{summary}" .
                                "</div>" .
                                "<div class='d-inline-flex align-items-center'>" .
                                    "<span class='text-muted small mr-1'>แสดง</span>" .
                                    "<select class='form-control form-control-sm actionlog-perpage' style='width: 75px; height: 31px; font-size: 12px; display: inline-block;'>" .
                                        "<option value='20'" . ($perpage == 20 ? " selected" : "") . ">20</option>" .
                                        "<option value='50'" . ($perpage == 50 ? " selected" : "") . ">50</option>" .
                                        "<option value='100'" . ($perpage == 100 ? " selected" : "") . ">100</option>" .
                                        "<option value='200'" . ($perpage == 200 ? " selected" : "") . ">200</option>" .
                                    "</select>" .
                                    "<span class='text-muted small ml-1'>รายการ/หน้า</span>" .
                                "</div>" .
                            "</div>" .
                            "<div class='col-12 col-md-6 d-flex justify-content-md-end justify-content-center'>{pager}</div>" .
                        "</div>" .
                    "</div>",
                'summary' => "แสดงรายการที่ <b>{begin} - {end}</b> จากทั้งหมด <b>{totalCount}</b> รายการ",
                'tableOptions' => ['class' => 'table table-bordered table-striped table-hover text-nowrap mb-0'],
                'pager' => [
                    'class' => LinkPager::class,
                    'options' => ['class' => 'd-flex justify-content-md-end justify-content-center m-0'],
                    'listOptions' => ['class' => ['pagination', 'pagination-sm', 'm-0', 'shadow-sm']],
                    'firstPageLabel' => '<i class="fas fa-angle-double-left"></i> หน้าแรก',
                    'lastPageLabel' => 'หน้าสุดท้าย <i class="fas fa-angle-double-right"></i>',
                    'prevPageLabel' => '<i class="fas fa-chevron-left"></i> ก่อนหน้า',
                    'nextPageLabel' => 'ถัดไป <i class="fas fa-chevron-right"></i>',
                    'maxButtonCount' => 7,
                ],
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => '#',
                        'headerOptions' => ['style' => 'width: 50px; text-align: center;'],
                        'contentOptions' => ['style' => 'text-align: center;'],
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'วัน-เวลา',
                        'headerOptions' => ['style' => 'width: 140px; text-align: center;'],
                        'contentOptions' => ['style' => 'text-align: center; font-size: 13px;'],
                        'value' => function ($model) {
                            return date('d/m/Y H:i:s', $model->created_at);
                        },
                    ],
                    [
                        'attribute' => 'username',
                        'label' => 'ผู้ดำเนินการ',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'width: 140px; text-align: center;'],
                        'contentOptions' => ['style' => 'text-align: center;'],
                        'value' => function ($model) {
                            $name = $model->getOperatorName();
                            if (strpos($name, 'User ID:') === 0) {
                                return '<span class="badge badge-light border text-dark p-1" title="อ้างอิง User ID"><i class="fas fa-id-card text-muted mr-1"></i>' . Html::encode($name) . '</span>';
                            }
                            if ($name === 'console' || $name === 'System') {
                                return '<span class="badge badge-secondary p-1"><i class="fas fa-cogs mr-1"></i>' . Html::encode($name) . '</span>';
                            }
                            return '<span class="font-weight-bold text-dark"><i class="fas fa-user-circle text-primary mr-1"></i>' . Html::encode($name) . '</span>';
                        },
                    ],
                    [
                        'attribute' => 'action',
                        'label' => 'การทำงาน',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'contentOptions' => ['style' => 'text-align: center;'],
                        'value' => function ($model) {
                            switch ($model->action) {
                                case ActionLog::ACTION_DELETE:
                                    return '<span class="badge badge-danger p-2"><i class="fas fa-trash-alt mr-1"></i>ลบ (DELETE)</span>';
                                case ActionLog::ACTION_UPDATE:
                                    return '<span class="badge badge-warning p-2"><i class="fas fa-edit mr-1"></i>แก้ไข (UPDATE)</span>';
                                case ActionLog::ACTION_CREATE:
                                    return '<span class="badge badge-success p-2"><i class="fas fa-plus mr-1"></i>เพิ่ม (CREATE)</span>';
                                default:
                                    return '<span class="badge badge-secondary p-2">' . Html::encode($model->action) . '</span>';
                            }
                        },
                    ],
                    [
                        'attribute' => 'table_name',
                        'label' => 'ตาราง',
                        'headerOptions' => ['style' => 'width: 120px;'],
                        'value' => function ($model) {
                            return $model->table_name;
                        },
                    ],
                    [
                        'attribute' => 'record_id',
                        'label' => 'Record ID',
                        'headerOptions' => ['style' => 'width: 90px; text-align: center;'],
                        'contentOptions' => ['style' => 'text-align: center; font-weight: bold;'],
                    ],
                    [
                        'attribute' => 'record_name',
                        'label' => 'ชื่อข้อมูล/ข้อมูลอ้างอิง',
                        'value' => function ($model) {
                            return !empty($model->record_name) ? $model->record_name : '-';
                        },
                    ],
                    [
                        'attribute' => 'description',
                        'label' => 'รายละเอียด',
                        'value' => function ($model) {
                            return $model->description;
                        },
                    ],
                    [
                        'attribute' => 'ip_address',
                        'label' => 'IP Address',
                        'headerOptions' => ['style' => 'width: 110px; text-align: center;'],
                        'contentOptions' => ['style' => 'text-align: center; font-size: 12px;'],
                        'value' => function ($model) {
                            return !empty($model->ip_address) ? $model->ip_address : '-';
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'ดูข้อมูล',
                        'headerOptions' => ['style' => 'width: 80px; text-align: center;'],
                        'contentOptions' => ['style' => 'text-align: center;'],
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fas fa-eye"></i> ดูรายละเอียด', ['view', 'id' => $model->id], [
                                    'class' => 'btn btn-xs btn-info',
                                    'title' => 'ดูรายละเอียดบันทึก',
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>

            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$js = <<<JS
$(document).on('change', '.actionlog-perpage', function() {
    var perpage = $(this).val();
    var url = new URL(window.location.href);
    url.searchParams.set('perpage', perpage);
    url.searchParams.set('page', 1);
    $.pjax({url: url.toString(), container: '#action-log-grid-pjax', timeout: 5000});
});
JS;
$this->registerJs($js);
?>

