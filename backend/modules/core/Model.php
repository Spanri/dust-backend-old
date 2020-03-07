<?php

namespace app\modules\core;

/**
 * Класс для модели ввода данных для REST API
 */
abstract class Model extends \yii\base\Model
{
    /**
     * @inheritdoc
     */
    public function formName()
    {
        return '';
    }
}