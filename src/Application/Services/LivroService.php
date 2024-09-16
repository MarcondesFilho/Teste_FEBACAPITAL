<?php

namespace src\Application\Services;

use src\Domain\Entities\Livro;
use src\Domain\Repositories\LivroRepository;
use src\Infrastructure\Services\BrasilApiService;
use src\Infrastructure\Services\S3UploadService;
use yii\web\UploadedFile;

class LivroService
{
    private LivroRepository $livroRepository;
    private S3UploadService $s3UploadService;

    public function __construct(LivroRepository $livroRepository)
    {
        $this->livroRepository = $livroRepository;
        $this->s3UploadService = $s3UploadService;
    }

    public function cadastrarLivro(array $data, ?UploadedFile $imagem = null): bool
    {
        // Se o ISBN for fornecido, buscar os dados através da BrasilAPI
        if (isset($data['isbn'])) {
            $isbnData = BrasilApiService::buscarDadosIsbn($data['isbn']);

            if ($isbnData) {
                $data['titulo'] = $isbnData['title'] ?? $data['titulo'];
                $data['autor'] = $isbnData['author'] ?? $data['autor'];
            }
        }

        $livro = new Livro($data['isbn'], $data['titulo'], $data['autor'], $data['preco'], $data['estoque']);

        if ($imagem) {
            $caminhoImagem = $this->s3UploadService->upload($imagem);
            $livro->setImagem($caminhoImagem);
        }

        return $this->livroRepository->save($livro);
    }

    public function listarLivros(int $limit, int $offset, string $orderBy, ?string $filterBy = null): array
    {
        return $this->livroRepository->findAll($limit, $offset, $orderBy, $filterBy);
    }
}
