<?php

use yii\db\Migration;
use app\traits\schemaTypesTrait;

/**
 * Handles the creation of table `referral_program`.
 */
class m200228_183728_create_referral_program_table extends Migration
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
        $this->createTable('referral_program', [
            'id' => $this->uuidPk(),
            'user_id' => $this->uuid()->notNull(),
            'upline_user_id' => $this->uuid(),

            'level' => $this->tinyInteger()->notNull(),
            'upline_user_level' => $this->tinyInteger(),

            'updated_at' => $this->bigInteger(),
        ]);

        $this->addForeignKey('fk-referral_program-user_id-user-id', 'referral_program', 'user_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk-referral_program-upline_user_id-user-id', 'referral_program', 'upline_user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-referral_program-user_id-user-id', 'referral_program');
        $this->dropForeignKey('fk-referral_program-upline_user_id-user-id', 'referral_program');
        $this->dropTable('referral_program');
    }
}
