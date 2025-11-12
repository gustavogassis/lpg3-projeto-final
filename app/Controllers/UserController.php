<?php

namespace App\Controllers;

use App\Domain\Entity\Usuario;
use App\Infrastructure\Repository\UserRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UserController
{
    private UserRepository $repository;
    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function get(int $user_id, Request $request, Response $response): Response
    {
        $user = $this->repository->findById($user_id);

        if (!empty($user)) {
            $response->getBody()->write(json_encode($user->toArray(), JSON_PRETTY_PRINT));
        } else {
            $response->getBody()->write(json_encode([]));
        }

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
    public function getAll(Request $request, Response $response): Response
    {
        $response_body = array_map(function (Usuario $user) {
            return $user->toArray();
        }, $this->repository->getAll());

        $response->getBody()->write(json_encode($response_body, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function getAllAlunos(Request $request, Response $response): Response
    {
        $response_body = array_map(function (Usuario $user) {
            return $user->toArray();
        }, $this->repository->getAllAlunos());

        $response->getBody()->write(json_encode($response_body, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function getAllProfessores(Request $request, Response $response): Response
    {
        $response_body = array_map(function (Usuario $user) {
            return $user->toArray();
        }, $this->repository->getAllProfessores());

        $response->getBody()->write(json_encode($response_body, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function delete(int $user_id, Request $request, Response $response): Response
    {
        $user = $this->repository->findById($user_id);

        if (empty($user)) {
            $response->getBody()->write(json_encode(['error' => 'User not found'], JSON_PRETTY_PRINT));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        try {
            $this->repository->delete($user);
        } catch (\Throwable $exception) {
            $response->getBody()->write(json_encode(['error' => $exception->getMessage()], JSON_PRETTY_PRINT));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}