<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'olmXn7opFVmTRplfb9yl4TRBedoDFiq9',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
        
    'urlManager' => [
        'enablePrettyUrl' => true,  
        'showScriptName' => false,  
        'enableStrictParsing' => true, 
        'rules' => [
            // Rota para Clientes
            'GET api/clientes' => 'cliente/index',          // Listar todos os clientes
            'GET api/clientes/<id:\d+>' => 'cliente/view',  // Visualizar um cliente específico
            'POST api/clientes' => 'cliente/create',        // Criar um novo cliente
            'PUT api/clientes/<id:\d+>' => 'cliente/update',// Atualizar um cliente
            'DELETE api/clientes/<id:\d+>' => 'cliente/delete', // Excluir um cliente

            // Rota para Livros
            'GET api/livros' => 'livro/index',              // Listar todos os livros
            'GET api/livros/<id:\d+>' => 'livro/view',      // Visualizar um livro específico
            'POST api/livros' => 'livro/create',            // Criar um novo livro
            'PUT api/livros/<id:\d+>' => 'livro/update',    // Atualizar um livro
            'DELETE api/livros/<id:\d+>' => 'livro/delete', // Excluir um livro

            // Rota para Autenticação
            'POST api/login' => 'auth/login',               // Rota para fazer login e gerar JWT
        ],
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
