<?php

namespace App\Controllers;

use App\Domain\Entity\Matricula;
use App\Infrastructure\Repository\MatriculaRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class MatriculaController
{
    private MatriculaRepository $repository;

    public function __construct()
    {
        $this->repository = new MatriculaRepository();
    }

    public function create(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            $matricula = new Matricula();
            $matricula->setAlunoId((int)($data['aluno_id'] ?? 0));
            $matricula->setTurmaId((int)($data['turma_id'] ?? 0));

            if (empty($matricula->getAlunoId()) || empty($matricula->getTurmaId())) {
                $response->getBody()->write(json_encode(['erro' => 'aluno_id e turma_id são obrigatórios'], JSON_PRETTY_PRINT));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

            $this->repository->save($matricula);

            $response->getBody()->write(json_encode($matricula->toArray(), JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['erro' => $e->getMessage()], JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}