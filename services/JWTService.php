<?php

namespace app\services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Yii;

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
            return false;
        }
    }
}
