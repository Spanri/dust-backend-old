<?php

namespace app\modules\bot;

use yii\base\BootstrapInterface;

/**
 * bot module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\bot\controllers';

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            // получить коины каждого пользователя
            'GET bot/arra-bot/coins' => 'bot/arra-bot/coins-list',
            // получить коины конкретного пользователя
            'GET bot/arra-bot/type/<type:\d+>/account_id/<account_id:\w+>/coins' => 'bot/arra-bot/coins-index',
            // настроить коины конкретного пользователя
            'PUT bot/arra-bot/type/<type:\d+>/account_id/<account_id:\w+>/coins' => 'bot/arra-bot/coins-update',
            // настроить коины нескольких пользователей
            'PUT bot/arra-bot/type/<type:\d+>/coins' => 'bot/arra-bot/coins-update',
        ], false);
    }
}
