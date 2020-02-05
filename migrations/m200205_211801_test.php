<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200205_211801_test
 */
class m200205_211801_test extends Migration
{

    public function up()
    {
        $this->createTable('news', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'content' => Schema::TYPE_TEXT,
        ]);
    }

    public function down()
    {
        $this->dropTable('news');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200205_211801_test cannot be reverted.\n";

        return false;
    }
    */
}
