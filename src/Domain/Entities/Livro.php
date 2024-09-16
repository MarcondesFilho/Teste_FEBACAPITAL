<?php

namespace src\Domain\Entities;

class Livro
{
    private string $isbn;
    private string $titulo;
    private string $autor;
    private float $preco;
    private int $estoque;
    private ?string $imagem;

    public function __construct(string $isbn, string $titulo, string $autor, float $preco, int $estoque, ?string $imagem = null)
    {
        $this->isbn = $isbn;
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->preco = $preco;
        $this->estoque = $estoque;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getAutor(): string
    {
        return $this->autor;
    }

    public function getPreco(): float
    {
        return $this->preco;
    }

    public function getEstoque(): int
    {
        return $this->estoque;
    }

    public function getImagem(): string
    {
        return $this->imagem;
    }
}
