<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Matricula;
use App\Infrastructure\Database;
use PDO;

class MatriculaRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConexao();
    }

    public function save(Matricula $matricula): Matricula
    {
        $sql = "INSERT INTO matriculas (aluno_id, turma_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $matricula->getAlunoId(),
            $matricula->getTurmaId()
        ]);

        $matricula->setId((int)$this->pdo->lastInsertId());
        return $matricula;
    }
}