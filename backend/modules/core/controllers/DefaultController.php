<?php

namespace app\modules\core\controllers;

use yii\web\Controller;

/**
 * Default controller for the `core` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return array
     */
    public function actionIndex()
    {
        return [
            'name' => "DUST API",
            'version' => "0.1.0",
            'docs' => ''
        ];
    }
}
