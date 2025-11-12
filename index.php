<?php


use App\Controllers\DisciplinaController;
use App\Controllers\MatriculaController;
use App\Controllers\NotaController;
use App\Controllers\PeriodoController;
use App\Controllers\TurmaController;
use App\Controllers\UserController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->get('/health', function (Request $request, Response $response) {
    $data = [
        'status' => 'healthy',
        'timestamp' => date('Y-m-d H:i:s'),
        'service' => 'Minha API PHP',
        'version' => '1.0.0'
    ];

    $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

// User

$app->get('/users', function (Request $request, Response $response) {
    return (new UserController())->getAll($request, $response);
});

$app->get('/users/alunos', function (Request $request, Response $response) {
    return (new UserController())->getAllAlunos($request, $response);
});

$app->get('/users/professores', function (Request $request, Response $response) {
    return (new UserController())->getAllProfessores($request, $response);
});

$app->get('/user/{id}', function (Request $request, Response $response, $args) {
    return (new UserController())->get(intval($args['id']), $request, $response);
});

$app->delete('/user/{id}', function (Request $request, Response $response, $args) {
    return (new UserController())->delete(intval($args['id']), $request, $response);
});

$app->post('/notas', function (Request $request, Response $response) {
    return (new NotaController())->create($request, $response);
});


$app->put('/notas/{id}', function (Request $request, Response $response, $args) {
    return (new NotaController())->update(intval($args['id']), $request, $response);
});


$app->delete('/notas/{id}', function (Request $request, Response $response, $args) {
    return (new NotaController())->delete(intval($args['id']), $request, $response);
});


$app->get('/matriculas/{id}/notas', function (Request $request, Response $response, $args) {
    return (new NotaController())->listByMatricula(intval($args['id']), $request, $response);
});


$app->get('/alunos/{id}/historico', function (Request $request, Response $response, $args) {
    return (new NotaController())->getHistorico(intval($args['id']), $request, $response);
});

$app->post('/matriculas', function (Request $request, Response $response) {
    return (new MatriculaController())->create($request, $response);
});

$app->post('/disciplinas', function (Request $request, Response $response) {
    return (new DisciplinaController())->create($request, $response);
});
$app->get('/disciplinas', function (Request $request, Response $response) {
    return (new DisciplinaController())->getAll($request, $response);
});
$app->get('/disciplinas/{id}', function (Request $request, Response $response, $args) {
    return (new DisciplinaController())->get(intval($args['id']), $request, $response);
});


$app->post('/periodos', function (Request $request, Response $response) {
    return (new PeriodoController())->create($request, $response);
});
$app->get('/periodos', function (Request $request, Response $response) {
    return (new PeriodoController())->getAll($request, $response);
});
$app->get('/periodos/{id}', function (Request $request, Response $response, $args) {
    return (new PeriodoController())->get(intval($args['id']), $request, $response);
});


$app->post('/turmas', function (Request $request, Response $response) {
    return (new TurmaController())->create($request, $response);
});
$app->get('/turmas', function (Request $request, Response $response) {
    return (new TurmaController())->getAll($request, $response);
});
$app->get('/turmas/{id}', function (Request $request, Response $response, $args) {
    return (new TurmaController())->get(intval($args['id']), $request, $response);
});

$app->run();