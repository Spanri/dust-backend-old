<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m200228_182243_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('
            CREATE TABLE {{%user}} (
                id uuid PRIMARY KEY,
                billing_id integer NOT NULL,
                user_session_id integer NOT NULL,
                referral_program_id integer NOT NULL,

                email character varying(255) NOT NULL UNIQUE,
                username character varying(255) NOT NULL UNIQUE,
                password_hash character varying(255) NOT NULL,
                password_reset_token character varying(255) NOT NULL UNIQUE,
                email_confirm_token character varying(255) NOT NULL,

                avatar character varying(500) NOT NULL,

                created_at integer NOT NULL,
                updated_at bigint NOT NULL
            );
        ');    

        // \thamtech\uuid\helpers\UuidHelper::uuid()
        
        $this->addForeignKey('fk-user-billing_id-billing-id', 'user', 'billing_id', 'billing', 'id', 'CASCADE');
        $this->addForeignKey('fk-user_session-user_id-user-id', 'user_session', 'user_id', 'user', 'id', 'CASCADE');
        // $this->addForeignKey('fk-auth_assignment-user_id-user-id', 'auth_assignment', 'user_id', 'user', 'id', 'CASCADE');
        // $this->addForeignKey('fk-billing-user_id-user_session-user_session_id', 'user_session', 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-billing-user_id-user-id', 'user');
        $this->dropForeignKey('fk-user_session-user_id-user-id', 'user_session');
        // $this->dropForeignKey('fk-auth_assignment-user_id-user-id', 'user');
        // $this->dropForeignKey('fk-billing-user_id-user_sessions-user_session_id', 'user');
        $this->dropTable('user');
    }
}
