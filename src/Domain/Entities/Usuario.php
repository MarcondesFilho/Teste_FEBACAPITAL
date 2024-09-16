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

    public function getUsername()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->senha;
    }

    public function getName()
    {
        return $this->nome;
    }
}

