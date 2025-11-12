<?php

namespace App\Controllers;

use App\Domain\Entity\Nota;
use App\Infrastructure\Repository\NotaRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class NotaController
{
    private NotaRepository $repository;

    public function __construct()
    {
        $this->repository = new NotaRepository();
    }

    public function create(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            $nota = new Nota();
            $nota->setMatriculaId((int)($data['matricula_id'] ?? 0));
            $nota->setDescricao($data['descricao'] ?? '');
            $nota->setValor((float)($data['valor'] ?? 0));

            if (empty($nota->getMatriculaId()) || empty($nota->getDescricao())) {
                $response->getBody()->write(json_encode(['erro' => 'matricula_id e descricao são obrigatórios'], JSON_PRETTY_PRINT));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

            $this->repository->save($nota);

            $response->getBody()->write(json_encode($nota->toArray(), JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['erro' => $e->getMessage()], JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }


    public function update(int $nota_id, Request $request, Response $response): Response
    {
        try {
            $nota = $this->repository->findById($nota_id);
            if (!$nota) {
                $response->getBody()->write(json_encode(['erro' => 'Nota não encontrada'], JSON_PRETTY_PRINT));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }

            $data = $request->getParsedBody();

            $nota->setDescricao($data['descricao'] ?? $nota->getDescricao());
            $nota->setValor(isset($data['valor']) ? (float)$data['valor'] : $nota->getValor());

            $this->repository->save($nota);

            $response->getBody()->write(json_encode($nota->toArray(), JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['erro' => $e->getMessage()], JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function delete(int $nota_id, Request $request, Response $response): Response
    {
        $nota = $this->repository->findById($nota_id);
        if (!$nota) {
            $response->getBody()->write(json_encode(['erro' => 'Nota não encontrada'], JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $this->repository->delete($nota_id);

        $response->getBody()->write(json_encode([], JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(204);
    }

    public function listByMatricula(int $matricula_id, Request $request, Response $response): Response
    {
        $notas = $this->repository->findByMatricula($matricula_id);

        $data = array_map(function (Nota $n) {
            return $n->toArray();
        }, $notas);

        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function getHistorico(int $aluno_id, Request $request, Response $response): Response
    {
        $data = $this->repository->getHistoricoByAluno($aluno_id);

        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}