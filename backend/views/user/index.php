<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap4\LinkPager;

$this->title = 'ผู้ใช้งาน';
$this->params['breadcrumbs'][] = $this->title;

$isAdmin = !Yii::$app->user->isGuest && (
    Yii::$app->user->identity->username === 'admin' ||
    Yii::$app->user->identity->username === 'iceadmin' ||
    Yii::$app->user->identity->group_id == 1
);

$columns = [];

if ($isAdmin) {
    $columns[] = [
        'class' => 'yii\grid\CheckboxColumn',
        'headerOptions' => ['style' => 'text-align: center; width: 40px;'],
        'contentOptions' => ['style' => 'text-align: center'],
        'checkboxOptions' => function ($model) {
            $isTargetAdmin = (strtolower(trim($model->username)) === 'admin' || strtolower(trim($model->username)) === 'iceadmin');
            if ($isTargetAdmin) {
                return ['disabled' => true, 'style' => 'display:none;'];
            }
            return ['value' => $model->id, 'class' => 'user-checkbox'];
        },
    ];
}

$columns[] = [
    'class' => 'yii\grid\SerialColumn',
    'headerOptions' => ['style' => 'text-align: center'],
    'contentOptions' => ['style' => 'text-align: center']
];

$columns[] = 'username';

$columns[] = [
    'attribute' => 'group_id',
    'label'=>'กลุ่มผู้ใช้งาน',
    'headerOptions' => ['style' => 'text-align: center'],
    'contentOptions' => ['style' => 'text-align: center'],
    'value' => function ($data) {
        return \backend\models\Usergroup::findName($data->group_id);
    }
];

$columns[] = [
    'attribute' => 'employee_ref_id',
    'label'=>'พนักงาน',
    'headerOptions' => ['style' => 'text-align: center'],
    'contentOptions' => ['style' => 'text-align: left'],
    'value' => function ($data) {
        return \backend\models\Employee::findFullName($data->employee_ref_id);
    }
];

$columns[] = [
    'attribute' => 'status',
    'format' => 'raw',
    'label'=>'สถานะ',
    'headerOptions' => ['style' => 'text-align: center'],
    'contentOptions' => ['style' => 'text-align: center'],
    'value' => function ($data) {
        if ($data->status == 1) {
            return '<div class="badge badge-success">ใช้งาน</div>';
        } else {
            return '<div class="badge badge-dark">ไม่ใช้งาน</div>';
        }
    }
];

$actionColumn = [
    'header' => 'ตัวเลือก',
    'headerOptions' => ['style' => 'text-align:center;', 'class' => 'activity-view-link',],
    'class' => 'yii\grid\ActionColumn',
    'contentOptions' => ['style' => 'text-align: center'],
    'template' => '{view} {update}' . ($isAdmin ? ' {resetpassword}' : '') . ' {delete}',
    'buttons' => [
        'view' => function ($url, $data, $index) {
            $options = [
                'title' => Yii::t('yii', 'View'),
                'aria-label' => Yii::t('yii', 'View'),
                'data-pjax' => '0',
            ];
            return Html::a(
                '<span class="fas fa-eye btn btn-xs btn-default"></span>', $url, $options);
        },
        'update' => function ($url, $data, $index) {
            return Html::a(
                '<span class="fas fa-edit btn btn-xs btn-default"></span>', $url, [
                'title' => Yii::t('yii', 'Update'),
                'aria-label' => Yii::t('yii', 'Update'),
                'id' => 'activity-view-link',
                'data-id' => $index,
                'data-pjax' => '0',
            ]);
        },
        'resetpassword' => function ($url, $data, $index) use ($isAdmin) {
            if (!$isAdmin) {
                return '';
            }
            $isTargetAdmin = (strtolower(trim($data->username)) === 'admin' || strtolower(trim($data->username)) === 'iceadmin');
            if ($isTargetAdmin) {
                return '';
            }
            return Html::a(
                '<span class="fas fa-key btn btn-xs btn-warning"></span>',
                ['resetpassword', 'id' => $data->id],
                [
                    'title' => 'รีเซ็ตรหัสผ่านเป็น Na12345678',
                    'aria-label' => 'รีเซ็ตรหัสผ่านเป็น Na12345678',
                    'data-confirm' => 'ต้องการรีเซ็ตรหัสผ่านของ "' . Html::encode($data->username) . '" เป็น Na12345678 ใช่หรือไม่?',
                    'data-method' => 'post',
                    'data-pjax' => '0',
                    'style' => 'margin-left: 2px; margin-right: 2px;',
                ]
            );
        },
        'delete' => function ($url, $data, $index) {
            $options = array_merge([
                'title' => Yii::t('yii', 'Delete'),
                'aria-label' => Yii::t('yii', 'Delete'),
                'data-url' => $url,
                'data-var' => $data->id,
                'onclick' => 'recDelete($(this));'
            ]);
            return Html::a('<span class="fas fa-trash-alt btn btn-xs btn-default"></span>', 'javascript:void(0)', $options);
        }
    ]
];

