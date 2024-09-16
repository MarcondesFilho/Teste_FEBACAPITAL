<?php

namespace src\Infrastructure\Repositories;

use src\Infrastructure\ActiveRecord\UsuarioAR;
use src\Domain\Entities\Usuario as UsuarioEntity;
use src\Domain\Repositories\UsuarioRepository;

class UsuarioRepositoryImpl implements UsuarioRepository
{
    public function save(UsuarioEntity $usuario): bool
    {
        // Lógica para salvar o usuário no banco de dados
        $usuarioAR = new UsuarioAR();
        $usuarioAR->username = $usuario->getUsername();
        $usuarioAR->password = $usuario->getPassword();
        $usuarioAR->name = $usuario->getName();

        return $usuarioAR ->save();
    }

    public function findByUsername(string $username): ?UsuarioEntity
    {
        // Lógica para buscar o usuário no banco de dados
        $userData = UsuarioAR::findOne(['username' => $username]);

        if ($userData) {
            return new UsuarioEntity($userData->username, $userData->password, $userData->name);
        }

        return null;
    }

    public function findById($id): ?UsuarioEntity
    {
        $usuarioAR = UsuarioAR::findOne($id);
        if (!$usuarioAR) {
            return null;
        }
        
        return new UsuarioEntity($usuarioAR->login, $usuarioAR->senha, $usuarioAR->nome);
    }
}
