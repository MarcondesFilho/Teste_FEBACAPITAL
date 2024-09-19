<?php

namespace app\services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Yii;
use yii\web\UnauthorizedHttpException;

class JWTService
{
    private $key;

    public function __construct()
    {
        $this->key = Yii::$app->params['jwt']['key'];
    }

    public function generateToken($usuario)
    {
        $payload = [
            'iss' => 'febacapital',
            'aud' => 'febacapital_app',
            'iat' => time(),
            'exp' => time() + 3600,
            'uid' => $usuario->id
        ];

        return JWT::encode($payload, $this->key, 'HS256');
    }

    public function validateToken($token)
    {
        try {
            return JWT::decode($token, new Key($this->key, 'HS256'));
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException('Token inválido ou expirado');
        }
    }

    public function getTokenFromHeader()
    {
        $authHeader = Yii::$app->request->getHeaders()->get('Authorization');
        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            return $matches[1];
        }
        throw new UnauthorizedHttpException('Token não encontrado no cabeçalho');
    }
}
