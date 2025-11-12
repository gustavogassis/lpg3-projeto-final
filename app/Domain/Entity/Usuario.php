<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\UsuarioPerfil;

class Usuario
{
    protected ?int $id = null;
    protected string $nome;
    protected string $email;
    protected UsuarioPerfil $perfil;
    protected ?string $senha_hash = null;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'perfil' => $this->perfil->value,
            'senha_hash' => $this->senha_hash,
        ];
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPerfil(): string
    {
        return $this->perfil->value;
    }

    public function setPerfil(UsuarioPerfil $perfil): void
    {
        $this->perfil = $perfil;
    }

    public function getSenhaHash(): ?string
    {
        return $this->senha_hash;
    }

    public function setSenhaHash(string $senha_hash): void
    {
        $this->senha_hash = $senha_hash;
    }

    public function setSenhaPlana(string $senhaPlana): void
    {
        $this->senha_hash = password_hash($senhaPlana, PASSWORD_DEFAULT);
    }

    public function verificarSenha(string $senhaPlana): bool
    {
        return password_verify($senhaPlana, $this->senha_hash);
    }
}