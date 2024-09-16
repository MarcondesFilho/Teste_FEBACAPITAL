<?php

namespace src\Domain\Repositories;

use src\Domain\Entities\User;

interface UserRepository
{
    public function findByUsername(string $username): ?User;
    public function save(User $user): bool;
}
