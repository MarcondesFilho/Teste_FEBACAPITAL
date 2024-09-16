<?php

namespace src\Domain\ValueObjects;

use src\Infrastructure\Services\BrasilApiService;

class Endereco
{
    private string $cep;
    private string $logradouro;
    private string $numero;
    private string $cidade;
    private string $estado;
    private ?string $complemento;

    public function __construct(string $cep, string $logradouro, string $numero, string $cidade, string $estado, ?string $complemento = null)
    {
        if (!BrasilApiService::validateCep($cep)) {
            throw new \InvalidArgumentException("CEP inválido");
        }
        $this->cep = $cep;
        $this->logradouro = $logradouro;
        $this->numero = $numero;
        $this->cidade = $cidade;
        $this->estado = $estado;
        $this->complemento = $complemento;
    }

    public function getCep(): string
    {
        return $this->cep;
    }

    // Outros getters para os campos de endereço...
}
