<?php

namespace src\Domain\Repositories;

use src\Domain\Entities\Usuario;

interface UsuarioRepository
{
    public function findByLogin($login): ?Usuario;
    public function findById($id): ?Usuario;
    public function save(Usuario $usuario): bool;
    
}
