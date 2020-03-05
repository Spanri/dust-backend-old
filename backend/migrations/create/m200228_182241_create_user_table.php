<?php

use yii\db\Migration;
use app\traits\schemaTypesTrait;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m200228_182241_create_user_table extends Migration
{
    /**
     * @var schemaTypesTrait Специфические типы данных
     */
    use schemaTypesTrait;

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->uuidPk(),

            'email' => $this->string(255)->notNull()->unique(),
            'username' => $this->string(255)->notNull()->unique(),
            'password_hash' => $this->string(255)->notNull(),
            'password_reset_token' => $this->string(255)->unique(),
            'email_confirm_token' => $this->string(255),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'avatar' => $this->string(255)->notNull(),

            'created_at' => $this->bigInteger(),
            'updated_at' => $this->bigInteger()
        ]);

        // rbac не совсем правильно генерирует таблицы, меняем тип user_id
        $this->dropColumn('auth_assignment', 'user_id');
        $this->addColumn('auth_assignment', 'user_id', 'uuid');
        $this->createIndex('idx-auth_assignment-user_id', 'auth_assignment', 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
