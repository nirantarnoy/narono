<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ChartofaccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ผังบัญชี (Chart of Accounts)';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chartofaccount-index">
    <?php Pjax::begin(); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title text-primary font-weight-bold" style="margin-top: 10px;"><i class="fas fa-university mr-1"></i> <?= Html::encode($this->title) ?></h3>
                    <div class="card-tools ml-auto">
                        <?= Html::a('<i class="fas fa-plus-circle"></i> สร้างใหม่', ['create'], ['class' => 'btn btn-success shadow-sm mr-1']) ?>
                        <?= Html::button('<i class="fas fa-file-import"></i> นำเข้า Excel', [
                            'class' => 'btn btn-outline-primary shadow-sm',
                            'data-toggle' => 'modal',
                            'data-target' => '#importModal'
                        ]) ?>
                    </div>
                </div>

                <!-- Import Modal -->
                <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="importModalLabel"><i class="fas fa-file-import mr-1"></i> นำเข้าข้อมูลผังบัญชีจาก Excel</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?= Html::beginForm(['import'], 'post', ['enctype' => 'multipart/form-data']) ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="font-weight-bold">เลือกไฟล์ Excel (.xlsx, .xls)</label>
                                    <?= Html::fileInput('import_file', null, ['class' => 'form-control', 'accept' => '.xlsx, .xls', 'required' => true]) ?>
                                    <div class="alert alert-info mt-3 py-2 px-3">
                                        <small>
                                            <i class="fas fa-info-circle mr-1"></i> <b>โครงสร้างไฟล์ที่รองรับ:</b><br>
                                            - ข้อมูลเริ่มจาก <b>แถวที่ 6</b> เป็นต้นไป<br>
                                            - Column B: รหัสบัญชี<br>
                                            - Column C: รหัสบัญชีย่อย<br>
                                            - Column D: ชื่อบัญชี (ไทย)<br>
                                            - Column E: ชื่อบัญชี (อังกฤษ)
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-primary px-4">เริ่มนำเข้า</button>
                            </div>
                            <?= Html::endForm() ?>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => true,
                            'striped' => true,
                            'hover' => true,
                            'bordered' => true,
                            'tableOptions' => ['class' => 'table table-hover table-striped'],
                            'columns' => [
                                [
                                    'class' => 'kartik\grid\SerialColumn',
                                    'contentOptions' => ['style' => 'text-align: center'],
                                ],
                                'account_code',
                                'sub_account_code',
                                'name',
                                'name_en',
                                [
                                    'attribute' => 'status',
                                    'format' => 'html',
                                    'value' => function($model) {
                                        return $model->status == 1 ? '<span class="badge badge-success">ใช้งาน</span>' : '<span class="badge badge-secondary">ไม่ใช้งาน</span>';
                                    },
                                    'filter' => [1 => 'ใช้งาน', 0 => 'ไม่ใช้งาน'],
                                    'contentOptions' => ['style' => 'text-align: center;'],
                                    'headerOptions' => ['style' => 'text-align: center;'],
                                ],
                                [
                                    'class' => 'kartik\grid\ActionColumn',
                                    'header' => 'ตัวเลือก',
                                    'headerOptions' => ['style' => 'text-align: center;'],
                                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                                    'template' => '{view} {update} {delete}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {
                                            return Html::a('<span class="fas fa-eye btn btn-xs btn-default"></span>', $url, [
                                                'title' => 'ดูรายละเอียด',
                                                'data-pjax' => '0',
                                            ]);
                                        },
                                        'update' => function ($url, $model, $key) {
                                            return Html::a('<span class="fas fa-edit btn btn-xs btn-default"></span>', $url, [
                                                'title' => 'แก้ไข',
                                                'data-pjax' => '0',
                                            ]);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                            return Html::a('<span class="fas fa-trash-alt btn btn-xs btn-default"></span>', $url, [
                                                'title' => 'ลบ',
                                                'data-confirm' => 'คุณต้องการลบรายการนี้ใช่หรือไม่?',
                                                'data-method' => 'post',
                                                'data-pjax' => '0',
                                            ]);
                                        },
                                    ],
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php Pjax::end(); ?>
</div>
