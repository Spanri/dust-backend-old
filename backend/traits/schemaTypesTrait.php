<?php

namespace app\traits;

use yii\db\ColumnSchemaBuilder;

/**
 * Trait SchemaTypesTrait
 * Объявления специфических типов данных
 */
trait SchemaTypesTrait
{
    abstract protected function getDb();

    /**
     * @return ColumnSchemaBuilder
     */
    public function uuid()
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('uuid');
    }

    /**
     * @return ColumnSchemaBuilder
     */
    public function uuidPk()
    {
        $this->execute('create extension if not exists "uuid-ossp"');
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('uuid PRIMARY KEY DEFAULT uuid_generate_v4()');
    }
}