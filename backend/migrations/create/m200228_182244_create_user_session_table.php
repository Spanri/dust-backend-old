<?php

use yii\db\Migration;
use app\traits\schemaTypesTrait;

/**
 * Handles the creation of table `user_session`.
 */
class m200228_182244_create_user_session_table extends Migration
{
    /**
     * @var schemaTypesTrait Специфические типы данных
     */
    use schemaTypesTrait;

    /**
     * @return bool|void
     * @throws \yii\base\NotSupportedException
     */
    public function safeUp()
    {
        $this->createTable('user_session', [
            'id' => $this->uuidPk(),
            'user_id' => $this->uuid()->notNull(),

            'token' => $this->string(32)->notNull(),
            'lifetime' => $this->integer()->notNull(),
            'last_activity' => $this->bigInteger()->notNull()
        ]);

        $this->createIndex('idx-user_session-user_id', 'user_session', 'token');
        $this->addForeignKey('fk-user_session-user_id-user-id', 'user_session', 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-user_session-user_id', 'user_session');
        $this->dropForeignKey('fk-user_session-user_id-user-id', 'user_session');
        $this->dropTable('user_session');
    }
}
