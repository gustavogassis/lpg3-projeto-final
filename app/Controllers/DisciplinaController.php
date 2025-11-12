<?php

namespace App\Controllers;

use App\Domain\Entity\Disciplina;
use App\Infrastructure\Repository\DisciplinaRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class DisciplinaController
{
    private DisciplinaRepository $repository;

    public function __construct()
    {
        $this->repository = new DisciplinaRepository();
    }

    public function create(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            $d = new Disciplina();
            $d->setNome($data['nome'] ?? '');
            $d->setCodigo($data['codigo'] ?? '');

            if (empty($d->getNome()) || empty($d->getCodigo())) {
                $response->getBody()->write(json_encode(['erro' => 'Nome e Código são obrigatórios'], JSON_PRETTY_PRINT));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

            $this->repository->save($d);

            $response->getBody()->write(json_encode($d->toArray(), JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['erro' => $e->getMessage()], JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function getAll(Request $request, Response $response): Response
    {
        $disciplinas = $this->repository->getAll();
        $data = array_map(fn($d) => $d->toArray(), $disciplinas);

        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function get(int $id, Request $request, Response $response): Response
    {
        $disciplina = $this->repository->findById($id);
        if (!$disciplina) {
            $response->getBody()->write(json_encode(['erro' => 'Disciplina não encontrada'], JSON_PRETTY_PRINT));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write(json_encode($disciplina->toArray(), JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}