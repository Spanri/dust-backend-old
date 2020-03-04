<?php

use yii\db\Migration;
use app\traits\schemaTypesTrait;

/**
 * Handles the creation of table `settings`.
 * Почему во множественном числе?
 * Я увидела это тут 
 * https://dba.stackexchange.com/questions/50567/configuration-table/50587 
 */
class m200229_183235_create_settings_table extends Migration
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
        $this->createTable('settings', [
            'name' => $this->string(),
            'value' => $this->string(),

            'created_at' => $this->bigInteger(),
            'updated_at' => $this->bigInteger(),
        ]);

        $this->addPrimaryKey('news_pk', 'settings', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('settings');
    }
}
