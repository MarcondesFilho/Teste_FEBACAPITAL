<?php

namespace src\Infrastructure\Repositories;

use src\Infrastructure\ActiveRecord\UserAR;
use src\Domain\Entities\User as UserEntity;
use src\Domain\Repositories\UserRepository;

class UserRepositoryImpl implements UserRepository
{
    public function findByUsername(string $username): ?UserEntity
    {
        // Lógica para buscar o usuário no banco de dados
        $userData = UserAR::findOne(['username' => $username]);

        if ($userData) {
            return new UserEntity($userData->username, $userData->password, $userData->name);
        }

        return null;
    }

    public function save(UserEntity $user): bool
    {
        // Lógica para salvar o usuário no banco de dados
        $userModel = new UserAR();
        $userModel->username = $user->getUsername();
        $userModel->password = $user->getPassword();
        $userModel->name = $user->getName();

        return $userModel->save();
    }
}
