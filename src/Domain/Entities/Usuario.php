<?php

namespace src\Domain\Entities;

class Usuario
{
    private $login;
    private $senha;
    private $nome;

    public function __construct($login, $senha, $nome)
    {
        $this->login = $login;
        $this->senha = $senha;
        $this->nome = $nome;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function getNome()
    {
        return $this->nome;
    }
}

