<?php

use sizeg\jwt\Jwt;
use yii\rest\UrlRule;
use yii\web\JsonParser;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@src'   => '@app/src',
    ],
    'container' => [
        'definitions' => [
            'src\Domain\Repositories\UsuarioRepository' => 'src\Infrastructure\Repositories\UsuarioRepositoryImpl',
        ],
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                ['class' => UrlRule::class, 'controller' => ['auth', 'cliente', 'livro']],
                // Rotas para clientes
                'POST /api/clientes' => 'cliente/create',
                'GET /api/clientes' => 'cliente/index',
                'GET /api/clientes/<id>' => 'cliente/view',
                'PUT /api/clientes/<id>' => 'cliente/update',
                'DELETE /api/clientes/<id>' => 'cliente/delete',

                // Rotas para livros
                'POST /api/livros' => 'livro/create',
                'GET /api/livros' => 'livro/index',
                'GET /api/livros/<id>' => 'livro/view',
                'PUT /api/livros/<id>' => 'livro/update',
                'DELETE /api/livros/<id>' => 'livro/delete',

                // Autenticação
                'POST /api/login' => 'auth/login',
                'POST /api/register' => 'usuario/create',
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'olmXn7opFVmTRplfb9yl4TRBedoDFiq9',
            'parsers' => [
                'application/json' => JsonParser::class,
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'src\Domain\Entities\Usuario',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'authMethods' => [
                'yii\filters\auth\HttpBearerAuth',  // Certifique-se de que não há parâmetros incorretos
            ],
        ],
        'jwt' => [
            'class' => Jwt::class,
            'key'   => 'FEBACAPITAL', // Chave secreta para geração de tokens
        ],
        'errorHandler' => [
            'class' => 'yii\web\ErrorHandler',
            'errorAction' => null,
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
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
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
