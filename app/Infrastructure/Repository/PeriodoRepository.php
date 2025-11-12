<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Periodo;
use App\Infrastructure\Database;
use PDO;

class PeriodoRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConexao();
    }

    public function save(Periodo $periodo): Periodo
    {
        if (is_null($periodo->getId())) {
            $sql = "INSERT INTO periodos (ano, semestre) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $periodo->getAno(),
                $periodo->getSemestre()
            ]);
            $periodo->setId((int)$this->pdo->lastInsertId());
        } else {
            $sql = "UPDATE periodos SET ano = ?, semestre = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $periodo->getAno(),
                $periodo->getSemestre(),
                $periodo->getId()
            ]);
        }
        return $periodo;
    }

    public function findById(int $id): ?Periodo
    {
        $sql = "SELECT * FROM periodos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        return $data ? $this->hydrate($data) : null;
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM periodos ORDER BY ano DESC, semestre DESC";
        $stmt = $this->pdo->query($sql);

        $periodos = [];
        foreach ($stmt->fetchAll() as $data) {
            $periodos[] = $this->hydrate($data);
        }
        return $periodos;
    }

    private function hydrate(array $data): Periodo
    {
        $p = new Periodo();
        $p->setId((int)$data['id']);
        $p->setAno((int)$data['ano']);
        $p->setSemestre((int)$data['semestre']);
        return $p;
    }
}