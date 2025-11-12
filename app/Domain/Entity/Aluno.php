<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\UsuarioPerfil;

class Aluno extends Usuario
{
    protected string $matricula;

    public function __construct()
    {
        $this->setPerfil(UsuarioPerfil::ALUNO);
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'matricula' => $this->matricula,
        ]);
    }

    public function getMatricula(): string
    {
        return $this->matricula;
    }

    public function setMatricula(string $matricula): void
    {
        $this->matricula = $matricula;
    }
}