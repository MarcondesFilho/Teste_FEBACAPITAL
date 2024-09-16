<?php

namespace src\Infrastructure\Repositories;

use src\Domain\Entities\User;
use src\Domain\Repositories\UserRepository;

class UserRepositoryImpl implements UserRepository
{
    public function findByUsername(string $username): ?User
    {
        // Lógica para buscar o usuário no banco de dados
        $userData = UserModel::findOne(['username' => $username]);

        if ($userData) {
            return new User($userData->username, $userData->password, $userData->name);
        }

        return null;
    }

    public function save(User $user): bool
    {
        // Lógica para salvar o usuário no banco de dados
        $userModel = new UserModel();
        $userModel->username = $user->getUsername();
        $userModel->password = $user->getPassword();
        $userModel->name = $user->getName();

        return $userModel->save();
    }
}
