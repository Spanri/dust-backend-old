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
            'user_id' => $this->uuid()->notNull(),

            'type' => $this->tinyInteger()->notNull(),
            'status' => $this->tinyInteger()->notNull(),
            'currency_num' => $this->integer()->defaultValue(0),

            'created_at' => $this->bigInteger(),
        ]);

        $this->addForeignKey('fk-transaction-user_id-user-id', 'transaction', 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-transaction-user_id-user-id', 'transaction');
        $this->dropTable('transaction');
    }
}
