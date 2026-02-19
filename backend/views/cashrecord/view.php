<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Cashrecord $model */

$this->title = $model->journal_no;
$this->params['breadcrumbs'][] = ['label' => 'Cashrecords', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cashrecord-view">


    <p>
        <?= Html::a('แก้ไข', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('ลบ', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'journal_no',
            'trans_date',
//            'car_id',
            [
                'attribute' => 'car_id',
                'value' => function ($data) {
                    return \backend\models\Car::findName($data->car_id);
                }
            ],
//            'car_tail_id',
            [
                'attribute' => 'car_tail_id',
                'value' => function ($data) {
                    return \backend\models\Car::findName($data->car_tail_id);
                }
            ],
//            'status',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($data) {
                    if ($data->status == 1) {
                        return '<div class="badge badge-success" >ใช้งาน</div>';
                    } else {
                        return '<div class="badge badge-secondary" >ไม่ใช้งาน</div>';
                    }
                }
            ],
            'bank_account',
            'check_no',
//            'created_at',
//            'create_by',
//            'updated_at',
//            'updated_by',
        ],
    ]) ?>

    <br />
    <h5>รายการค่าใช้จ่าย</h5>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>รายการ</th>
            <th style="text-align: right">จำนวนเงิน</th>
            <th style="text-align: right">หัก ณ ที่จ่าย</th>
            <th>หมายเหตุ</th>
        </tr>
        </thead>
        <tbody>
        <?php $model_line = \common\models\CashRecordLine::find()->where(['car_record_id' => $model->id])->all(); ?>
        <?php foreach ($model_line as $line): ?>
            <tr>
                <td><?= \backend\models\CostTitle::findName($line->cost_title_id) ?></td>
                <td style="text-align: right"><?= number_format($line->amount, 2) ?></td>
                <td style="text-align: right"><?= number_format($line->vat_amount, 2) ?></td>
                <td><?= $line->remark ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <br />
    <h5>สำหรับฝ่ายบัญชี</h5>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ชื่อบัญชี</th>
            <th>รหัสบัญชี</th>
            <th style="text-align: right">เดบิต</th>
            <th style="text-align: right">เครดิต</th>
        </tr>
        </thead>
        <tbody>
        <?php $model_account = \common\models\CashRecordAccount::find()->where(['cash_record_id' => $model->id])->all(); ?>
        <?php foreach ($model_account as $acc): ?>
            <tr>
                <td><?= $acc->account_name ?></td>
                <td><?= $acc->account_code ?></td>
                <td style="text-align: right"><?= number_format($acc->debit, 2) ?></td>
                <td style="text-align: right"><?= number_format($acc->credit, 2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
