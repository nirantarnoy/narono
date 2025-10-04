<?php

namespace backend\helpers; // หรือ common\helpers ตามโครงสร้างของคุณ

use Yii;
use yii\helpers\ArrayHelper;

class Model extends \yii\base\Model
{
    /**
     * Creates and populates a set of models.
     *
     * @param string $modelClass
     * @param array $multipleModels
     * @return array
     */
    public static function createMultiple($modelClass, $multipleModels = [])
    {
        $model = new $modelClass;
        $formName = $model->formName();
        $post = Yii::$app->request->post($formName);
        $models = [];

        if (!empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }

    /**
     * Load multiple models
     *
     * @param array $models
     * @param array $data
     * @param string|null $formName
     * @return boolean
     */
    public static function loadMultiple($models, $data, $formName = null)
    {
        if ($formName === null) {
            /* @var $first \yii\base\Model */
            $first = reset($models);
            if ($first === false) {
                return false;
            }
            $formName = $first->formName();
        }

        $success = false;
        foreach ($models as $i => $model) {
            /* @var $model \yii\base\Model */
            if ($formName == '') {
                if (!empty($data[$i]) && $model->load($data[$i], '')) {
                    $success = true;
                }
            } elseif (!empty($data[$formName][$i]) && $model->load($data[$formName][$i], '')) {
                $success = true;
            }
        }

        return $success;
    }

    /**
     * Validate multiple models
     *
     * @param array $models
     * @param array $attributeNames
     * @param boolean $clearErrors
     * @return boolean
     */
    public static function validateMultiple($models, $attributeNames = null, $clearErrors = true)
    {
        $valid = true;
        /* @var $model \yii\base\Model */
        foreach ($models as $model) {
            $valid = $model->validate($attributeNames, $clearErrors) && $valid;
        }
        return $valid;
    }
}