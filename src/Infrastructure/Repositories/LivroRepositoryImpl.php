<?php

namespace src\Infrastructure\Repositories;

use src\Infrastructure\ActiveRecord\LivroAR;
use src\Domain\Entities\Livro as LivroEntity;
use src\Domain\Repositories\LivroRepository;

class LivroRepositoryImpl implements LivroRepository
{
    public function save(LivroEntity $livro): bool
    {
        $livroAR = new LivroAR();
        $livroAR->isbn = $livro->getIsbn();
        $livroAR->titulo = $livro->getTitulo();
        $livroAR->autor = $livro->getAutor();
        $livroAR->preco = $livro->getPreco();
        $livroAR->estoque = $livro->getEstoque();
        $livroAR->imagem = $livro->getImagem();
        return $livroAR->save();
    }

    public function findById($id)
    {
        return LivroAR::findOne($id);
    }

    public function findByIsbn(string $isbn): ?LivroEntity
    {
        $livroAR = LivroAR::findOne(['isbn' => $isbn]);
        if ($livroAR) {
            return new LivroEntity($livroAR->isbn, $livroAR->titulo, $livroAR->autor, $livroAR->preco, $livroAR->estoque, $livroAR->imagem);
        }
        return null;
    }

    public function findAll(int $limit, int $offset, string $orderBy, ?string $filterBy = null): array
    {
        $query = LivroAR::find()
            ->limit($limit)
            ->offset($offset)
            ->orderBy($orderBy);

        if ($filterBy) {
            $query->andWhere(['like', 'titulo', $filterBy])
                  ->orWhere(['like', 'autor', $filterBy])
                  ->orWhere(['isbn' => $filterBy]);
        }

        return $query->all();
    }
}
