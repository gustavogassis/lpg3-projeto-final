<?php

namespace App\Controllers;

use App\Domain\Entity\Turma;
use App\Infrastructure\Repository\TurmaRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class TurmaController
{
    private TurmaRepository $repository;

    public function __construct()
    {
        $this->repository = new TurmaRepository();
    }

    public function create(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            $t = new Turma();
            $t->setDisciplinaId((int)($data['disciplina_id'] ?? 0));
            $t->setProfessorId((int)($data['professor_id'] ?? 0));
            $t->setPeriodoId((int)($data['periodo_id'] ?? 0));

            $t->setStatusTurma($data['status_turma'] ?? 'aberta');

            if (empty($t->getDisciplinaId()) || empty($t->getProfessorId()) || empty($t->getPeriodoId())) {
                $response->getBody()->write(json_encode(['erro' => 'disciplina_id, professor_id e periodo_id são obrigatórios'], JSON_PRETTY_PRINT));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

            $this->repository->save($t);

            $response->getBody()->write(json_encode($t->toArray(), JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['erro' => $e->getMessage()], JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function getAll(Request $request, Response $response): Response
    {
        $turmas = $this->repository->getAll();
        $data = array_map(fn($t) => $t->toArray(), $turmas);

        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function get(int $id, Request $request, Response $response): Response
    {
        $turma = $this->repository->findById($id);
        if (!$turma) {
            $response->getBody()->write(json_encode(['erro' => 'Turma não encontrada'], JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write(json_encode($turma->toArray(), JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}