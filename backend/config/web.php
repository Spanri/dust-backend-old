<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$oldDb = require __DIR__ . '/oldDb.php';

$config = [
    'id' => 'app-api',
    'language' => 'ru',
    'sourceLanguage' => 'ru',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'bot',
        'core',
        'log',
        [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ]
        ]
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'core' => [
            'class' => 'app\modules\core\Module',
        ],
        'bot' => [
            'class' => 'app\modules\bot\Module',
        ],
    ],
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
            'cookieValidationKey' => '123',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'oldDb' => $oldDb,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
//                'class' => \yii\rest\UrlRule::class,
                '' => 'core/default/index',

//                'pattern' => '<controller:[\w-]+>/<action:[\w-]+>',
//                'route' => '<controller>/<action>',
//                'tokens' => ['{type}' => '<type:\\w+>']
//                'defaults' => ['controller' => 'core/default', 'action' => 'index']
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module'
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module'
    ];
}

return $config;
