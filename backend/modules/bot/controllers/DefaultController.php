<?php

namespace app\modules\bot\controllers;

use yii\filters\Cors;
use yii\web\Controller;

/**
 * Default controller for the `bot` module
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => ['dust.lo', 'dust.games'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => null,
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Expose-Headers' => [],
                ],

            ],
        ];
    }

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
