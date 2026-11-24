<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\models\ActionLog;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ActionLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $perpage int */

$this->title = 'ประวัติการใช้งานระบบ (Action Log)';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-log-index">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-history mr-2"></i><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <form method="get" action="<?= Url::to(['actionlog/index']) ?>" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" name="ActionLogSearch[globalSearch]" class="form-control" 
                                   placeholder="ค้นหาชื่อ, ตาราง, ข้อมูล..." 
                                   value="<?= Html::encode($searchModel->globalSearch) ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="ActionLogSearch[action]" class="form-control" onchange="this.form.submit()">
                            <option value="">-- ทุกการทำงาน --</option>
                            <option value="<?= ActionLog::ACTION_DELETE ?>" <?= $searchModel->action === ActionLog::ACTION_DELETE ? 'selected' : '' ?>>ลบข้อมูล (DELETE)</option>
                            <option value="<?= ActionLog::ACTION_UPDATE ?>" <?= $searchModel->action === ActionLog::ACTION_UPDATE ? 'selected' : '' ?>>แก้ไขข้อมูล (UPDATE)</option>
                            <option value="<?= ActionLog::ACTION_CREATE ?>" <?= $searchModel->action === ActionLog::ACTION_CREATE ? 'selected' : '' ?>>เพิ่มข้อมูล (CREATE)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="ActionLogSearch[table_name]" class="form-control" 
                               placeholder="ระบุชื่อตาราง..." 
                               value="<?= Html::encode($searchModel->table_name) ?>">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="ActionLogSearch[username]" class="form-control" 
                               placeholder="ผู้ใช้งาน..." 
                               value="<?= Html::encode($searchModel->username) ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i>ค้นหา</button>
                        <a href="<?= Url::to(['actionlog/index']) ?>" class="btn btn-secondary"><i class="fas fa-undo mr-1"></i>รีเซ็ต</a>
                    </div>
                </div>
            </form>

            <?php Pjax::begin(['id' => 'action-log-grid-pjax']); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'emptyCell' => '-',
                'layout' => "{items}\n<div class='row mt-3'><div class='col-sm-12 col-md-5'>{summary}</div><div class='col-sm-12 col-md-7 text-right'>{pager}</div></div>",
                'summary' => "แสดง <b>{begin} - {end}</b> จากทั้งหมด <b>{totalCount}</b> รายการ",
                'tableOptions' => ['class' => 'table table-bordered table-striped table-hover text-nowrap'],
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
                        'label' => 'ผู้ใช้งาน',
                        'headerOptions' => ['style' => 'width: 120px;'],
                        'value' => function ($model) {
                            return !empty($model->username) ? $model->username : '-';
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

