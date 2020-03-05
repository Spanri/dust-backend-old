<?php

use yii\db\Migration;

/**
 * Class m200304_223723_migrate_data_from_old_database
 */
class m200304_223723_migrate_data_from_old_database extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $newDb = Yii::$app->db;
        $oldDb = Yii::$app->oldDb;

        $users = $oldDb->createCommand('SELECT * FROM {{%user}}')->queryAll();

//        // допустим, статус 10 - неподтвержденный email, но зареганный юзер, 20 - подтвержденный email
//        $newUsers = array_map(
//            function ($user) { return array($user['id'], $user['name'], $user['name'], '12345', $user['avatar'], 20, time(), time()); },
//            $users);
//        $newDb->createCommand()->batchInsert('user', ['id', 'email', 'username', 'password_hash', 'avatar', 'status', 'created_at', 'updated_at'], $newUsers)->execute();

        // допустим, тип 1 - это стим, 2 - твич
        $newAccounts = array_map(
            function ($user) {
                if(!is_null($user['twitch'])) { return array(2, $user['twitch'], $user['dc']); }
                else { return array(1, $user['steamId'], $user['dc']); } },
            $users);
        $newDb->createCommand()->batchInsert('account_type', ['type', 'account_id', 'username'], $newAccounts)->execute();

        $newUnregistered = array_map(
            function ($user) {
                if(!is_null($user['twitch'])) { return array(2, $user['twitch'], $user['dc']); }
                else { return array(1, $user['steamId'], $user['dc']); } },
            $users);
        $newDb->createCommand()->batchInsert('unregistered', ['type', 'account_id', 'dust_coin_num'], $newUnregistered)->execute();

//        $newUsersAccounts = array_map(
//            function ($user) { return array($user['id'], 1, $user['steamId']); },
//            $users);
//        $newDb->createCommand()->batchInsert('user_account_type', ['user_id', 'type', 'account_id'], $newUsersAccounts)->execute();
//
//        $newBillings = array_map(
//            function ($user) { return array($user['id'], $user['dc']); },
//            $users);
//        $newDb->createCommand()->batchInsert('billing', ['user_id', 'dust_token_num'], $newBillings)->execute();
//
//        $newReferralProgram = array_map(
//            function ($user) { return array($user['id'], time()); },
//            $users);
//        $newDb->createCommand()->batchInsert('referral_program', ['user_id', 'updated_at'], $newReferralProgram)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200304_223723_migrate_data_from_old_database cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200304_223723_migrate_data_from_old_database cannot be reverted.\n";

        return false;
    }
    */
}
