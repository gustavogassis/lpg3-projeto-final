<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\UsuarioPerfil;

class Professor extends Usuario
{
    protected ?string $departamento;

    public function __construct()
    {
        $this->setPerfil(UsuarioPerfil::PROFESSOR);
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'departamento' => $this->departamento,
        ]);
    }

    public function getDepartamento(): ?string
    {
        return $this->departamento;
    }

    public function setDepartamento(?string $departamento): void
    {
        $this->departamento = $departamento;
    }
}