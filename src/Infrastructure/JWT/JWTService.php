<?php

namespace src\Infrastructure\JWT;

use yii\filters\auth\HttpBearerAuth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Yii;

class JWTService extends HttpBearerAuth
{
    private $key;
    private $issuer;
    private $audience;

    public function __construct()
    {
        $this->key = Yii::$app->params['jwtSecretKey'];
        $this->issuer = 'febacapital'; // Domínio configurado para o emissor
        $this->audience = 'febacapital_app'; // Domínio para quem o token é destinado (audience)
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

        return JWT::encode($payload, $this->key, 'HS256');
    }

    public function validateToken($token)
    {
        try {
//            $decoded = JWT::decode($token, $this->key, ['HS256']);
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            
            $decodedArray = json_decode(json_encode($decoded), true);

            return $decodedArray;
            
        } catch (\Exception $e) {
            // Lidar com exceção de token inválido ou expirado
            return false;
        }
    }

    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            $token = $matches[1];
            try {
                $decoded = $this->validateToken($token);

                if (!$decoded) {
                    $response->setStatusCode(401, 'Token inválido');
                    return null;
                }

                // Se o token for válido, busca o usuário pelo ID (sub)
                $identity = $user->loginByAccessToken($decoded['sub'], get_class($this));
                if ($identity) {
                    return $identity;
                }
            } catch (\Exception $e) {
                $response->setStatusCode(401, 'Token inválido');
                return null;
            }
        }

        return null;
    }
}
