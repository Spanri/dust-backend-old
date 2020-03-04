<?php

use yii\db\Migration;

/**
 * Handles the creation of table `settings`.
 * Почему во множественном числе?
 * Я увидела это тут 
 * https://dba.stackexchange.com/questions/50567/configuration-table/50587 
 */
class m200229_183235_create_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('settings', [
            'name' => $this->primaryKey(),
            'value' => $this->string()->notNull(),

            'created_at' => $this->bigInteger(),
            'updated_at' => $this->bigInteger(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('settings');
    }
}
