<?php

declare(strict_types=1);

namespace App\Domain\Entity;

class Turma
{
    protected ?int $id = null;
    protected int $disciplina_id;
    protected int $professor_id;
    protected int $periodo_id;
    protected string $status_turma;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'disciplina_id' => $this->disciplina_id,
            'professor_id' => $this->professor_id,
            'periodo_id' => $this->periodo_id,
            'status_turma' => $this->status_turma,
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

    public function getDisciplinaId(): int
    {
        return $this->disciplina_id;
    }

    public function setDisciplinaId(int $disciplina_id): void
    {
        $this->disciplina_id = $disciplina_id;
    }

    public function getProfessorId(): int
    {
        return $this->professor_id;
    }

    public function setProfessorId(int $professor_id): void
    {
        $this->professor_id = $professor_id;
    }

    public function getPeriodoId(): int
    {
        return $this->periodo_id;
    }

    public function setPeriodoId(int $periodo_id): void
    {
        $this->periodo_id = $periodo_id;
    }

    public function getStatusTurma(): string
    {
        return $this->status_turma;
    }

    public function setStatusTurma(string $status_turma): void
    {
        $this->status_turma = $status_turma;
    }


}