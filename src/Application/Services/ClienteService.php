<?php

namespace src\Application\Services;

use src\Domain\Entities\Cliente;
use src\Domain\ValueObjects\CPF;
use src\Domain\ValueObjects\Endereco;
use src\Domain\Repositories\ClienteRepository;
use src\Infrastructure\Services\S3UploadService;
use yii\web\UploadedFile;

class ClienteService
{
    private ClienteRepository $clienteRepository;
    private S3UploadService $s3UploadService;

    public function __construct(ClienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
        $this->s3UploadService = $s3UploadService;
    }

    public function cadastrarCliente(array $data, ?UploadedFile $imagem = null): bool
    {
        $cpf = new CPF($data['cpf']);
        $endereco = new Endereco(
            $data['cep'],
            $data['logradouro'],
            $data['numero'],
            $data['cidade'],
            $data['estado'],
            $data['complemento'] ?? null
        );

        $cliente = new Cliente($data['nome'], $cpf, $endereco, $data['sexo']);

        if ($imagem) {
            $caminhoImagem = $this->s3UploadService->upload($imagem);
            $cliente->setImagem($caminhoImagem);
        }

        return $this->clienteRepository->save($cliente);
    }

    public function listarClientes(int $limit, int $offset, string $orderBy, ?string $filterBy = null): array
    {
        return $this->clienteRepository->findAll($limit, $offset, $orderBy, $filterBy);
    }
}
