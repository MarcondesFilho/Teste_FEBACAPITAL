<?php

namespace src\Domain\Repositories;

use src\Domain\Entities\Cliente;

interface ClienteRepository
{
    public function save(Cliente $cliente): bool;
    public function findByCpf(string $cpf): ?Cliente;
    public function findAll(int $limit, int $offset, string $orderBy, ?string $filterBy = null): array;
}
