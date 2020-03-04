<?php

use yii\db\Migration;

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
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_account_type}}', [
            'user_id' => $this->integer(),
            'account_type_id' => $this->integer(),
            'PRIMARY KEY(user_id, account_type_id)',
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

        // creates index for column `account_type_id`
        $this->createIndex(
            '{{%idx-user_account_type-account_type_id}}',
            '{{%user_account_type}}',
            'account_type_id'
        );

        // add foreign key for table `{{%account_type}}`
        $this->addForeignKey(
            '{{%fk-user_account_type-account_type_id}}',
            '{{%user_account_type}}',
            'account_type_id',
            '{{%account_type}}',
            'id',
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

        // drops index for column `account_type_id`
        $this->dropIndex(
            '{{%idx-user_account_type-account_type_id}}',
            '{{%user_account_type}}'
        );

        $this->dropTable('{{%user_account_type}}');
    }
}
