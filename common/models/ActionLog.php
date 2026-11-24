<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * ActionLog model
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $username
 * @property string $action CREATE, UPDATE, DELETE
 * @property string $table_name
 * @property string $model_class
 * @property string|null $record_id
 * @property string|null $record_name
 * @property string|null $description
 * @property string|null $raw_data_before
 * @property string|null $raw_data_after
 * @property string|null $data_before_json
 * @property string|null $data_after_json
 * @property string|null $url
 * @property string|null $method
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property int $created_at
 */
class ActionLog extends ActiveRecord
{
    const ACTION_CREATE = 'CREATE';
    const ACTION_UPDATE = 'UPDATE';
    const ACTION_DELETE = 'DELETE';

    /**
     * In-memory cache for resolved reference names
     * @var array
     */
    private static $refCache = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%action_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action', 'table_name', 'model_class', 'created_at'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['description', 'raw_data_before', 'raw_data_after', 'data_before_json', 'data_after_json'], 'string'],
            [['action'], 'string', 'max' => 50],
            [['table_name'], 'string', 'max' => 100],
            [['model_class', 'username', 'record_name'], 'string', 'max' => 255],
            [['record_id'], 'string', 'max' => 100],
            [['url', 'user_agent'], 'string', 'max' => 500],
            [['method'], 'string', 'max' => 20],
            [['ip_address'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'ผู้ใช้งาน (User ID)',
            'username' => 'ชื่อผู้ใช้งาน (Username)',
            'action' => 'การทำงาน (Action)',
            'table_name' => 'ตาราง (Table)',
            'model_class' => 'โมเดล (Model)',
            'record_id' => 'รหัสข้อมูล (Record ID)',
            'record_name' => 'ชื่อข้อมูล/ข้อมูลอ้างอิง',
            'description' => 'รายละเอียด',
            'raw_data_before' => 'ข้อมูลเดิม (ก่อนแก้ไข/ลบ)',
            'raw_data_after' => 'ข้อมูลใหม่ / การเปลี่ยนแปลง',
            'data_before_json' => 'Data Before (JSON)',
            'data_after_json' => 'Data After (JSON)',
            'url' => 'URL / Route',
            'method' => 'Method',
            'ip_address' => 'IP Address',
            'user_agent' => 'User Agent',
            'created_at' => 'วันที่/เวลาบันทึก',
        ];
    }

    /**
     * Resolve a human-readable display name for the model instance
     * 
     * @param ActiveRecord $model
     * @return string
     */
    public static function resolveRecordName($model)
    {
        if (!$model) {
            return '';
        }

        $tableName = str_replace(['{{%', '}}'], '', $model::tableName());

        // Model specific checks
        if ($model->hasProperty('username') && !empty($model->username)) {
            return (string)$model->username;
        }
        if ($model->hasProperty('work_queue_no') && !empty($model->work_queue_no)) {
            return (string)$model->work_queue_no;
        }
        if ($model->hasProperty('plate_no') && !empty($model->plate_no)) {
            return (string)$model->plate_no;
        }
        if ($model->hasProperty('fname')) {
            $fname = $model->fname ?? '';
            $lname = $model->lname ?? '';
            $fullName = trim($fname . ' ' . $lname);
            if (!empty($fullName)) {
                return $fullName;
            }
        }
        if ($model->hasProperty('name') && !empty($model->name)) {
            return (string)$model->name;
        }
        if ($model->hasProperty('title') && !empty($model->title)) {
            return (string)$model->title;
        }
        if ($model->hasProperty('po_number') && !empty($model->po_number)) {
            return (string)$model->po_number;
        }
        if ($model->hasProperty('code') && !empty($model->code)) {
            return (string)$model->code;
        }

        // Check attributes array if not in property
        $attrs = $model->getAttributes();
        foreach (['username', 'work_queue_no', 'plate_no', 'name', 'title', 'code', 'description'] as $key) {
            if (!empty($attrs[$key])) {
                return (string)$attrs[$key];
            }
        }

        $pk = $model->getPrimaryKey();
        return is_array($pk) ? json_encode($pk) : (string)$pk;
    }

