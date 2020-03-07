<?php

namespace app\modules\core;

use yii\base\BootstrapInterface;

/**
 * core module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\core\controllers';

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            'POST login' => 'core/auth/login',
            'POST logout' => 'core/auth/logout',
        ], false);
    }
}
