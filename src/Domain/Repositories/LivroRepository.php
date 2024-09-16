<?php

namespace src\Domain\Repositories;

use src\Domain\Entities\Livro;

interface LivroRepository
{
    public function save(Livro $livro): bool;
    public function findById($id);
    public function findByIsbn(string $isbn): ?Livro;
    public function findAll(int $limit, int $offset, string $orderBy, ?string $filterBy = null): array;
}