    /**
     * Resolves a reference ID (foreign key) to its human-readable title/name.
     * 
     * @param string $attribute
     * @param mixed $value
     * @param string|null $currentTable
     * @return string|null
     */
    public static function resolveRefName($attribute, $value, $currentTable = null)
    {
        if ($value === null || $value === '' || $value === 0 || $value === '0') {
            return null;
        }

        $db = Yii::$app->db;
        $cacheKey = "{$attribute}:{$value}";
        if (isset(self::$refCache[$cacheKey])) {
            return self::$refCache[$cacheKey];
        }

        $resolved = null;

        // Specific well-known column mappings
        switch ($attribute) {
            case 'created_by':
            case 'updated_by':
            case 'user_id':
                $resolved = self::queryValue('user', 'username', 'id', $value);
                break;

            case 'group_id':
            case 'user_group_id':
                $resolved = self::queryValue('usergroup', 'name', 'id', $value);
                break;

            case 'employee_ref_id':
            case 'emp_assign':
            case 'emp_id':
            case 'driver_id':
            case 'approval_emp_id':
            case 'emp_inform_id':
            case 'emp_notify_id':
                $resolved = self::queryEmployeeName($value);
                break;

            case 'customer_id':
            case 'customer_child_id':
                $resolved = self::queryValue('customer', 'name', 'id', $value);
                break;

            case 'customer_group_id':
                $resolved = self::queryValue('customer_group', 'name', 'id', $value);
                break;

            case 'car_id':
            case 'tail_id':
            case 'car_tail_id':
            case 'tail_back_id':
                $resolved = self::queryCarName($value);
                break;

            case 'car_type_id':
                $resolved = self::queryValue('cartype', 'name', 'id', $value);
                break;

            case 'company_id':
            case 'compnay_id':
                $resolved = self::queryValue('company', 'name', 'id', $value);
                break;

            case 'branch_id':
                $resolved = self::queryValue('branch', 'name', 'id', $value);
                break;

            case 'work_type_id':
            case 'work_option_type_id':
                $resolved = self::queryValue('work_option_type', 'name', 'id', $value);
                break;

            case 'dropoff_id':
            case 'drop_off_id':
            case 'dropoff_place_id':
                $resolved = self::queryValue('dropoff_place', 'name', 'id', $value);
                break;

            case 'warehouse_id':
                $resolved = self::queryValue('warehouse', 'name', 'id', $value);
                break;

            case 'vendor_id':
                $resolved = self::queryValue('vendor', 'name', 'id', $value);
                break;

            case 'vendor_group_id':
                $resolved = self::queryValue('vendor_group', 'name', 'id', $value);
                break;

            case 'work_queue_id':
                $resolved = self::queryValue('work_queue', 'work_queue_no', 'id', $value);
                break;

            case 'province_id':
            case 'des_province_id':
                $resolved = self::queryValue('province', 'name', 'id', $value);
                break;

            case 'district_id':
            case 'city_id':
                $resolved = self::queryValue('amphur', 'name', 'id', $value);
                break;

            case 'payment_method_id':
                $resolved = self::queryValue('payment_method', 'name', 'id', $value);
                break;

            case 'payment_term_id':
                $resolved = self::queryValue('payment_term', 'name', 'id', $value);
                break;

            case 'cus_po_id':
                $resolved = self::queryValue('customer_po', 'po_number', 'id', $value);
                break;

            case 'cus_po_name_id':
                $resolved = self::queryValue('customer_po_line', 'item_name', 'id', $value);
                break;

            case 'status':
                $resolved = self::resolveStatusName($value);
                break;
        }

        // Dynamic heuristic if not resolved yet and ends with _id
        if ($resolved === null && substr($attribute, -3) === '_id') {
            $baseName = substr($attribute, 0, -3);
            $possibleTables = [$baseName, 'car_' . $baseName, $baseName . '_title'];
            foreach ($possibleTables as $t) {
                if ($db->getTableSchema($t) !== null) {
                    $cols = array_keys($db->getTableSchema($t)->columns);
                    foreach (['name', 'title', 'work_queue_no', 'code', 'description', 'po_number'] as $c) {
                        if (in_array($c, $cols)) {
                            $resolved = self::queryValue($t, $c, 'id', $value);
                            if ($resolved !== null) {
                                break 2;
                            }
                        }
                    }
                }
            }
        }

        self::$refCache[$cacheKey] = $resolved;
        return $resolved;
    }

