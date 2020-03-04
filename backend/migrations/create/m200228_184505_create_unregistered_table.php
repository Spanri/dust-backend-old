<?php

use yii\db\Migration;

/**
 * Handles the creation of table `unregistered`.
 */
class m200228_184505_create_unregistered_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('unregistered', [
            'id' => $this->primaryKey(),
            'account_type_id' => $this->integer()->notNull(),

            'dust_coin_num' => $this->integer()->defaultValue(0),
        ]);

        $this->addForeignKey('fk-unregistered-account_type_id-account_type-id', 'unregistered', 'account_type_id', 'account_type', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-unregistered-account_type_id-account_type-id', 'account_type');
        $this->dropTable('unregistered');
    }
}
