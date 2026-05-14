<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $data */

$this->title = 'ผลการตรวจสอบข้อมูลนำเข้า';
$this->params['breadcrumbs'][] = ['label' => 'จัดคิวงาน', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="import-details-demo">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h3 class="card-title"><i class="fas fa-list"></i> <?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <p class="text-muted">ตรวจสอบความถูกต้องของข้อมูลก่อนบันทึกเข้าระบบ (ขณะนี้ยังไม่ได้บันทึกข้อมูลใดๆ)</p>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="demo-table">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Master (AH)</th>
                            <th>เลขที่ใบตั้ง</th>
                            <th>จำนวน</th>
                            <th>น้ำหนัก</th>
                            <th>ชนิด</th>
                            <th>ข้อความต้นฉบับ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-danger">ไม่พบข้อมูลที่สามารถแยกแยะได้</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td class="text-secondary"><?= Html::encode($item['master_data']) ?></td>
                                    <td class="font-weight-bold text-primary"><?= Html::encode($item['bill_no']) ?></td>
                                    <td><?= Html::encode($item['qty']) ?></td>
                                    <td><?= Html::encode($item['weight']) ?></td>
                                    <td><?= Html::encode($item['type']) ?></td>
                                    <td><small class="text-muted"><?= Html::encode($item['raw_text']) ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-right">
            <?= Html::a('<i class="fas fa-chevron-left"></i> ย้อนกลับ', ['index'], ['class' => 'btn btn-secondary']) ?>
            <?php if (!empty($data)): ?>
                <?= Html::button('<i class="fas fa-save"></i> ยืนยันการนำเข้า (Coming Soon)', ['class' => 'btn btn-success disabled', 'title' => 'ฟังก์ชันนี้จะเปิดให้ใช้งานเร็วๆ นี้']) ?>
            <?php endif; ?>
        </div>
    </div>
</div>
