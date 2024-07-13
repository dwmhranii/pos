<?php
namespace app\helpers;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ModelHelper
{
    /**
     * Create multiple models.
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
     * Load multiple models.
     * @param array $models
     * @param array $data
     * @return boolean
     */
    public static function loadMultiple($models, $data)
    {
        $success = false;
        foreach ($models as $i => $model) {
            if (isset($data[$i]) && $model->load($data[$i], '')) {
                $success = true;
            }
        }

        return $success;
    }
}
