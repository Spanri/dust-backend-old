<?php

use yii\db\Migration;
use app\traits\schemaTypesTrait;

/**
 * Handles the creation of table `{{%user_account_type}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%account_type}}`
 */
class m200229_085123_create_junction_table_for_user_and_account_type_tables extends Migration
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
        $this->createTable('{{%user_account_type}}', [
            'user_id' => $this->uuid(),
            'type' => $this->integer()->notNull(),
            'account_id' => $this->string(32)->notNull(),
            'PRIMARY KEY(user_id, type, account_id)',
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_account_type-user_id}}',
            '{{%user_account_type}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_account_type-user_id}}',
            '{{%user_account_type}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `type` and 'account_id'
        $this->createIndex(
            '{{%idx-user_account_type-type-account_id}}',
            '{{%user_account_type}}',
            ['type', 'account_id']
        );

        // add foreign key for table `{{%account_type}}`
        $this->addForeignKey(
            'fk-user_account_type-account_type_id',
            'user_account_type',
            ['type', 'account_id'],
            'account_type',
            ['type', 'account_id'],
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_account_type-user_id}}',
            '{{%user_account_type}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_account_type-user_id}}',
            '{{%user_account_type}}'
        );

        // drops foreign key for table `{{%account_type}}`
        $this->dropForeignKey(
            '{{%fk-user_account_type-account_type_id}}',
            '{{%user_account_type}}'
        );

        // drops index for column 'type' and `account_type`
        $this->dropIndex(
            '{{%idx-user_account_type-type-account_id}}',
            '{{%user_account_type}}'
        );

        $this->dropTable('{{%user_account_type}}');
    }
}
