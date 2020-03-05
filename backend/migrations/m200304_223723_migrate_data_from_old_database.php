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
        $oldDb = \Yii::$app->getDb('oldDb');
        $transportUsers = $oldDb->createCommand('SELECT * FROM {{%user}}')->queryAll();
        print_r($transportUsers);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200304_223723_migrate_data_from_old_database cannot be reverted.\n";

        return false;
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
