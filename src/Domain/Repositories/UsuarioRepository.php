<?php

namespace src\Domain\Repositories;

use src\Domain\Entities\Usuario;

interface UsuarioRepository
{
    public function findById($id): ?Usuario;
    public function findByUsername(string $username): ?Usuario;
    public function save(Usuario $usuario): bool;
    
}
