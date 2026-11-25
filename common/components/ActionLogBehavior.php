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

        // CRITICAL SECURITY FAILSAFE: Never allow unauthenticated guests to delete database records!
        if (Yii::$app instanceof \yii\web\Application && Yii::$app->user->isGuest) {
            if ($event instanceof \yii\base\ModelEvent) {
                $event->isValid = false; // Cancels ActiveRecord delete completely!
            }
            $ip = Yii::$app->request ? Yii::$app->request->getUserIP() : 'unknown';
            Yii::warning("SECURITY ALERT: Blocked unauthenticated delete attempt on " . get_class($model) . " from IP: " . $ip, __METHOD__);
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
            ], $model);
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
            ], $model);
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
            ], $model);
        } catch (\Exception $e) {
            Yii::error("ActionLog afterInsert error: " . $e->getMessage(), __METHOD__);
        }
    }

    /**
     * Insert action log entry via direct SQL command to avoid recursion.
     * 
     * @param array $data
     * @param BaseActiveRecord|null $model
     */
    private static function insertLog(array $data, $model = null)
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
            } else {
                // If identity not yet loaded or user object guest, check active session
                try {
                    $session = Yii::$app->session;
                    if ($session) {
                        if ($session->get('__id')) {
                            $userId = $session->get('__id');
                        } elseif (isset($_SESSION['driver_login'])) {
                            $userId = $_SESSION['driver_login'];
                        } elseif (isset($_SESSION['user_id'])) {
                            $userId = $_SESSION['user_id'];
                        }
                    }
                } catch (\Exception $e) {
                }
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

        $isWeb = Yii::$app instanceof \yii\web\Application;

        // Fallback: If userId is still empty and this is CLI/console, check model's audit attributes
        if (empty($userId) && !$isWeb && $model instanceof BaseActiveRecord) {
            if ($model->hasAttribute('updated_by') && !empty($model->updated_by)) {
                $userId = $model->updated_by;
            } elseif ($model->hasAttribute('created_by') && !empty($model->created_by)) {
                $userId = $model->created_by;
            } elseif ($model->hasAttribute('user_id') && !empty($model->user_id) && self::getCleanTableName($model) !== 'user') {
                $userId = $model->user_id;
            }
        }

        // If we have userId but username is empty, look up username from user table
        if (!empty($userId) && (empty($username) || $username === '-')) {
            try {
                $dbUser = (new \yii\db\Query())
                    ->select(['username'])
                    ->from('{{%user}}')
                    ->where(['id' => $userId])
                    ->scalar();
                if ($dbUser) {
                    $username = $dbUser;
                } else {
                    $username = 'User ID: ' . $userId;
                }
            } catch (\Exception $e) {
                $username = 'User ID: ' . $userId;
            }
        }

        // Ensure username is NEVER empty, blank, or dash
        if (empty($username) || trim($username) === '' || trim($username) === '-') {
            if (!empty($userId)) {
                $username = 'User ID: ' . $userId;
            } elseif ($method === 'CLI') {
                $username = 'console';
            } else {
                $username = 'System';
            }
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

