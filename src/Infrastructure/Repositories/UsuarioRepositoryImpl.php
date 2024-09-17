<?php

namespace src\Infrastructure\Repositories;

use src\Infrastructure\ActiveRecord\UsuarioAR;
use src\Domain\Entities\Usuario as UsuarioEntity;
use src\Domain\Repositories\UsuarioRepository;

class UsuarioRepositoryImpl implements UsuarioRepository
{
    public function save(UsuarioEntity $usuario): bool
    {
        $usuarioAR = new UsuarioAR();
        $usuarioAR->login = $usuario->getUsername();
        $usuarioAR->senha = $usuario->getPassword();
        $usuarioAR->nome = $usuario->getName(); 
        
        return $usuarioAR->save();
    }

    public function findById($id): ?UsuarioEntity
    {
        $usuarioAR = UsuarioAR::findOne($id);
        if (!$usuarioAR) {
            return null;
        }
        
        return new UsuarioEntity($usuarioAR->login, $usuarioAR->senha, $usuarioAR->nome);
    }

    public function findByLogin($login): ?UsuarioEntity
    {
        return UsuarioAR::findOne(['login' => $login]);
    }
}
