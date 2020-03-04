<?php

use yii\db\Migration;
use app\traits\schemaTypesTrait;

/**
 * Handles the creation of table `billing`.
 */
class m200228_182242_create_billing_table extends Migration
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
        $this->createTable('billing', [
            'id' => $this->uuidPk(),
            'user_id' => $this->uuid()->notNull(),

            'ruble_token_num' => $this->decimal(15,3)->notNull()->defaultValue(0),
            'dust_token_num' => $this->decimal(15,3)->notNull()->defaultValue(0)
        ]);

        $this->addForeignKey('fk-billing-user_id-user-id', 'billing', 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-billing-user_id-user-id', 'billing');
        $this->dropTable('billing');
    }
}
