<?php

namespace src\Application\Services;

use src\Domain\Repositories\UsuarioRepository;
use src\Infrastructure\JWT\JWTService;
use Yii;

class AuthService
{
    private UsuarioRepository $userRepository;
    private JWTService $jwtService;

    public function __construct(UsuarioRepository $userRepository, JWTService $jwtService)
    {
        $this->userRepository = $userRepository;
        $this->jwtService = $jwtService;
    }

    public function authenticate(string $username, string $password): ?string
    {
        $user = $this->userRepository->findByUsername($username);

        if ($user && Yii::$app->getSecurity()->validatePassword($password, $user->getPassword())) {
            return $this->jwtService->generateToken($user->getUsername());
        }

        return null;
    }
}