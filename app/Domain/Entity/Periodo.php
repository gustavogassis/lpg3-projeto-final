<?php

declare(strict_types=1);

namespace App\Domain\Entity;

class Periodo
{
    protected ?int $id = null;
    protected int $ano;
    protected int $semestre;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'ano' => $this->ano,
            'semestre' => $this->semestre,
            'label' => $this->ano . '/' . $this->semestre,
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

    public function getAno(): int
    {
        return $this->ano;
    }

    public function setAno(int $ano): void
    {
        $this->ano = $ano;
    }

    public function getSemestre(): int
    {
        return $this->semestre;
    }

    public function setSemestre(int $semestre): void
    {
        $this->semestre = $semestre;
    }
}