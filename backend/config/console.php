<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$oldDb = require __DIR__ . '/oldDb.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'oldDb' => $oldDb,

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            // 'itemTable' => 'auth_role',
            // 'itemChildTable' => 'auth_role_child',
            // 'assignmentTable' => 'auth_permission',
        ],
    ],
    'params' => $params,

    'controllerMap' => [
        'migrate' => [
            'class' => \yii\console\controllers\MigrateController::class,
            'migrationPath' => [
                '@yii/rbac/migrations',
                '@app/migrations',
                '@app/migrations/create',
                '@app/migrations/update',
            ],
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
