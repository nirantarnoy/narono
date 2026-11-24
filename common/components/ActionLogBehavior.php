<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use common\models\ActionLog;

class ActionLogBehavior extends Component
{
    /**
     * Tables to exclude from action logging
     * @var array
     */
    public static $excludedTables = [
        'action_log',
        'migration',
        'session',
        'login_log',
    ];

    /**
     * Attaches global ActiveRecord event listeners for action logging.
     */
    public static function attach()
    {
        Event::on(BaseActiveRecord::class, BaseActiveRecord::EVENT_BEFORE_DELETE, [self::class, 'handleBeforeDelete']);
        Event::on(BaseActiveRecord::class, BaseActiveRecord::EVENT_BEFORE_UPDATE, [self::class, 'handleBeforeUpdate']);
        Event::on(BaseActiveRecord::class, BaseActiveRecord::EVENT_AFTER_INSERT, [self::class, 'handleAfterInsert']);
    }

    /**
     * Handles EVENT_BEFORE_DELETE
     * 
     * @param Event $event
     */
    public static function handleBeforeDelete($event)
    {
        $model = $event->sender;
        if (!($model instanceof BaseActiveRecord) || self::isExcluded($model)) {
            return;
        }

        try {
            $tableName = self::getCleanTableName($model);
            $pk = $model->getPrimaryKey();
            $recordId = is_array($pk) ? json_encode($pk) : (string)$pk;
            $recordName = ActionLog::resolveRecordName($model);

            $oldAttributes = $model->getAttributes();
            $rawDataBefore = ActionLog::formatRawData($oldAttributes, $model);
            $jsonBefore = json_encode($oldAttributes, JSON_UNESCAPED_UNICODE);

            $description = "ลบข้อมูลตาราง {$tableName}: {$recordName} (ID: {$recordId})";

            self::insertLog([
                'action' => ActionLog::ACTION_DELETE,
                'table_name' => $tableName,
                'model_class' => get_class($model),
                'record_id' => $recordId,
                'record_name' => $recordName,
                'description' => $description,
                'raw_data_before' => "[ข้อมูลเดิมทั้งหมดก่อนลบ]\n" . $rawDataBefore,
                'raw_data_after' => null,
                'data_before_json' => $jsonBefore,
                'data_after_json' => null,
            ]);
        } catch (\Exception $e) {
            Yii::error("ActionLog beforeDelete error: " . $e->getMessage(), __METHOD__);
        }
    }

    /**
     * Handles EVENT_BEFORE_UPDATE
     * 
     * @param Event $event
     */
    public static function handleBeforeUpdate($event)
    {
        $model = $event->sender;
        if (!($model instanceof BaseActiveRecord) || self::isExcluded($model)) {
            return;
        }

        try {
            $dirtyAttributes = $model->getDirtyAttributes();
            if (empty($dirtyAttributes)) {
                return;
            }

            // Ignore if only updated_at timestamp changed
            if (count($dirtyAttributes) === 1 && isset($dirtyAttributes['updated_at'])) {
                return;
            }

            $tableName = self::getCleanTableName($model);
            $pk = $model->getPrimaryKey();
            $recordId = is_array($pk) ? json_encode($pk) : (string)$pk;
            $recordName = ActionLog::resolveRecordName($model);

            $oldAttributes = $model->getOldAttributes();
            $newAttributes = $model->getAttributes();

            $changesText = ActionLog::formatChanges($oldAttributes, $newAttributes, $model);
            $rawDataBefore = ActionLog::formatRawData($oldAttributes, $model);

            $description = "แก้ไขข้อมูลตาราง {$tableName}: {$recordName} (ID: {$recordId})";

            self::insertLog([
                'action' => ActionLog::ACTION_UPDATE,
                'table_name' => $tableName,
                'model_class' => get_class($model),
                'record_id' => $recordId,
                'record_name' => $recordName,
                'description' => $description,
                'raw_data_before' => "[ข้อมูลเดิมก่อนแก้ไข]\n" . $rawDataBefore,
                'raw_data_after' => "[รายการเปลี่ยนแปลง]\n" . $changesText,
                'data_before_json' => json_encode($oldAttributes, JSON_UNESCAPED_UNICODE),
                'data_after_json' => json_encode($newAttributes, JSON_UNESCAPED_UNICODE),
            ]);
        } catch (\Exception $e) {
            Yii::error("ActionLog beforeUpdate error: " . $e->getMessage(), __METHOD__);
        }
    }

