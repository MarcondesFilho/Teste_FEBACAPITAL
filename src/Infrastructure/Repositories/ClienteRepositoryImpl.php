<?php

namespace src\Infrastructure\Repositories;

use src\Infrastructure\ActiveRecord\ClienteAR;
use src\Domain\Entities\Cliente as ClienteEntity;
use src\Domain\Repositories\ClienteRepository;

class ClienteRepositoryImpl implements ClienteRepository
{
    public function save(ClienteEntity $cliente): bool
    {
        $clienteAR = new ClienteAR();
        $clienteAR->nome = $cliente->getNome();
        $clienteAR->cpf = $cliente->getCpf();
        $clienteAR->endereco = $cliente->getEndereco();
        $clienteAR->sexo = $cliente->getSexo();
        $clienteAR->imagem = $cliente->getImagem();
        return $clienteAR->save();
    }

    public function findByCpf(string $cpf): ?ClienteEntity
    {
        $clienteAR = ClienteAR::findOne(['cpf' => $cpf]);
        if ($clienteAR) {
            return new ClienteEntity($clienteAR->nome, $clienteAR->cpf, $clienteAR->endereco, $clienteAR->sexo, $clienteAR->imagem);
        }
        return null;
    }

    public function findAll(int $limit, int $offset, string $orderBy, ?string $filterBy = null): array
    {
        $query = ClienteAR::find()
            ->limit($limit)
            ->offset($offset)
            ->orderBy($orderBy);

        if ($filterBy) {
            $query->andWhere(['like', 'nome', $filterBy])
                  ->orWhere(['like', 'cpf', $filterBy]);
        }

        return $query->all();
    }
}
