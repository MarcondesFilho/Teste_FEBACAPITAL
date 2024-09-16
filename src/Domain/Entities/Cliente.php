<?php

namespace src\Domain\Entities;

use src\Domain\ValueObjects\CPF;
use src\Domain\ValueObjects\Endereco;

class Cliente
{
    private string $nome;
    private CPF $cpf;
    private Endereco $endereco;
    private string $sexo;
    private ?string $imagem = null;

    public function __construct(string $nome, CPF $cpf, Endereco $endereco, string $sexo)
    {
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->endereco = $endereco;
        $this->sexo = $sexo;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getCpf(): CPF
    {
        return $this->cpf;
    }

    public function getEndereco(): Endereco
    {
        return $this->endereco;
    }

    public function getSexo(): string
    {
        return $this->sexo;
    }

    public function getImagem(): string
    {
        return $this->imagem;
    }

    public function setImagem(string $caminhoImagem): void
    {
        $this->imagem = $caminhoImagem;
    }
}
