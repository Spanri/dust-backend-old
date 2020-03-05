<?php

use yii\db\Migration;
use app\traits\schemaTypesTrait;

/**
 * Handles the creation of table `unregistered`.
 */
class m200228_184505_create_unregistered_table extends Migration
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
        $this->createTable('unregistered', [
            'id' => $this->uuidPk(),
            'type' => $this->integer()->notNull(),
            'account_id' => $this->string(32)->notNull(),

            'dust_coin_num' => $this->decimal(15,3)->notNull()->defaultValue(0),
        ]);

        $this->addForeignKey('fk-unregistered-account_type_id-account_type-id', 'unregistered', ['type', 'account_id'], 'account_type', ['type', 'account_id'], 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-unregistered-account_type_id-account_type-id', 'unregistered');
        $this->dropTable('unregistered');
    }
}
