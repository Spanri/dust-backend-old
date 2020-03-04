<?php

use yii\db\Migration;

/**
 * Handles the creation of table `billing`.
 */
class m200228_182242_create_billing_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('
            CREATE TABLE {{%billing}} (
                id uuid PRIMARY KEY,

                ruble_token_num numeric(15,3) DEFAULT 0,
                dust_token_num numeric(15,3) DEFAULT 0
            );
        ');    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('billing');
    }
}