    /**
     * Formats attributes into human-readable raw text with resolved reference names.
     * 
     * @param array $attributes
     * @param ActiveRecord|null $model
     * @return string
     */
    public static function formatRawData($attributes, $model = null)
    {
        if (empty($attributes)) {
            return '';
        }

        $tableName = $model ? str_replace(['{{%', '}}'], '', $model::tableName()) : null;
        $labels = $model ? $model->attributeLabels() : [];

        $lines = [];
        foreach ($attributes as $key => $val) {
            $label = $labels[$key] ?? $key;

            // Format timestamp values
            if (in_array($key, ['created_at', 'updated_at', 'create_at']) && is_numeric($val) && $val > 1000000000) {
                $displayVal = $val . ' (' . date('d/m/Y H:i:s', $val) . ')';
            } elseif ($key === 'status') {
                $statusText = self::resolveStatusName($val);
                $displayVal = $statusText ? "{$val} ({$statusText})" : (string)$val;
            } else {
                // Check if this attribute has a resolved ref name
                $refName = self::resolveRefName($key, $val, $tableName);
                if ($refName !== null) {
                    $displayVal = "{$val} ({$refName})";
                } else {
                    $displayVal = ($val === null) ? 'NULL' : (is_scalar($val) ? (string)$val : json_encode($val, JSON_UNESCAPED_UNICODE));
                }
            }

            $lines[] = sprintf("%-24s : %s", "{$label} [{$key}]", $displayVal);
        }

        return implode("\n", $lines);
    }

    /**
     * Formats changed attributes into a clean comparison text.
     * 
     * @param array $oldAttributes
     * @param array $newAttributes
     * @param ActiveRecord|null $model
     * @return string
     */
    public static function formatChanges($oldAttributes, $newAttributes, $model = null)
    {
        $tableName = $model ? str_replace(['{{%', '}}'], '', $model::tableName()) : null;
        $labels = $model ? $model->attributeLabels() : [];

        $lines = [];
        foreach ($newAttributes as $key => $newVal) {
            $oldVal = $oldAttributes[$key] ?? null;
            if ($oldVal != $newVal) {
                $label = $labels[$key] ?? $key;

                $oldRef = self::resolveRefName($key, $oldVal, $tableName);
                $newRef = self::resolveRefName($key, $newVal, $tableName);

                $oldDisplay = ($oldRef !== null) ? "{$oldVal} ({$oldRef})" : (($oldVal === null) ? 'NULL' : (string)$oldVal);
                $newDisplay = ($newRef !== null) ? "{$newVal} ({$newRef})" : (($newVal === null) ? 'NULL' : (string)$newVal);

                if (in_array($key, ['updated_at', 'created_at', 'create_at']) && is_numeric($oldVal) && is_numeric($newVal)) {
                    $oldDisplay = date('d/m/Y H:i:s', $oldVal);
                    $newDisplay = date('d/m/Y H:i:s', $newVal);
                }

                $lines[] = sprintf("%-24s : %s  =>  %s", "{$label} [{$key}]", $oldDisplay, $newDisplay);
            }
        }

        return implode("\n", $lines);
    }

    private static function queryValue($table, $column, $idCol, $idVal)
    {
        try {
            $schema = Yii::$app->db->getTableSchema($table);
            if (!$schema) return null;
            $row = (new \yii\db\Query())
                ->select([$column])
                ->from($table)
                ->where([$idCol => $idVal])
                ->one();
            return $row ? (string)$row[$column] : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private static function queryEmployeeName($idVal)
    {
        try {
            $row = (new \yii\db\Query())
                ->select(['fname', 'lname'])
                ->from('employee')
                ->where(['id' => $idVal])
                ->one();
            if ($row) {
                return trim(($row['fname'] ?? '') . ' ' . ($row['lname'] ?? ''));
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private static function queryCarName($idVal)
    {
        try {
            $row = (new \yii\db\Query())
                ->select(['plate_no', 'name'])
                ->from('car')
                ->where(['id' => $idVal])
                ->one();
            if ($row) {
                return !empty($row['name']) ? $row['name'] : ($row['plate_no'] ?? null);
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private static function resolveStatusName($statusVal)
    {
        $map = [
            1 => 'ใช้งาน (Active)',
            0 => 'ไม่ใช้งาน (Inactive)',
            2 => 'ยกเลิก (Cancelled)',
            10 => 'Active',
            9 => 'Inactive',
        ];
        return $map[(int)$statusVal] ?? null;
    }
}

