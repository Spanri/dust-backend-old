<?php

use yii\db\Migration;
use app\traits\schemaTypesTrait;

/**
 * Handles the creation of table `transaction`.
 */
class m200228_193022_create_transaction_table extends Migration
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
        $this->createTable('transaction', [
            'id' => $this->uuidPk(),
            'registered_user_id' => $this->uuid(),
            'unregistered_user_id' => $this->uuid(),

            'type' => $this->tinyInteger()->notNull(),
            'status' => $this->tinyInteger()->notNull(),
            'currency_num' => $this->integer()->defaultValue(0),

            'created_at' => $this->bigInteger(),
        ]);

        $this->addForeignKey('fk-transaction-registered_user_id-user-id', 'transaction', 'registered_user_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk-transaction-unregistered_user_id-unregistered-id', 'transaction', 'unregistered_user_id', 'unregistered', 'id', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-transaction-registered_user_id-user-id', 'transaction');
        $this->dropForeignKey('fk-transaction-unregistered_user_id-unregistered-id', 'transaction');
        $this->dropTable('transaction');
    }
}
