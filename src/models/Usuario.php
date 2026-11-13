<?php
declare(strict_types=1);

abstract class Usuario
{
    public function __construct(
        protected int $id,
        protected string $nome,
        protected string $email
    ) {}

    abstract public function verPainel(): string;

    public function getNome(): string
    {
        return $this->nome;
    }
}
