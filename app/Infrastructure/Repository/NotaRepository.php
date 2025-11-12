<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Nota;
use App\Infrastructure\Database;
use PDO;

class NotaRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConexao();
    }

    public function save(Nota $nota): Nota
    {
        if ($nota->getId() === null) {
            $sql = "INSERT INTO notas (matricula_id, descricao, valor) VALUES (?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $nota->getMatriculaId(),
                $nota->getDescricao(),
                $nota->getValor()
            ]);
            $nota->setId((int)$this->pdo->lastInsertId());
        } else {
            $sql = "UPDATE notas SET matricula_id = ?, descricao = ?, valor = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $nota->getMatriculaId(),
                $nota->getDescricao(),
                $nota->getValor(),
                $nota->getId()
            ]);
        }
        return $nota;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM notas WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?Nota
    {
        $sql = "SELECT * FROM notas WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        return $data ? $this->hydrate($data) : null;
    }

    public function findByMatricula(int $matricula_id): array
    {
        $sql = "SELECT * FROM notas WHERE matricula_id = ? ORDER BY descricao";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$matricula_id]);

        $notas = [];
        foreach ($stmt->fetchAll() as $data) {
            $notas[] = $this->hydrate($data);
        }
        return $notas;
    }

    public function getMediaByMatricula(int $matricula_id): ?float
    {
        $sql = "SELECT AVG(valor) as media FROM notas WHERE matricula_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$matricula_id]);
        $result = $stmt->fetch();

        return $result['media'] ? (float)$result['media'] : null;
    }

    public function getHistoricoByAluno(int $aluno_id): array
    {
        $sql = "
            SELECT 
                p.ano, p.semestre,
                d.codigo as disciplina_codigo, 
                d.nome as disciplina_nome,
                m.id as matricula_id,
                n.id as nota_id,
                n.descricao as nota_descricao,
                n.valor as nota_valor,
                (SELECT AVG(n2.valor) FROM notas n2 WHERE n2.matricula_id = m.id) as media_final
            FROM alunos a
            JOIN matriculas m ON a.id = m.aluno_id
            JOIN turmas t ON m.turma_id = t.id
            JOIN disciplinas d ON t.disciplina_id = d.id
            JOIN periodos p ON t.periodo_id = p.id
            LEFT JOIN notas n ON m.id = n.matricula_id
            WHERE a.id = :aluno_id
            ORDER BY p.ano, p.semestre, d.nome, n.descricao;
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['aluno_id' => $aluno_id]);
        $rows = $stmt->fetchAll();

        $historico = [];
        foreach ($rows as $row) {
            $matriculaId = $row['matricula_id'];

            if (!isset($historico[$matriculaId])) {
                $historico[$matriculaId] = [
                    'matricula_id' => $matriculaId,
                    'periodo' => $row['ano'] . '/' . $row['semestre'],
                    'disciplina_codigo' => $row['disciplina_codigo'],
                    'disciplina_nome' => $row['disciplina_nome'],
                    'media_final' => $row['media_final'] ? (float)$row['media_final'] : null,
                    'notas' => []
                ];
            }

            if ($row['nota_id'] !== null) {
                $historico[$matriculaId]['notas'][] = [
                    'nota_id' => (int)$row['nota_id'],
                    'descricao' => $row['nota_descricao'],
                    'valor' => (float)$row['nota_valor']
                ];
            }
        }


        return array_values($historico);
    }


    private function hydrate(array $data): Nota
    {
        $nota = new Nota();
        $nota->setId((int)$data['id']);
        $nota->setMatriculaId((int)$data['matricula_id']);
        $nota->setDescricao($data['descricao']);
        $nota->setValor((float)$data['valor']);
        return $nota;
    }
}