$columns[] = $actionColumn;

?>
<div class="user-index">
    <?php Pjax::begin(['id' => 'pjax-user-grid']); ?>
    <div class="row">
        <div class="col-lg-8">
            <p>
                <?= Html::a(Yii::t('app', '<i class="fa fa-plus"></i> สร้างใหม่'), ['create'], ['class' => 'btn btn-success']) ?>
                <?php if ($isAdmin): ?>
                    <button type="button" class="btn btn-warning btn-reset-selected" style="margin-left: 5px;">
                        <i class="fas fa-key"></i> รีเซ็ตรหัสผ่านที่เลือก (Na12345678)
                    </button>
                    <?= Html::a('<i class="fas fa-unlock-alt"></i> รีเซ็ตรหัสผ่านทั้งหมด ยกเว้น Admin (Na12345678)', ['resetpassword-all'], [
                        'class' => 'btn btn-danger',
                        'style' => 'margin-left: 5px;',
                        'data-confirm' => 'คำเตือน: ต้องการรีเซ็ตรหัสผ่านของผู้ใช้งานทั้งหมด (ยกเว้น Admin) เป็น Na12345678 ใช่หรือไม่?',
                        'data-method' => 'post',
                    ]) ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="col-lg-4" style="text-align: right">
            <form id="form-perpage" class="form-inline float-right" action="<?= Url::to(['user/index'], true) ?>"
                  method="post">
                <div class="form-group">
                    <label>แสดง </label>
                    <select class="form-control ml-1 mr-1" name="perpage" id="perpage">
                        <option value="20" <?= $perpage == '20' ? 'selected' : '' ?>>20</option>
                        <option value="50" <?= $perpage == '50' ? 'selected' : '' ?> >50</option>
                        <option value="100" <?= $perpage == '100' ? 'selected' : '' ?>>100</option>
                    </select>
                    <label> รายการ</label>
                </div>
            </form>
        </div>
    </div>
    <?php echo $this->render('_search', ['model' => $searchModel,'viewstatus'=>$viewstatus]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'emptyCell' => '-',
        'layout' => "{items}\n{summary}\n<div class='text-center'>{pager}</div>",
        'summary' => "แสดง {begin} - {end} ของทั้งหมด {totalCount} รายการ",
        'showOnEmpty' => false,
        'id' => 'product-grid',
        'emptyText' => '<div style="color: red;text-align: center;"> <b>ไม่พบรายการไดๆ</b> <span> เพิ่มรายการโดยการคลิกที่ปุ่ม </span><span class="text-success">"สร้างใหม่"</span></div>',
        'columns' => $columns,
        'pager' => ['class' => LinkPager::className()],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

<?php
if ($isAdmin) {
    $resetSelectedUrl = Url::to(['user/resetpassword-selected']);
    $js = <<<JS
$(document).on('click', '.btn-reset-selected', function(e) {
    e.preventDefault();
    var keys = $('#product-grid').yiiGridView('getSelectedRows');
    if (!keys || keys.length === 0) {
        alert('กรุณาเลือกผู้ใช้งานที่ต้องการรีเซ็ตรหัสผ่าน');
        return;
    }
    if (confirm('ต้องการรีเซ็ตรหัสผ่านของผู้ใช้งานที่เลือกจำนวน ' + keys.length + ' รายการ เป็น Na12345678 ใช่หรือไม่?')) {
        var form = $('<form>', {
            'action': '{$resetSelectedUrl}',
            'method': 'POST'
        });
        $.each(keys, function(i, id) {
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'selection[]',
                'value': id
            }));
        });
        $('body').append(form);
        form.submit();
    }
});
JS;
    $this->registerJs($js);
}
?>
