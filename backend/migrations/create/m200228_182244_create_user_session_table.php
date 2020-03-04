<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_session`.
 */
class m200228_182244_create_user_session_table extends Migration
{
    /**
     * @return bool|void
     * @throws \yii\base\NotSupportedException
     */
    public function safeUp()
    {
        $this->execute('
            CREATE TABLE {{%user_session}} (
                id uuid PRIMARY KEY,

                token varying(255) NOT NULL,
                lifetime integer NOT NULL,
                last_activity bigint NOT NULL
            );
        ');   

        $this->createIndex('idx-user_session-user_id', 'user_session', 'token');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-user_session-user_id', 'user_session');
        $this->dropTable('user_session');
    }
}
