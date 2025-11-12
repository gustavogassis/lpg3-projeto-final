<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Turma;
use App\Infrastructure\Database;
use PDO;

class TurmaRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConexao();
    }

    public function save(Turma $turma): Turma
    {
        $params = [
            $turma->getDisciplinaId(),
            $turma->getProfessorId(),
            $turma->getPeriodoId(),
            $turma->getStatusTurma()
        ];

        if (is_null($turma->getId())) {
            $sql = "INSERT INTO turmas (disciplina_id, professor_id, periodo_id, status_turma) 
                    VALUES (?, ?, ?, ?)";
        } else {
            $sql = "UPDATE turmas SET disciplina_id = ?, professor_id = ?, periodo_id = ?, status_turma = ? 
                    WHERE id = ?";
            $params[] = $turma->getId();
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        if (is_null($turma->getId())) {
            $turma->setId((int)$this->pdo->lastInsertId());
        }

        return $turma;
    }

    public function findById(int $id): ?Turma
    {
        $sql = "SELECT * FROM turmas WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        return $data ? $this->hydrate($data) : null;
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM turmas";
        $stmt = $this->pdo->query($sql);

        $turmas = [];
        foreach ($stmt->fetchAll() as $data) {
            $turmas[] = $this->hydrate($data);
        }
        return $turmas;
    }

    private function hydrate(array $data): Turma
    {
        $t = new Turma();
        $t->setId((int)$data['id']);
        $t->setDisciplinaId((int)$data['disciplina_id']);
        $t->setProfessorId((int)$data['professor_id']);
        $t->setPeriodoId((int)$data['periodo_id']);
        $t->setStatusTurma($data['status_turma']);
        return $t;
    }
}