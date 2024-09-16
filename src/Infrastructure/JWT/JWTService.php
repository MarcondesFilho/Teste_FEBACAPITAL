<?php

namespace src\Infrastructure\JWT;

use Firebase\JWT\JWT;

class JWTService
{
    private $key;
    private $issuer;
    private $audience;

    public function __construct()
    {
        $this->key = \Yii::$app->params['jwtSecretKey'];
        $this->issuer = 'https://meusistema.com'; // Domínio configurado para o emissor
        $this->audience = 'https://meusistema.com'; // Domínio para quem o token é destinado (audience)
    }

    public function generateToken($userId)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // O token expira em 1 hora

        $payload = [
            'iss' => $this->issuer,        // Emissor do token (domínio)
            'aud' => $this->audience,      // Destinatário do token (domínio)
            'iat' => $issuedAt,            // Tempo de emissão
            'exp' => $expirationTime,      // Tempo de expiração
            'sub' => $userId,              // Identificação do usuário
        ];

        return JWT::encode($payload, $this->key);
    }

    public function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, $this->key, ['HS256']);
            return (array) $decoded;
        } catch (\Exception $e) {
            // Lidar com exceção de token inválido ou expirado
            return false;
        }
    }
}
