<?php

namespace App\Controllers;

use App\Domain\Entity\Periodo;
use App\Infrastructure\Repository\PeriodoRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class PeriodoController
{
    private PeriodoRepository $repository;

    public function __construct()
    {
        $this->repository = new PeriodoRepository();
    }

    public function create(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            $p = new Periodo();
            $p->setAno((int)($data['ano'] ?? 0));
            $p->setSemestre((int)($data['semestre'] ?? 0));

            if (empty($p->getAno()) || empty($p->getSemestre())) {
                $response->getBody()->write(json_encode(['erro' => 'Ano e Semestre são obrigatórios'], JSON_PRETTY_PRINT));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

            $this->repository->save($p);

            $response->getBody()->write(json_encode($p->toArray(), JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['erro' => $e->getMessage()], JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function getAll(Request $request, Response $response): Response
    {
        $periodos = $this->repository->getAll();
        $data = array_map(fn($p) => $p->toArray(), $periodos);

        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function get(int $id, Request $request, Response $response): Response
    {
        $periodo = $this->repository->findById($id);
        if (!$periodo) {
            $response->getBody()->write(json_encode(['erro' => 'Período não encontrado'], JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write(json_encode($periodo->toArray(), JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}