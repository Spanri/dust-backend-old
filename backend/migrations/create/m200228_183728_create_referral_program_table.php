<?php

use yii\db\Migration;

/**
 * Handles the creation of table `referral_program`.
 */
class m200228_183728_create_referral_program_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('referral_program', [
            'id' => $this->primaryKey(),
            'upline_user_id' => $this->integer()->notNull(),

            'level' => $this->tinyInteger()->notNull(),
            'upline_user_level' => $this->tinyInteger()->notNull(),

            'updated_at' => $this->bigInteger(),
        ]);

        $this->execute('
            CREATE TABLE {{%user_session}} (
                id uuid PRIMARY KEY,
                upline_user_id integer,

                level smallint NOT NULL DEFAULT 0,
                lifetime integer NOT NULL,
                last_activity bigint NOT NULL
            );
        ');  

        $this->addForeignKey('fk-referral_program-upline_user_id-user-id', 'referral_program', 'upline_user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-referral_program-upline_user_id-users-id', 'user');
        $this->dropTable('referral_program');
    }
}
