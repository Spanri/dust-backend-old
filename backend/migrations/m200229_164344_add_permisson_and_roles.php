<?php

use yii\db\Migration;

/**
 * Class m200229_164344_add_permisson_and_roles
 */
class m200229_164344_add_permisson_and_roles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $admin = $auth->createRole("Admin");
        $admin->description = 'Администратор';
        $auth->add($admin);

        $moderator = $auth->createRole("Moderator");
        $moderator->description = 'Модератор';
        $auth->add($moderator);

        $bot = $auth->createRole("Bot");
        $bot->description = 'Бот';
        $auth->add($bot);

        $user = $auth->createRole("User");
        $user->description = 'Пользователь';
        $auth->add($user);

        $guest = $auth->createRole("Guest");
        $guest->description = 'Гость';
        $auth->add($guest);

        $auth->addChild($admin, $moderator);
        $auth->addChild($moderator, $user);
        $auth->addChild($user, $guest);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $admin = $auth->createRole("Admin");
        $moderator = $auth->createRole("Moderator");
        $user = $auth->createRole("User");
        $bot = $auth->createRole("Bot");
        $guest = $auth->createRole("Guest");

        $auth->removeChild($admin, $moderator);
        $auth->removeChild($moderator, $user);
        $auth->removeChild($user, $guest);

        $auth->remove($admin);
        $auth->remove($moderator);
        $auth->remove($bot);
        $auth->remove($user);
        $auth->remove($guest);
    }

}
