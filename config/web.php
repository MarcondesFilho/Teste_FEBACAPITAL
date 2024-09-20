<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

use yii\rest\UrlRule;
use yii\web\JsonParser;
use yii\symfonymailer\Mailer;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\Visibility;

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
            'enableStrictParsing' => true,
            'rules' => [
                ['class' => UrlRule::class, 'controller' => ['auth', 'cliente', 'livro']],
                'POST api/login' => 'auth/login',
                'POST api/register' => 'auth/register',
                'GET api/clientes' => 'cliente/index',
                'POST api/clientes' => 'cliente/create',
                'GET api/clientes/<id:\d+>' => 'cliente/view',
                'PUT api/clientes/<id:\d+>' => 'cliente/update',
                'DELETE api/clientes/<id:\d+>' => 'cliente/delete',
                'GET api/livros' => 'livro/index',
                'POST api/livros' => 'livro/create',
                'GET api/livros/<id:\d+>' => 'livro/view',
                'PUT api/livros/<id:\d+>' => 'livro/update',
                'DELETE api/livros/<id:\d+>' => 'livro/delete',
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'iN29Z7joZYFbzVvVxLYYvA72QrclcTLr',
            'parsers' => [
                'application/json' => JsonParser::class,
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
        'jwt' => [
            'class' => 'app\services\AuthService',
            'key' => $params['jwt']['key'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => Mailer::class,
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
        's3' => function () {
            $client = new S3Client([
                'credentials' => [
                    'key' => 'your-aws-access-key',
                    'secret' => 'your-aws-secret-key',
                ],
                'region' => 'us-east-1',
                'version' => 'latest',
            ]);

            $adapter = new AwsS3V3Adapter(
                $client,
                'your-bucket-name',
                'path/prefix',
                new \League\Flysystem\AwsS3V3\PortableVisibilityConverter(Visibility::PUBLIC)
            );

            return new Filesystem($adapter);
        },
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
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
