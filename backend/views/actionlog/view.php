<?php

use yii\helpers\Html;
use common\models\ActionLog;

/* @var $this yii\web\View */
/* @var $model common\models\ActionLog */

$this->title = 'รายละเอียดประวัติการใช้งาน #' . $model->id . ' (' . $model->action . ')';
$this->params['breadcrumbs'][] = ['label' => 'ประวัติการใช้งานระบบ (Action Log)', 'url' => ['index']];
$this->params['breadcrumbs'][] = '#' . $model->id;

$badgeClass = 'badge-secondary';
$actionText = $model->action;
if ($model->action === ActionLog::ACTION_DELETE) {
    $badgeClass = 'badge-danger';
    $actionText = 'ลบข้อมูล (DELETE)';
} elseif ($model->action === ActionLog::ACTION_UPDATE) {
    $badgeClass = 'badge-warning';
    $actionText = 'แก้ไขข้อมูล (UPDATE)';
} elseif ($model->action === ActionLog::ACTION_CREATE) {
    $badgeClass = 'badge-success';
    $actionText = 'เพิ่มข้อมูล (CREATE)';
}
?>
<div class="action-log-view">
    <div class="mb-3">
        <?= Html::a('<i class="fas fa-arrow-left mr-1"></i> ย้อนกลับ', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <!-- Summary Card -->
    <div class="card card-outline card-primary mb-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-info-circle mr-1"></i> ข้อมูลสรุปรายการ
            </h3>
            <div class="card-tools">
                <span class="badge <?= $badgeClass ?> p-2 font-weight-normal font-size-14"><?= $actionText ?></span>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <tbody>
                    <tr>
                        <th style="width: 20%;">วัน-เวลาที่ดำเนินการ</th>
                        <td style="width: 30%; font-weight: bold;"><?= date('d/m/Y H:i:s', $model->created_at) ?></td>
                        <th style="width: 20%;">ผู้ดำเนินการ</th>
                        <td style="width: 30%;">
                            <strong><i class="fas fa-user-circle text-primary mr-1"></i><?= Html::encode($model->getOperatorName()) ?></strong>
                            <?php if (!empty($model->user_id)): ?>
                                <span class="badge badge-light border ml-1 font-weight-normal">(User ID: <?= Html::encode($model->user_id) ?>)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>ตาราง (Table Name)</th>
                        <td><code><?= Html::encode($model->table_name) ?></code></td>
                        <th>คลาสโมเดล (Model Class)</th>
                        <td><small><?= Html::encode($model->model_class) ?></small></td>
                    </tr>
                    <tr>
                        <th>รหัสข้อมูล (Record ID)</th>
                        <td><span class="badge badge-dark"><?= Html::encode($model->record_id ?: '-') ?></span></td>
                        <th>ชื่อข้อมูล / ข้อมูลอ้างอิง</th>
                        <td style="font-weight: bold; color: #0056b3;"><?= Html::encode($model->record_name ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>รายละเอียด (Description)</th>
                        <td colspan="3"><?= Html::encode($model->description) ?></td>
                    </tr>
                    <tr>
                        <th>Request Info</th>
                        <td colspan="3">
                            <span class="badge badge-info"><?= Html::encode($model->method ?: 'N/A') ?></span>
                            <code><?= Html::encode($model->url ?: '-') ?></code>
                            <span class="text-muted ml-3">IP: <?= Html::encode($model->ip_address ?: '-') ?></span>
                        </td>
                    </tr>
                    <?php if (!empty($model->user_agent)): ?>
                    <tr>
                        <th>User Agent</th>
                        <td colspan="3"><small class="text-muted"><?= Html::encode($model->user_agent) ?></small></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 1: Raw Data Before -->
    <?php if (!empty($model->raw_data_before)): ?>
    <div class="card card-outline card-danger mb-3">
        <div class="card-header bg-light">
            <h3 class="card-title text-danger">
                <i class="fas fa-file-alt mr-1"></i> <b>ข้อมูลเดิมก่อนดำเนินการ (Raw Text Before - พร้อมชื่อข้อมูลอ้างอิง Ref IDs)</b>
            </h3>
        </div>
        <div class="card-body p-2">
            <pre style="background: #fff8f8; color: #212529; font-family: 'Consolas', 'Courier New', monospace; font-size: 13px; line-height: 1.6; border: 1px solid #f5c6cb; border-radius: 4px; padding: 15px; margin: 0; white-space: pre-wrap; word-break: break-all;"><?= Html::encode($model->raw_data_before) ?></pre>
        </div>
    </div>
    <?php endif; ?>

    <!-- Section 2: Raw Data After / Changes -->
    <?php if (!empty($model->raw_data_after)): ?>
    <div class="card card-outline card-success mb-3">
        <div class="card-header bg-light">
            <h3 class="card-title text-success">
                <i class="fas fa-exchange-alt mr-1"></i> <b>ข้อมูลใหม่ / รายการเปลี่ยนแปลง (Changes & New Data)</b>
            </h3>
        </div>
        <div class="card-body p-2">
            <pre style="background: #f8fff8; color: #212529; font-family: 'Consolas', 'Courier New', monospace; font-size: 13px; line-height: 1.6; border: 1px solid #c3e6cb; border-radius: 4px; padding: 15px; margin: 0; white-space: pre-wrap; word-break: break-all;"><?= Html::encode($model->raw_data_after) ?></pre>
        </div>
    </div>
    <?php endif; ?>

    <!-- Section 3: Raw JSON Data (Collapsible) -->
    <div class="card card-outline card-secondary collapsed-card">
        <div class="card-header" data-card-widget="collapse" style="cursor: pointer;">
            <h3 class="card-title text-muted">
                <i class="fas fa-code mr-1"></i> ข้อมูลโครงสร้าง JSON ดิบ (Raw JSON Attributes)
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <?php if (!empty($model->data_before_json)): ?>
                <div class="col-md-6">
                    <label>Data Before (JSON):</label>
                    <pre class="bg-light p-2 border" style="font-size: 11px; max-height: 300px; overflow-y: auto;"><?= Html::encode(json_encode(json_decode($model->data_before_json), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
                </div>
                <?php endif; ?>

                <?php if (!empty($model->data_after_json)): ?>
                <div class="col-md-6">
                    <label>Data After (JSON):</label>
                    <pre class="bg-light p-2 border" style="font-size: 11px; max-height: 300px; overflow-y: auto;"><?= Html::encode(json_encode(json_decode($model->data_after_json), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

