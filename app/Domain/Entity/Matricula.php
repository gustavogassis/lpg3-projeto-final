<?php

declare(strict_types=1);

namespace App\Domain\Entity;

class Matricula
{
    protected ?int $id = null;
    protected int $aluno_id;
    protected int $turma_id;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'aluno_id' => $this->aluno_id,
            'turma_id' => $this->turma_id,
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

    public function getAlunoId(): int
    {
        return $this->aluno_id;
    }

    public function setAlunoId(int $aluno_id): void
    {
        $this->aluno_id = $aluno_id;
    }

    public function getTurmaId(): int
    {
        return $this->turma_id;
    }

    public function setTurmaId(int $turma_id): void
    {
        $this->turma_id = $turma_id;
    }



}