<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=dust',
    'username' => 'postgres',
    'password' => 'nysha2161',
    'charset' => 'utf8',

    // 'autoConnect' => false, // не устанавливать соединение при старте приложения - для оптимизации

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
