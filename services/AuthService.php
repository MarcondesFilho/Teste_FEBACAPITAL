<?php

namespace app\services;

use app\models\User;
use app\services\JWTService;
use Yii;

class AuthService
{
    private $jwtService;

    public function __construct(JWTService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function authenticate($login, $senha)
    {
        $usuario = User::findOne(['login' => $login]);
        if (!$usuario || !$this->validatePassword($senha, $usuario->senha)) {
            throw new \Exception('Credenciais inválidas');
        }
        return $this->jwtService->generateToken($usuario);
    }

    public function validatePassword($senha, $hash)
    {
        return Yii::$app->getSecurity()->validatePassword($senha, $hash);
    }

    public function register($login, $senha, $nome)
    {
        // Lógica de criação de usuário
        $usuario = new User();
        $usuario->login = $login;
        $usuario->senha = Yii::$app->getSecurity()->generatePasswordHash($senha);
        $usuario->nome = $nome;

        if ($usuario->save()) {
            return $usuario;
        }
        throw new \Exception('Falha ao criar o usuário');
    }
}
