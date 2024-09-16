<?php

namespace src\Domain\Entities;

class User
{
    private string $username;
    private string $password;
    private string $name;

    public function __construct(string $username, string $password, string $name)
    {
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