    /**
     * Handles EVENT_AFTER_INSERT
     * 
     * @param Event $event
     */
    public static function handleAfterInsert($event)
    {
        $model = $event->sender;
        if (!($model instanceof BaseActiveRecord) || self::isExcluded($model)) {
            return;
        }

        try {
            $tableName = self::getCleanTableName($model);
            $pk = $model->getPrimaryKey();
            $recordId = is_array($pk) ? json_encode($pk) : (string)$pk;
            $recordName = ActionLog::resolveRecordName($model);

            $attributes = $model->getAttributes();
            $rawData = ActionLog::formatRawData($attributes, $model);

            $description = "เพิ่มข้อมูลใหม่ตาราง {$tableName}: {$recordName} (ID: {$recordId})";

            self::insertLog([
                'action' => ActionLog::ACTION_CREATE,
                'table_name' => $tableName,
                'model_class' => get_class($model),
                'record_id' => $recordId,
                'record_name' => $recordName,
                'description' => $description,
                'raw_data_before' => null,
                'raw_data_after' => "[ข้อมูลที่เพิ่มใหม่]\n" . $rawData,
                'data_before_json' => null,
                'data_after_json' => json_encode($attributes, JSON_UNESCAPED_UNICODE),
            ]);
        } catch (\Exception $e) {
            Yii::error("ActionLog afterInsert error: " . $e->getMessage(), __METHOD__);
        }
    }

    /**
     * Insert action log entry via direct SQL command to avoid recursion.
     * 
     * @param array $data
     */
    private static function insertLog(array $data)
    {
        $userId = null;
        $username = null;
        $url = null;
        $method = null;
        $ip = null;
        $ua = null;

        if (Yii::$app instanceof \yii\web\Application) {
            if (!Yii::$app->user->isGuest && Yii::$app->user->identity) {
                $userId = Yii::$app->user->id;
                $username = Yii::$app->user->identity->username ?? null;
            }
            $req = Yii::$app->request;
            if ($req instanceof \yii\web\Request) {
                $url = $req->getUrl();
                $method = $req->getMethod();
                $ip = $req->getUserIP();
                $ua = substr((string)$req->getUserAgent(), 0, 500);
            }
        } elseif (Yii::$app instanceof \yii\console\Application) {
            $method = 'CLI';
            $url = isset($_SERVER['argv']) ? implode(' ', $_SERVER['argv']) : 'console';
            $username = 'console';
        }

        $row = array_merge([
            'user_id' => $userId,
            'username' => $username,
            'url' => $url,
            'method' => $method,
            'ip_address' => $ip,
            'user_agent' => $ua,
            'created_at' => time(),
        ], $data);

        Yii::$app->db->createCommand()->insert('{{%action_log}}', $row)->execute();
    }

    /**
     * Check if model or table is excluded from logging.
     * 
     * @param BaseActiveRecord $model
     * @return bool
     */
    private static function isExcluded($model)
    {
        if ($model instanceof ActionLog) {
            return true;
        }

        try {
            $table = self::getCleanTableName($model);
            return in_array($table, self::$excludedTables, true);
        } catch (\Exception $e) {
            return true;
        }
    }

    /**
     * Get clean table name without {{%...}}
     * 
     * @param BaseActiveRecord $model
     * @return string
     */
    private static function getCleanTableName($model)
    {
        return str_replace(['{{%', '}}', '`'], '', $model::tableName());
    }
}

