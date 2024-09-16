<?php

namespace src\Domain\ValueObjects;

class CPF
{
    private string $cpf;

    public function __construct(string $cpf)
    {
        if (!$this->validate($cpf)) {
            throw new \InvalidArgumentException("CPF inválido");
        }
        $this->cpf = $cpf;
    }

    private function validate(string $cpf): bool
    {
        return preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $cpf);
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }
}
