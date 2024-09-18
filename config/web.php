<?php

use sizeg\jwt\Jwt;
use yii\rest\UrlRule;
use yii\web\JsonParser;
use yii\filters\auth\HttpBearerAuth;

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
                ['class' => UrlRule::class, 'controller' => ['auth', 'usuario', 'cliente', 'livro'], 'pluralize' => false],
                // Rotas RESTful para o controlador de autenticação
                'POST api/login' => 'auth/login',
                'POST api/register' => 'auth/register',
                
                // Rotas RESTful para o controlador de clientes
                'GET api/clientes' => 'cliente/index',
                'POST api/clientes' => 'cliente/create',
                'GET api/clientes/<id:\d+>' => 'cliente/view',
                'PUT api/clientes/<id:\d+>' => 'cliente/update',
                'DELETE api/clientes/<id:\d+>' => 'cliente/delete',
                
                // Rotas RESTful para o controlador de livros
                'GET api/livros' => 'livro/index',
                'POST api/livros' => 'livro/create',
                'GET api/livros/<id:\d+>' => 'livro/view',
                'PUT api/livros/<id:\d+>' => 'livro/update',
                'DELETE api/livros/<id:\d+>' => 'livro/delete',
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
                HttpBearerAuth::class, // Autenticação via Bearer Token
            ],
        ],
        'jwt' => [
            'class' => 'src\Infrastructure\JWT\JWTService',
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
    'modules' => [
        'api' => [
            'class' => 'yii\rest\Module',
        ],
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
