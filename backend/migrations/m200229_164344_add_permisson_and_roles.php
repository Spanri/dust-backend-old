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

        $guest = $auth->createRole("Guest");
        $guest->description = 'Гость';
        $auth->add($guest);

        $user = $auth->createRole("User");
        $user->description = 'Пользователь';
        $auth->add($user);

        $moderator = $auth->createRole("Moderator");
        $moderator->description = 'Модератор';
        $auth->add($moderator);

        $admin = $auth->createRole("Admin");
        $admin->description = 'Администратор';
        $auth->add($admin);

        $baseAccess = $auth->createPermission('base_access');
        $baseAccess->description = 'Базовый доступ';

        // $canCreate = $auth->createPermission(UserPermission::CAN_CREATE);
        // $canCreate->description = 'Создание элемента';

        // $canViewCollection = $auth->createPermission(UserPermission::CAN_VIEW_COLLECTION);
        // $canViewCollection->description = 'Просмотр коллекции';

        // $photoReport = $auth->createPermission(UserPermission::PHOTOREPORT);
        // $photoReport->description = 'Фотоотчет';

        // $auth->add($canCreate);
        // $auth->add($canViewCollection);
        $auth->add($baseAccess);
        // $auth->add($photoReport);

        // $auth->addChild($user, $canCreate);
        // $auth->addChild($user, $canViewCollection);

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
        $guest = $auth->createRole("Guest");

        // $canCreate = $auth->createPermission(UserPermission::CAN_CREATE);
        // $canViewCollection = $auth->createPermission(UserPermission::CAN_VIEW_COLLECTION);
        $baseAccess = $auth->createPermission('base_access');
        // $photoReport = $auth->createPermission(UserPermission::PHOTOREPORT);

        // $auth->removeChild($admin, $user);
        // $auth->removeChild($user, $canViewCollection);
        // $auth->removeChild($user, $canCreate);

        $auth->removeChild($admin, $moderator);
        $auth->removeChild($moderator, $user);
        $auth->removeChild($user, $guest);

        $auth->remove($admin);
        $auth->remove($moderator);
        $auth->remove($user);
        $auth->remove($guest);

        // $auth->remove($canCreate);
        // $auth->remove($canViewCollection);
        // $auth->remove($baseAccess);
        // $auth->remove($photoReport);
    }

}
