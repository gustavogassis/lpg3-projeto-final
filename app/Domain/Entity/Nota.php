<?php

declare(strict_types=1);

namespace App\Domain\Entity;

class Nota
{
    protected ?int $id = null;
    protected int $matricula_id;
    protected string $descricao;
    protected float $valor;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'matricula_id' => $this->matricula_id,
            'descricao' => $this->descricao,
            'valor' => $this->valor,
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

    public function getMatriculaId(): int
    {
        return $this->matricula_id;
    }

    public function setMatriculaId(int $matricula_id): void
    {
        $this->matricula_id = $matricula_id;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function setValor(float $valor): void
    {
        $this->valor = $valor;
    }
}