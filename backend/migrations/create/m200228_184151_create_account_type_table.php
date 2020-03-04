<?php

use yii\db\Migration;

/**
 * Handles the creation of table `account_type`.
 */
class m200228_184151_create_account_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('account_type', [
            'id' => $this->primaryKey(),

            'type' => $this->string(32)->notNull(),
            'account_id' => $this->string(32)->notNull(),
            'username' => $this->string(32),
        ]);

        $this->createIndex('idx-account_type-type', 'account_type', 'type', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-account_type-type', 'account_type');
        $this->dropTable('account_type');
    }
}
