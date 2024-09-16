<?php

namespace src\Infrastructure\JWT;

use Firebase\JWT\JWT;

class JWTService
{
    private string $key = 'your-secret-key';  // Definir uma chave secreta

    public function generateToken(string $username): string
    {
        $payload = [
            'iss' => 'your-domain.com',  // Emissor
            'aud' => 'your-domain.com',  // Destinatário
            'iat' => time(),             // Emitido em
            'exp' => time() + 3600,      // Expiração (1 hora)
            'sub' => $username           // Identificação do usuário
        ];

        return JWT::encode($payload, $this->key);
    }

    public function validateToken(string $token): bool
    {
        try {
            $decoded = JWT::decode($token, $this->key, ['HS256']);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
