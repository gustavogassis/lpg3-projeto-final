<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Disciplina;
use App\Infrastructure\Database;
use PDO;

class DisciplinaRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConexao();
    }

    public function save(Disciplina $disciplina): Disciplina
    {
        if (is_null($disciplina->getId())) {
            $sql = "INSERT INTO disciplinas (nome, codigo) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $disciplina->getNome(),
                $disciplina->getCodigo()
            ]);
            $disciplina->setId((int)$this->pdo->lastInsertId());
        } else {
            $sql = "UPDATE disciplinas SET nome = ?, codigo = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $disciplina->getNome(),
                $disciplina->getCodigo(),
                $disciplina->getId()
            ]);
        }
        return $disciplina;
    }

    public function findById(int $id): ?Disciplina
    {
        $sql = "SELECT * FROM disciplinas WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        return $data ? $this->hydrate($data) : null;
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM disciplinas ORDER BY nome ASC";
        $stmt = $this->pdo->query($sql);

        $disciplinas = [];
        foreach ($stmt->fetchAll() as $data) {
            $disciplinas[] = $this->hydrate($data);
        }
        return $disciplinas;
    }

    private function hydrate(array $data): Disciplina
    {
        $d = new Disciplina();
        $d->setId((int)$data['id']);
        $d->setNome($data['nome']);
        $d->setCodigo($data['codigo']);
        return $d;
    }
}