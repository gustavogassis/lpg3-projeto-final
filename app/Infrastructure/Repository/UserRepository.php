<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Aluno;
use App\Domain\Entity\Professor;
use App\Domain\Entity\Usuario;
use App\Infrastructure\Database;
use PDO;
use App\Domain\UsuarioPerfil;

class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConexao();
    }

    public function getAll(): array
    {
        return $this->getUsuarios();
    }

    public function getAllAlunos(): array
    {
        return $this->getUsuarios(UsuarioPerfil::ALUNO);
    }

    public function getAllProfessores(): array
    {
        return $this->getUsuarios(UsuarioPerfil::PROFESSOR);
    }

    public function getAllAdmin(): array
    {
        return $this->getUsuarios(UsuarioPerfil::ADMINISTRADOR);
    }

    public function findById(int $id): Usuario|Aluno|Professor|null
    {
        $sql = "SELECT u.*, a.matricula, p.departamento
                FROM usuarios u
                LEFT JOIN alunos a ON u.id = a.usuario_id
                LEFT JOIN professores p ON u.id = p.usuario_id
                WHERE u.id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        return $this->hydrate($data);
    }

    public function delete(Usuario $usuario): bool
    {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$usuario->getId()]);
    }

    private function getUsuarios(?UsuarioPerfil $perfil = null): array
    {
        $sql = "SELECT u.*, a.matricula, p.departamento
                FROM usuarios u
                LEFT JOIN alunos a ON u.id = a.usuario_id
                LEFT JOIN professores p ON u.id = p.usuario_id";

        $params = [];

        if ($perfil !== null) {
            $sql .= " WHERE u.perfil = ?";
            $params[] = $perfil->value;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $resultados = $stmt->fetchAll();
        $usuarios = [];

        foreach ($resultados as $data) {
            $usuarios[] = $this->hydrate($data);
        }

        return $usuarios;
    }

    private function hydrate(array $data): Usuario|Aluno|Professor
    {
        $usuario = null;

        if ($data['perfil'] === UsuarioPerfil::ALUNO->value) {
            $usuario = new Aluno();
            if (isset($data['matricula'])) {
                $usuario->setMatricula($data['matricula']);
            }
        } elseif ($data['perfil'] === UsuarioPerfil::PROFESSOR->value) {
            $usuario = new Professor();
            if (isset($data['departamento'])) {
                $usuario->setDepartamento($data['departamento']);
            }
        } else {
            $usuario = new Usuario();
            $usuario->setPerfil(UsuarioPerfil::ADMINISTRADOR);
        }

        $usuario->setId((int)$data['id']);
        $usuario->setNome($data['nome']);
        $usuario->setEmail($data['email']);
        $usuario->setSenhaHash($data['senha_hash']);

        return $usuario;
    }
}